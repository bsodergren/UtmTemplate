<?php

namespace UTMTemplate;

use UTM\Utilities\Colors;
use UTMTemplate\Traits\Callbacks;
use UTMTemplatec\Render;

class Template
{
    use Callbacks;
    public $html;

    public static $Render = false;
    public static $registeredCallbacks = false;
    public static $flushdummy;
    public static $BarStarted = false;
    public static $BarHeight = 30;
    public static $params = [];
    public static $TEMPLATE_DIR = __DIR__.'/src/Templates';
    public static $USER_TEMPLATE_DIR = '';
    public static $THEME_DIR = '';
    public static $TEMPLATE_COMMENTS = true;

    public static $SITE_URL = '';
    public static $SITE_PATH = '';
    public static $ASSETS_URL = '';

    private $template_file;
    private $replacement_array;

    private static $RenderHTML = '';

    public function __construct($options = [])
    {
        ob_implicit_flush(true);
        @ob_end_flush();

        $flushdummy = '';
        for ($i = 0; $i < 1200; ++$i) {
            $flushdummy .= '      ';
        }
        self::$flushdummy = $flushdummy;
        if (true == self::$registeredCallbacks) {
            $this->registerCallback(self::$registeredCallbacks);
        }
    }

    public function registerCallback($constant, $function = '')
    {
        if (\is_array($constant)) {
            foreach ($constant as $key => $value) {
                $this->registerCallback($key, $value);
            }
        } else {
            if (!\array_key_exists($constant, $this->registered_callbacks)) {
                $this->registered_callbacks = array_merge($this->registered_callbacks, [$constant => $function]);
            }
        }
    }

    public static function ProgressBar($timeout = 5, $name = 'theBar')
    {
        if ('start' == strtolower($timeout)) {
            self::$BarStarted = true;
            self::pushhtml('progress_bar', ['NAME' => $name, 'BAR_HEIGHT' => self::$BarHeight]);

            return;
        }

        if ($timeout > 0) {
            $timeout *= 1000;
            $update_inv = $timeout / 100;
            if (false == self::$BarStarted) {
                self::pushhtml('progress_bar', ['NAME' => $name, 'BAR_HEIGHT' => self::$BarHeight]);
                self::$BarStarted = false;
            }

            self::pushhtml('progressbar_js', ['SPEED' => $update_inv, 'NAME' => $name]);
        }
    }

    public static function pushhtml($template, $params = [])
    {
        $contents = Render::html($template, $params);
        self::push($contents);
    }

    public static function put($contents, $color = null, $break = true)
    {
        $nlbr = '';
        if (null !== $color) {
            $colorObj = new Colors();
            //    $contents = $colorObj->getColoredSpan($contents, $color);
        }
        if (true == $break) {
            $nlbr = '<br>';
        }
        // echo $contents;
        self::push($contents.'  '.$nlbr);
    }

    public static function push($contents)
    {
        echo $contents; // , self::$flushdummy;
        flush();
        @ob_flush();
    }

    public function parseHtml($html_text)
    {
        foreach ($this->registered_callbacks as $pattern => $function) {
            if (!str_contains($pattern, '::')) {
                $pattern = 'self::'.$pattern;
                $class = $this;
            } else {
                $parts = explode('::', $pattern);
                // UtmDump([$pattern,$parts,$function]);
                $class = (new $parts[0]());
                // $function = $parts[1];
            }

            $html_text = preg_replace_callback(\constant($pattern), [$class, $function], $html_text);
        }

        return preg_replace_callback('/(##(\w+,?\w+)##)(.*)(##)/iU', [$this, 'callback_color'], $html_text);

        // $html_text     = preg_replace_callback('/(!!(\w+,?\w+)!!)(.*)(!!)/iU', [$this, 'callback_badge'], $html_text);
    }

    private function getTemplateFile($template, $extension = 'HTML')
    {
        if ('' == $extension) {
            $extension = 'html';
        }

        $extension = '.'.$extension;
        $template_file = self::$USER_TEMPLATE_DIR.'/'.$template.$extension;

        if (!file_exists($template_file)) {
            $template_file = self::$TEMPLATE_DIR.'/'.$template.$extension;
            if (!file_exists($template_file)) {
                $html_text = '<h1>NO TEMPLATE FOUND<br>';
                $html_text .= 'FOR <pre>'.$template_file.'</pre></h1> <br>';
                utmdump($html_text);
                $this->html = $html_text;

                return $html_text;
            }
        }

        $this->template_file = $template.$extension;

        return file_get_contents($this->template_file);
    }

    public function template($template = '', $replacement_array = [], $extension = 'html')
    {
        unset($this->replacement_array);
        $html_text = $this->getTemplateFile($template, $extension);

        $replacement_array['self'] = $this->template_file;
        $replacement_array = array_merge($replacement_array, self::$params);
        $this->replacement_array = $replacement_array;

        if ('.html' == $extension && true == self::$TEMPLATE_COMMENTS) {
            $_text = '<!-- {$self} --> '.\PHP_EOL;
            $_text .= trim($html_text).\PHP_EOL;
            $_text .= '<!-- end {$self} -->'.\PHP_EOL;
            $html_text = $_text;
            unset($_text);
        }

        $html_text = $this->parseHtml($html_text);

        if ('.js' == $extension) {
            $html_text = '<script>'.\PHP_EOL.$html_text.\PHP_EOL.'</script>';
        }
        if ('.css' == $extension) {
            $html_text = '<style>'.\PHP_EOL.$html_text.\PHP_EOL.'</style>';
        }
        $html_text = trim($html_text).\PHP_EOL;

        $this->html = $html_text;

        return $html_text;
    }
}
