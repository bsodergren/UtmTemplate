<?php

namespace UTMTemplate;

use UTMTemplate\Browser\Browser;
use UTMTemplate\Browser\Device;
use UTMTemplate\Browser\Os;

class UtmDevice
{
    public static $DEVICE = 'DESKTOP';
    public static $DETECT_BROWSER = true;
    public static $MOBILE_DEVICE = false;

    public static $MOBILE_TEMPLATE = false;
    public static $DEFAULT_TEMPLATE = false;

    public static $USER_MOBILE_TEMPLATE = '';
    public static $USER_DEFAULT_TEMPLATE = '';

    public static $MOBILE_ASSETS_URL = '';
    public static $MOBILE_ASSETS_PATH = '';

    public static $template_path = [
        // 'APPLICATION' => 'Default',
        'MOBILE' => 'Mobile',
        'DESKTOP' => 'Default',
    ];

    public function __construct()
    {
        self::$DETECT_BROWSER = true;
        self::$DEVICE = $this->run();
        if (false === self::$DEFAULT_TEMPLATE) {
            self::$DEFAULT_TEMPLATE = Template::$TEMPLATE_DIR;
        }

        if (false === self::$MOBILE_TEMPLATE) {
            self::$MOBILE_TEMPLATE = __DIR__.\DIRECTORY_SEPARATOR.'Templates'.\DIRECTORY_SEPARATOR.self::$template_path[self::$DEVICE];
        }
    }

    public function run()
    {
        $device = new Device();
        $os = new Os();
        $browser = new Browser();

        /*
         *  windows desktop
         * Edge
         * Windows
         * unknown
         *
         * Media App
         * Chrome
         * Windows
         * Unknown
         *
         *
         * iPhone
         * Edge
         * iOS
         * iPhone
         *
         *
         */
        // return 'APPLICATION';
        if ('Edge' == $browser->getName()) {
            if ('Windows' == $os->getName()) {
                return 'DESKTOP';
            }
            if ('iOS' == $os->getName()) {
                return 'MOBILE';
            }
            if ('unknown' == $device->getName()) {
                return 'DESKTOP';
            }
        } elseif ('Chrome' == $browser->getName()) {
            if ('Windows' == $os->getName()) {
                return 'DESKTOP';
            }
        } elseif ('Safari' == $browser->getName()) {
            if ('iOS' == $os->getName()) {
                return 'MOBILE';
            }
        }

        return 'DESKTOP';
        // return [$browser->getName(), $device->getName(), $os->getName()];
    }

    public static function getAssetURL($type, $files)
    {
        $html = null;

        foreach ($files as $file) {
            $filePath = self::getThemepath(__LAYOUT_URL_PATH__).\DIRECTORY_SEPARATOR.$file;
            $url = __URL_LAYOUT__.'/'.strtolower(self::$DEVICE).'/'.$file;
            if (!file_exists($filePath)) {
                $filePath = self::getDefaultTheme(__LAYOUT_URL_PATH__).\DIRECTORY_SEPARATOR.$file;
                $url = __URL_LAYOUT__.'/'.strtolower(self::$default_theme).'/'.$file;
                if (!file_exists($filePath)) {
                    $url = null;
                    dd($filePath);
                }
            }
            if (null !== $url) {
                $url = $url.'?'.random_int(100000, 999999);
                switch ($type) {
                    case 'image':
                        $html .= $url;
                        break;
                    case 'css':
                        $html .= '<link rel="stylesheet" href="'.$url.'">'.\PHP_EOL;
                        break;
                    case 'js':
                        $html .= '<script src="'.$url.'" crossorigin="anonymous"></script>'.\PHP_EOL;
                        break;
                }
            }
        }

        return $html;
    }

    public static function IsMobile()
    {
        return 'MOBILE' === self::$DEVICE;
    }
}
