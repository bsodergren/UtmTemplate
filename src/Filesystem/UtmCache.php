<?php

namespace UTMTemplate\Filesystem;

use UTM\Bundle\Stash\Cache;
use UTMTemplate\Template;

class UtmCache
{
    public static $stash;

    public static function init()
    {
        if (!is_dir(Template::$CACHE_DIR)) {
            mkdir(Template::$CACHE_DIR, 0777, true);
        }

        self::$stash = Cache::file(function (): void {
            $this->setCacheDir(Template::$CACHE_DIR);
        });
    }

    public static function get($key)
    {
        if (false === Template::$USE_TEMPLATE_CACHE) {
            // utmdd(Template::$USE_TEMPLATE_CACHE);
            return null;
        }

        return self::$stash->get($key);
    }

    public static function put($key, $value)
    {
        if (false === Template::$USE_TEMPLATE_CACHE) {
            return false;
        }

        return self::$stash->put($key, $value);
    }
}
