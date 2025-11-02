<?php

namespace UTMTemplate\Traits;

use Symfony\Component\Finder\Finder;
use UTM\Utilities\UTMArray;
use UTMTemplate\Template;
use UTMTemplate\UtmDevice;

trait Pages
{
    public static $TEMPLATE_ARRAY = [];

    public static function BuildPages()
    {
        if (UtmDevice::$DETECT_BROWSER == true) {
            self::$TemplateArray = [
                'MOBILE'  => [
                    UtmDevice::$USER_MOBILE_TEMPLATE,
                    UtmDevice::$MOBILE_TEMPLATE,
                ],
                'DESKTOP' => [
                    UtmDevice::$USER_DEFAULT_TEMPLATE,
                    UtmDevice::$DEFAULT_TEMPLATE,
                ],
            ];
            self::$AssetsArray = [
                'MOBILE'  => [
                    'PATH' => UtmDevice::$MOBILE_ASSETS_PATH,
                    'URL'  => UtmDevice::$MOBILE_ASSETS_URL,
                ],
                'DESKTOP' => [
                    'PATH' => self::$ASSETS_PATH,
                    'URL'  => self::$ASSETS_URL,
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
                    'URL'  => self::$ASSETS_URL,
                ],
            ];
        }

        $templateDir = [];

        foreach (Template::$TemplateArray as $device => $device_dir) {
            if (UtmDevice::$DETECT_BROWSER === true) {
                if (UtmDevice::$DEVICE == 'DESKTOP') {
                    if ($device == 'MOBILE') {
                        continue;
                    }
                }
            }
            foreach ($device_dir as $path) {
                $templateDir[] = $path;
            }
        }

        $finder       = new Finder;
        $ext          = '.html';
        $fileNames = [];
        foreach ($templateDir as $path) {
            if (file_exists($path)) {
                $files = $finder->files()->name('*' . $ext)->in($path);
                foreach ($files as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $relativeFilePath = $file->getRelativePathname();
                    $fileName = str_replace($ext, '', $relativeFilePath);
                    if(file_exists($absoluteFilePath))
                    {
                        if(UTMArray::search($fileNames,$fileName,'',true)){
                            continue;
                        }

                        $fileNames[]  = $fileName;
                        $fileArray[] = $absoluteFilePath;
                    }
                }
            }
        }
                    utmdump($fileArray);

utmdd("f");




    }
}
