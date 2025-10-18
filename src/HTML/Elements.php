<?php

namespace UTMTemplate\HTML;

use UTMTemplate\Filesystem\Fileloader;
use UTMTemplate\HTML\Traits\Options;
use UTMTemplate\HTML\Traits\Select;
use UTMTemplate\Render;

class Elements
{
    use Options;
    use Select;

    public static $ElementsDir = 'elements/html';

    public static function url($url, $text, $class = '', $extra = '')
    {
        return Render::return(self::$ElementsDir.'/a', ['LINK' => $url, 'CLASS' => $class, 'EXTRA' => $extra, 'TEXT' => $text]);
    }

    public static function template($template)
    {
        return Render::return($template, []);
    }

    public static function stylesheet($stylesheet)
    {
        if (str_starts_with($stylesheet, 'http')) {
            $stylesheet_link = $stylesheet;
        } else {
            $stylesheet_link = Fileloader::getIncludeFile($stylesheet, 'css');
            if (null === $stylesheet_link) {
                return '';
            }
        }

        return Render::return(self::$ElementsDir.'/link', ['CSS_URL' => $stylesheet_link]);
    }

    public static function javascript($javafile)
    {
        if (str_starts_with($javafile, 'http')) {
            $javafile_link = $javafile;
        } else {
            $javafile_link = Fileloader::getIncludeFile($javafile, 'js');
            if (null === $javafile_link) {
                return '';
            }
        }

        return Render::return(self::$ElementsDir.'/script', ['SCRIPT_URL' => $javafile_link]);
    }

    public static function addButton($text, $type = 'button', $class = 'btn button', $extra = '', $javascript = '')
    {
        return Render::return(self::$ElementsDir.'/button', [
            'TEXT' => $text,
            'TYPE' => $type,
            'CLASS' => $class,
            'EXTRA' => $extra,
            'JAVASCRIPT' => $javascript,
        ]);
    }

    public static function add_hidden($name, $value, $attributes = '')
    {
        $html = '';
        $html .= '<input '.$attributes.' type="hidden" name="'.$name.'"  value="'.$value.'">';

        return $html."\n";
    }

    public static function draw_checkbox($name, $value, $text = '')
    {
        global $pub_keywords;

        $checked = '';
        $current_value = $value;

        if (1 == $current_value) {
            $checked = 'checked';
        }

        $html = '<input type="hidden" name="'.$name.'" value="0">';
        $html .= '<input class="form-check-input" type="checkbox" name="'.$name.'" value=1 '.$checked.'>'.$text;

        return $html;
    }

    public static function javaRefresh($url, $timeout_sec = 0, $text = '')
    {
        global $_REQUEST;

        if ($timeout_sec > 0) {
            $timeout = $timeout_sec / 100;

            $p = new ProgressBar();
            if ('' != $text) {
                $textLength = imagefontwidth('12') * \strlen($text);
                $p->setStyle(['width' => $textLength.'px', 'rounded' => true]);
            }

            $p->render();

            for ($i = 0; $i < ($size = 100); ++$i) {
                $p->setProgressBarProgress($i * 100 / $size, $text);
                usleep(1000000 * $timeout);
            }
            $p->setProgressBarProgress(100, $text);
        }

        echo Render::return(
            self::$ElementsDir.'/javascript',
            ['javascript' => "window.location.href = '".$url."';"]
        );
    }

    public static function radioButtons($label, $fieldName, $array = [], $selected = '')
    {
        $btn = '';
        $idx = 0;
        foreach ($array as $name => $value) {
            $checked = '';
            ++$idx;
            if ($selected == $value['value']) {
                $checked = ' checked';
            }

            $btn .= Render::html(
                self::$ElementsDir.'/radiobtn',
                [
                    'Name' => $fieldName,
                    'label' => $value['name'],
                    'Value' => $value['value'],
                    'Id' => $fieldName.'_'.$idx,
                    'checked' => $checked,
                ]
            );
        }

        return $btn;
    }

    public static function Comment($text)
    {
        return \PHP_EOL.'<!-- ---------- '.$text.' ----------- --->'.\PHP_EOL;
    }
}
