<?php

namespace UTMTemplate\Traits\Callbacks;

trait IfStatement
{
    public function callback_if_statement($matches)
    {
        $return = false;
        $compare = $matches[1];
        switch ($compare) {
            case str_contains($compare, '>'):
                $array = explode('>', $compare);

                if ($array[0] == $array[1]) {
                    return false;
                }
                if ($array[0] > $array[1]) {
                    return $matches[2];
                }

                return '';

            case str_contains($compare, '<'):
                $array = explode('<', $compare);
                if ($array[0] == $array[1]) {
                    return false;
                }

                if ($array[0] < $array[1]) {
                    return $matches[2];
                }

                return '';

            case str_contains($compare, '='):
                $array = explode('=', $compare);
                if ($array[0] == $array[1]) {
                    return $matches[2];
                }

                return '';
        }

        return $matches[0];

        // return $return;
    }
}
