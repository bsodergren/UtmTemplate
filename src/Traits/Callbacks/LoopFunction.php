<?php

namespace UTMTemplate\Traits\Callbacks;

use UTMTemplate\Render;

trait LoopFunction
{
    public function callback_loop_function($matches)
    {
        $array = unserialize($matches[1]);

        $variableNames = $matches[3];
        $templateName = $matches[2];
        $varpcs = explode(',', $variableNames);
        foreach ($varpcs as $name) {
            ${$name} = $name;
        }
        $html = '';
        foreach ($array as $loopIteratior) {
            $param = [];
            foreach (array_keys($loopIteratior) as $i) {
                $param[$varpcs[$i]] = $loopIteratior[$i];
            }
            $html .= Render::return($templateName, $param);
        }

        return $html;
    }
}
