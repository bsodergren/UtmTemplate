<?php

namespace UTMTemplate\Functions\Traits;

use UTMTemplate\Render;

trait Icons
{
    use Parser;
    public static $IconsDir = 'elements/Icons';
    public $IconMatches;

    private function returnMatch($key)
    {
        $var = $this->parseVars($this->IconMatches);
        if (\array_key_exists($key, $var)) {
            return $var[$key];
        }

        return false;
    }

    private function getColor($key = 'color')
    {
        $color = $this->returnMatch($key);
        if (false === $color) {
            return 'Red';
        }

        return $color;
    }

    private function useCSS()
    {
        $key = 'UseCSS';

        $var = $this->parseVars($this->IconMatches);
        if (\array_key_exists($key, $var)) {
            return $var[$key];
        }

        return false;
    }

    private function getIconClass($icon)
    {
        $className = $this->returnMatch('className');
        if (false !== $className) {
            $className = '_'.$className;
        }

        return $icon.'Class'.$className;
    }

    private function getIconStyle()
    {
        $color = $this->getColor();

        return "style='fill:".$color."'";
    }

    private function getIconCSS($icon)
    {
        $class = $this->getIconClass($icon);
        $color = $this->getColor();

        return Render::html(self::$IconsDir.'/css', ['Color' => $color, 'Class' => $class]);
    }

    public function getIcon($icon, $matches)
    {
        $this->IconMatches = $matches;

        $params = [];
        if (false !== $this->useCSS()) {
            $params['Class'] = $this->getIconClass($icon);
            $params['ICONSTYLE'] = $this->getIconStyle($icon);
            $params['CSS'] = $this->getIconCSS($icon);
        }

        return Render::html(self::$IconsDir.'/'.$icon, $params);
    }
}
