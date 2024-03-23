<?php

namespace UTMTemplate\HTML;

use UTMTemplatec\Render;

class Elements
{
    public static $ElementsDir = 'elements/html';

    public static function template($template)
    {
        return Render::return($template, []);
    }

    public static function stylesheet($stylesheet)
    {
        $stylesheet = 'css/'.$stylesheet;
        $file = __LAYOUT_PATH__.'/'.$stylesheet;

        if (false == file_exists($file)) {
            return '';
        }

        return Render::return(self::$ElementsDir.'/link', ['CSS_URL' => __LAYOUT_URL__.$stylesheet]);
    }

    public static function javascript($javafile)
    {
        $javafile = 'js/'.$javafile;
        $file = __LAYOUT_PATH__.'/'.$javafile;

        if (false == file_exists($file)) {
            return '';
        }

        return Render::return(self::$ElementsDir.'/script', ['SCRIPT_URL' => __LAYOUT_URL__.$javafile]);
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
        $disabled = null)
    {
        $disabled_style = ' style="background-color: rgba(32, 32,32, 0.5) !important;" ';
        $selected_style = ' style="background-color: rgba(0, 0,0, 0.5)!important;" ';

        $html = '';
        $option_selected = [];
        $options = [];
        $option_default = [];
        $option_disabled = [];

        if (\is_array($selected)) {
            $matchKey = array_key_first($selected);
            $matchValue = $selected[$matchKey];
        } else {
            $matchKey = 'text';
            $matchValue = $selected;
        }

        foreach ($array as $idx => $val) {
            $optionDisabled = false;
            if (\is_array($val)) {
                $text = $val['text'];
                $value = $val['value'];
            } else {
                $text = $val;
                $value = $val;
            }
            if (str_contains($disabled, $value)) {
                $optionDisabled = true;
            }

            if (null !== $matchValue) {
                if (${$matchKey} == $matchValue) {
                    $checked = true;
                    $option_selected[] = '<option class="'.$class.'" value="'.$value.'" '.$selected_style.' disabled selected>'.$text.'</option>'."\n";
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
                $default = ' selected';
                $option_default[] = '<option class="'.$class.'" value=""  selected>'.$blank.'</option>'."\n";
            }
        }
        $optionsArray = array_merge($option_default, $option_selected, $options, $option_disabled);
        $html = implode("\n", $optionsArray);

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

    public static function javaRefresh($url, $timeout = 0)
    {
        global $_REQUEST;

        $html = '<script>'."\n";

        if ($timeout > 0) {
            $html .= 'setTimeout(function(){ ';
        }

        $html .= "window.location.href = '".$url."';";

        if ($timeout > 0) {
            $timeout *= 1000;
            $html .= '}, '.$timeout.');';
        }
        $html .= "\n".'</script>';

        echo $html;
    }

    public static function Comment($text)
    {
        return \PHP_EOL.'<!-- ---------- '.$text.' ----------- --->'.\PHP_EOL;
    }
}
