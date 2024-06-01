<?php

namespace UTMTemplate;

use UTM\Utilities\Colors;
use UTMTemplate\Filesystem\Fileloader;
use UTMTemplate\Filesystem\UtmCache;
use UTMTemplate\Functions\Functions;
use UTMTemplate\Traits\Callbacks;
use UTMTemplate\Traits\Filters;

class Template
{
    use Callbacks;
    use Filters;


    
    public const STYLESHEET_CALLBACK = '|{{(stylesheet)=([a-zA-Z-_/\.]+)\|?([a-zA-Z=$,.\?\{\}]+)?}}|i';
    public const JAVASCRIPT_CALLBACK = '|{{(javascript)=([a-zA-Z-_/\.]+)\|?([a-zA-Z=$,.\?\{\}]+)?}}|i';
    public const TEMPLATE_CALLBACK = '|{{(template)=([a-zA-Z-_/\.]+)\|?(.*)?}}|i';
    public const VARIABLE_CALLBACK = '|{\$([a-zA-Z_-]+)}|';
    public const LANG_CALLBACK = '|{L ([a-zA-Z_]+)}|';
    public const JS_VAR_CALLBACK = '|!!([a-zA-Z_-]+)!!|';
    public const IF_CALLBACK = '|{if="([^"]+)"}(.*?){\/if}|misu';
    public const CSS_VAR_CALLBACK = '|\$([a-zA-Z_-]+)\$|';
    public const EXPLODE_CALLBACK = '|{replace="?([^"]+)"?}|mis';
    public const BUTTON_CALLBACK = '|{{button=([a-zA-Z_]+)\|?(.*)?}}|i';
    public const ICON_CALLBACK = '|{{icon=([a-zA-Z_]+)\|?(.*)?}}|i';


    public $html;

    public static $Render = false;
    public static $registeredCallbacks = false;
    public static $registeredFilters = false;

    public static $flushdummy;
    public static $BarStarted = false;
    public static $BarHeight = 30;
    public static $params = [];
    public static $TEMPLATE_DIR = __DIR__.'/Templates/Default';
    public static $USER_TEMPLATE_DIR = '';
    public static $THEME_DIR = '';
    public static $TEMPLATE_COMMENTS = true;

    public static $CACHE_DIR = false;
    public static $USE_TEMPLATE_CACHE = false;
    public static $SITE_URL = '';
    public static $SITE_PATH = '';

    public static $ASSETS_URL = '';
    public static $ASSETS_PATH = '';

    public static $TemplateArray = [];
    public static $AssetsArray = [];
    

    private $template_file;
    private $replacement_array = [];

    private static $RenderHTML = '';

    public function __construct()
    {
        // UtmDd(__DIR__);

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
        if (true == self::$registeredFilters) {
            $this->registerFilter(self::$registeredFilters);
        }

        if (true == UtmDevice::$DETECT_BROWSER) {
            self::$TemplateArray = [
                'MOBILE' => [
                    UtmDevice::$USER_MOBILE_TEMPLATE,
                    UtmDevice::$MOBILE_TEMPLATE,
                ],
                'DESKTOP' => [
                    UtmDevice::$USER_DEFAULT_TEMPLATE,
                    UtmDevice::$DEFAULT_TEMPLATE,
                ],
            ];
            self::$AssetsArray = [
                'MOBILE' => [
                    'PATH' => UtmDevice::$MOBILE_ASSETS_PATH,
                    'URL' =>UtmDevice::$MOBILE_ASSETS_URL,
                ],
                'DESKTOP' => [
                    'PATH' => self::$ASSETS_PATH,
                    'URL' =>self::$ASSETS_URL,
                ],
            ];
        } else {
            self::$TemplateArray = [
                'DESKTOP' => [
                    self::$USER_TEMPLATE_DIR,
                    self::$TEMPLATE_DIR,
                ],
            ];
            self::$AssetsArray = [
                'DESKTOP' => [
                    'PATH' => self::$ASSETS_PATH,
                    'URL' => self::$ASSETS_URL,
                ],
            ];
        }

        UtmCache::init();
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

    public function registerFilter($constant, $function = '')
    {
        if (\is_array($constant)) {
            foreach ($constant as $key => $value) {
                $this->registerFilter($key, $value);
            }
        } else {
            if (!\array_key_exists($constant, $this->registered_filters)) {
                $this->registered_filters = array_merge($this->registered_filters, [$constant => $function]);
            }
        }
    }

    public static function __callStatic($name, $arguments)
    {
        $icon = $arguments[0];
        $args = [];
        if (\array_key_exists('1', $arguments)) {
            $args = $arguments[1];
        }

        return (new Functions())->{$icon}($args);
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

    public function template($template = '', $replacement_array = [], $extension = 'html')
    {
        $extension = str_replace('.', '', $extension);
        unset($this->replacement_array);

        $html_text = Fileloader::getTemplateFile($template, $extension);

        $replacement_array['self'] = str_replace(\dirname(__DIR__, 4), '', Fileloader::$template_file);
        $replacement_array = array_merge($replacement_array, self::$params);
        $this->replacement_array = $replacement_array;
        if ('html' == $extension && true == self::$TEMPLATE_COMMENTS) {
            $_text = '<!-- {$self} --> '.\PHP_EOL;
            $_text .= trim($html_text).\PHP_EOL;
            $_text .= '<!-- end {$self} -->'.\PHP_EOL;
            $html_text = $_text;
            unset($_text);
        }

        $html_text = $this->parseHtml($html_text);

        if ('js' == $extension) {
            $html_text = '<script>'.\PHP_EOL.$html_text.\PHP_EOL.'</script>';
        }
        if ('css' == $extension) {
            $html_text = '<style>'.\PHP_EOL.$html_text.\PHP_EOL.'</style>';
        }
        $html_text = trim($html_text).\PHP_EOL;

        $this->html = $html_text;

        return $html_text;
    }
}
