<?php

namespace UTMTemplate\HTML;

use UTMTemplate\Filesystem\Fileloader;
use UTMTemplate\Render;

class Elements
{
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
        $stylesheet_link = Fileloader::getIncludeFile($stylesheet, 'css');
        if (false === $stylesheet_link) {
            return '';
        }

        return Render::return(self::$ElementsDir.'/link', ['CSS_URL' => $stylesheet_link]);
    }

    public static function javascript($javafile)
    {
        $javafile_link = Fileloader::getIncludeFile($javafile, 'js');

        if (false === $javafile_link) {
            return '';
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

    public static function SelectOptions(
        $array,
        $selected = null,
        $blank = null,
        $class = 'filter-option text-bg-primary',
        $disabled = null,
    ) {
        $disabled_style = ' style="background-color: rgba(32, 32,32, 0.5) !important;" ';
        $selected_style = ' style="background-color: rgba(0, 0,0, 0.5)!important;" ';

        $html = '';
        $option_selected = [];
        $options = [];
        $option_default = [];
        $option_disabled = [];
        $checked = false;

        if (\is_array($selected) && \count($selected) > 0) {
            $matchKey = array_key_first($selected);
            $matchValue = $selected[$matchKey];
        } else {
            $matchKey = 'text';
            $matchValue = $selected;
        }

        foreach ($array as $idx => $val) {
            $optionDisabled = false;
            $checked = false;

            if (\is_array($val)) {
                if (!\array_key_exists('text', $val)) {
                    $val['value'] = array_key_first($val);
                    $val['text'] = $val[$val['value']];
                }
                $text = $val['text'];
                $value = $val['value'];
            } else {
                $text = $val;
                $value = $val;
            }

            if (null !== $disabled) {
                if (str_contains($disabled, $value)) {
                    $optionDisabled = true;
                }
            }

            if (null !== $matchValue) {
                if (${$matchKey} == $matchValue) {
                    $checked = true;
                    $option_selected[] = '<option class="'.$class.'" value="'.$value.'" '.$selected_style.'  selected>'.$text.'</option>'."\n";
                    continue;
                }
            }
            if (true === $optionDisabled) {
                $option_disabled[] = '<option class="'.$class.'" value="'.$value.'" '.$disabled_style.' disabled>'.$text.'</option>'."\n";
                continue;
            }
            $options[] = '<option class="'.$class.'" value="'.$value.'">'.$text.'</option>'."\n";
        }

        if (null !== $blank) {
            if (false === $checked) {
                // $option_default[] = '<option style="background-color: #cccccc;" disabled selected>Select An Option</option>'."\n";
                $option_default[] = '<option class="'.$class.'" value="" disabled selected>'.$blank.'</option>'."\n";
            }
        }

        $sep = '<option style="font-size: 1pt; background-color: #000000;" disabled>&nbsp;</option>'."\n";
        $optionsArray[] = implode(' ', $option_default);
        $optionsArray[] = implode(' ', $options);

        $optionsArray[] = implode(' ', $option_selected);
        $optionsArray[] = implode(' ', $option_disabled);
        foreach ($optionsArray as $o) {
            if ('' == $o) {
                continue;
            }
            $allOptions[] = trim($o);
        }

        $html = implode($sep, $allOptions);

        return $html;
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

            $btn .= Render::html(self::$ElementsDir.'/radiobtn',
                [
                    'Name' => $fieldName,
                    'label' => $value['name'],
                    'Value' => $value['value'],
                    'Id' => $fieldName.'_'.$idx,
                    'checked' => $checked,
                ]);
        }

        return $btn;
    }

    public static function Comment($text)
    {
        return \PHP_EOL.'<!-- ---------- '.$text.' ----------- --->'.\PHP_EOL;
    }
}
