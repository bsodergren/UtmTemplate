<?php

namespace UTMTemplate\Traits\Callbacks;

trait IfStatement
{
    public function callback_if_statement($matches)
    {
        $return = '';
        $compare = $matches[1];

        switch ($compare) {
            case str_contains($compare, ">"):
                $array = explode('>', $compare);

                if ($array[0] == $array[1]) {
                    return '';
                }
                if ($array[0] > $array[1]) {
                    $return = $matches[2];
                }
                break;
            case str_contains($compare, "<"):
                $array = explode('<', $compare);
                if ($array[0] == $array[1]) {
                    return '';
                }


                if ($array[0] < $array[1]) {
                    $return = $matches[2];
                }
                break;
            case str_contains($compare, "="):
                $array = explode('=', $compare);

                if ($array[0] == $array[1]) {
                    $return = $matches[2];
                }
                break;

        }


        // $array = explode('=', $compare);

        // if ($array[0] == $array[1]) {
        //     $return = $matches[2];
        // }

        return $return;



        // $func = function () use ($compare, $matches) {
        //     $compare = "return " . $compare . ";";
        //     return eval($compare);
        // };

        // if ($func() === true) {
        //     return $matches[2];
        // }

        // return '';
    }
}
