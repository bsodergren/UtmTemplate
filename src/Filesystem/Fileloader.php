<?php

namespace UTMTemplate\Filesystem;

use Nette\Utils\FileSystem;
use UTMTemplate\Template;
use UTMTemplate\UtmDevice;

class Fileloader
{
    public static $template_file;
    public $html;

    public static function get_filelist($directory, $ext = 'log')
    {
        $cache_key = str_replace(\DIRECTORY_SEPARATOR, '_', $directory).'_'.$ext;

        if (true === Template::$USE_TEMPLATE_CACHE) {
            $cache_array = UtmCache::get($cache_key);

            if (false !== $cache_array) {
                return $cache_array;
            }
        }

        $files_array = [];
        if (!file_exists($directory)) {
            return [];
        }

        if ($all = opendir($directory)) {
            while ($filename = readdir($all)) {
                if ('.' == $filename) {
                    continue;
                }
                if ('..' == $filename) {
                    continue;
                }
                $file = Filesystem::normalizePath($directory.'/'.$filename);
                if (!is_dir($file)) {
                    if (preg_match('/('.$ext.')$/', $filename)) {
                        $files_array[] = $file;
                    } // end if
                } else {
                    $files_array = array_merge($files_array, self::get_filelist($file, $ext));
                }

                // end if
            } // end while
            closedir($all);
        } // end if
        sort($files_array);
        UtmCache::put($cache_key, $files_array, 60);

        return $files_array;
    }

    public static function getTemplate($template)
    {
        if (null === $template) {
            $html_text = '<h1>NO TEMPLATE FOUND<br>';
            $html_text .= 'FOR <pre>'.self::$template_file.'</pre></h1> <br>';
            return $html_text;
        }
        $cache_key = md5(str_replace(\DIRECTORY_SEPARATOR, '_', $template));
        if (true === Template::$USE_TEMPLATE_CACHE) {
            $template_text = UtmCache::get($cache_key);
            if (false !== $template_text) {
                return $template_text;
            }
        }
        $template_text = file_get_contents($template);
        UtmCache::put($cache_key, $template_text, 60);

        return $template_text;
    }

    public static function getTemplateFile($template, $extension = 'HTML')
    {

   
        if ('' == $extension) {
            $extension = 'html';
        }
        $extension = '.'.str_replace('.', '', $extension);
        $template_file = null;

        $cache_key = str_replace(\DIRECTORY_SEPARATOR, '_', $template).'_'.$extension;
        if (true === Template::$USE_TEMPLATE_CACHE) {
            $template_text = UtmCache::get($cache_key.'_html');

            if (false !== $template_text) {
                $tmpfile = UtmCache::get($cache_key.'_file');
                if (false !== $tmpfile) {
                    self::$template_file = $tmpfile;

                    return $template_text;
                }
            }
        }
        if (true === UtmDevice::$DETECT_BROWSER) {
            $templateArray = Template::$TemplateArray[UtmDevice::$DEVICE];
            foreach ($templateArray as $templateDir) {
                $temp_file = $templateDir.\DIRECTORY_SEPARATOR.$template.$extension;
                if (file_exists($temp_file)) {
                    $template_file = $temp_file;
                    break;
                }           
            }
        }
        
        if (null === $template_file) {
            $user_file = UtmDevice::$USER_DEFAULT_TEMPLATE.'/'.$template.$extension;
            $default_file = UtmDevice::$DEFAULT_TEMPLATE.'/'.$template.$extension;
            if (file_exists($user_file)) {
               $template_file = $user_file;
            } elseif (file_exists($default_file)) {
                $template_file = $default_file;
            }
        }

        self::$template_file = $template_file;
        if (null === $template_file) {
            self::$template_file = $template.$extension;
        }
        $template_text = self::getTemplate($template_file);

        UtmCache::put($cache_key.'_file', $template_file, 60);
        UtmCache::put($cache_key.'_html', $template_text, 60);

        return $template_text;
    }

    public static function getIncludeFile($file, $type)
    {
        $filename = null;
        $filepath = $type.'/'.$file;

        if (\array_key_exists(UtmDevice::$DEVICE, Template::$AssetsArray)) {
            $asset = Template::$AssetsArray[UtmDevice::$DEVICE];

            $file = $asset['PATH'].\DIRECTORY_SEPARATOR.$filepath;
            if (file_exists($file)) {
                $filename = $asset['URL'].'/'.$filepath;
            }
        }

        if (null === $filename) {
            $file = Template::$ASSETS_PATH.\DIRECTORY_SEPARATOR.$filepath;
            if (file_exists($file)) {
                $filename = Template::$ASSETS_URL.'/'.$filepath;
            }
        }

        return $filename;
    }
}
