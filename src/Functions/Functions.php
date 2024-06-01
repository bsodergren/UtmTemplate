<?php

namespace UTMTemplate\Functions;

use UTMTemplate\Filesystem\Fileloader;
use UTMTemplate\Functions\Traits\Icons;
use UTMTemplate\Functions\Traits\Parser;
use UTMTemplate\Template;

class Functions
{
    use Icons;
    use Parser;

    public static $IconsDir = 'elements/Icons';
    public static $iconList;
    public $IconMatches;

    public function __construct()
    {
        if (null === self::$iconList) {
            $dir = Template::$TEMPLATE_DIR.'/'.self::$IconsDir;
            $include_array = Fileloader::get_filelist($dir, 'html');
            foreach ($include_array as $required_file) {
                self::$iconList[] = basename($required_file, '.html');
            }

            $dir = Template::$USER_TEMPLATE_DIR.'/'.self::$IconsDir;
            $include_array = Fileloader::get_filelist($dir, 'html');
            foreach ($include_array as $required_file) {
                self::$iconList[] = basename($required_file, '.html');
            }
            self::$iconList = array_unique(self::$iconList);
        }
    }

    public function __call($name, $arguments)
    {
        if (\in_array($name, self::$iconList)) {
            return $this->getIcon($name, $arguments[0]);
        }

        return false;
    }
}
