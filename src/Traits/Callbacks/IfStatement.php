<?php

/**
 *
 *   Plexweb
 *
 */

namespace UTMTemplate\Traits\Callbacks;

trait IfStatement
{
    public function callback_if_statement($matches)
    {
        $return   = false;//  $matches[0];

        $compare  = $matches[1];
        preg_match('/([\w\s]+)?(\=|<|>)([\w\s]+)/', $compare, $output_array);


        $first    = $output_array[1];
        $second   = $output_array[3];
        $operator = $output_array[2];

        if ($operator == '=') {

            if ($first == '') {
                $first = false;
            }

            switch ($second) {
                case "true":
                    if ($first == true) {
                        $return = $matches[2];
                    } else {
                        $return = false;
                    }
                    break;

                case "false":
                    if ($first == false) {
                        $return = $matches[2];
                    } else {
                        $return = false;
                    }
                    break;
                default:
                    if ($first == $second) {
                        $return = $matches[2];
                    } else {
                        $return = false;
                    }

            }
        }

        if ($first == '') {
            $return = false;
        }
        if ($operator == '<') {
            if ($first <= $second) {
                $return = $matches[2];
            } else {
                $return = false;
            }
        }

        if ($operator == '>') {
            if ($first > $second) {
                $return = $matches[2];
            } else {
                $return = false;
            }
        }

        return $return;

    }
}
