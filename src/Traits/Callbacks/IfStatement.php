<?php

namespace UTMTemplate\Traits\Callbacks;

trait IfStatement
{
    public function callback_if_statement($matches)
    {
        $return = '';
        $compare = $matches[1];

        $func = function () use ($compare, $matches) {
            $compare = "return " . $compare . ";";
            return eval($compare);
        };

        if ($func() === true) {
            return $matches[2];
        }

        return '';
    }
}
