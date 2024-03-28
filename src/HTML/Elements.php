<?php

namespace UTMTemplate\HTML;

<<<<<<< HEAD
=======
use UTMTemplate\Template;
>>>>>>> d31f686 (update)
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
        $file = Template::$SITE_PATH.'/'.$stylesheet;

        if (false == file_exists($file)) {
            return '';
        }

        return Render::return(self::$ElementsDir.'/link', ['CSS_URL' => Template::$SITE_URL.$stylesheet]);
    }

    public static function javascript($javafile)
    {
        $javafile = 'js/'.$javafile;
        $file = Template::$SITE_PATH.'/'.$javafile;

        if (false == file_exists($file)) {
            return '';
        }

        return Render::return(self::$ElementsDir.'/script', ['SCRIPT_URL' => Template::$SITE_URL.$javafile]);
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

<<<<<<< HEAD
    public static function SelectOptions(
        $array,
        $selected = null,
        $blank = null,
        $class = 'filter-option text-bg-primary',
        $disabled = null)
    {
        // $disabled_style = ' style="background-color: rgba(32, 32,32, 0.5) !important;" ';
        // $selected_style = ' style="background-color: rgba(0, 0,0, 0.5)!important;" ';
=======
    public static function SelectOptions($array, $selected = null, $blank = null, $class = 'filter-option text-bg-primary')
    {
        $html = '';
        $default_option = '';
        $default = '';
        $checked = '';

        if (\is_array($selected)) {
            $matchKey = array_key_first($selected);
            $matchValue = $selected[$matchKey];
        } else {
            $matchKey = 'text';
            $matchValue = $selected;
        }

        foreach ($array as $val) {
            $checked = '';
>>>>>>> 75a05ce (hh)

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
            $checked = false;
            if (\is_array($val)) {
                $text = $val['text'];
                $value = $val['value'];
            } else {
                $text = $val;
                $value = $val;
            }
<<<<<<< HEAD
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
=======

            if (null !== $matchValue) {
                if (${$matchKey} == $matchValue) {
                    $checked = ' selected';
                }
            }
            $html .= '<option class="'.$class.'" value="'.$value.'" '.$checked.'>'.$text.'</option>'."\n";
>>>>>>> 75a05ce (hh)
        }

        if (null !== $blank) {
            if (false === $checked) {
                $option_default[] = '<option style="background-color: #cccccc;" disabled selected>Select An Option</option>'."\n";

                // $option_default[] = '<option class="'.$class.'" value=""  selected>'.$blank.'</option>'."\n";
            }
        }

        $sep = '<option style="font-size: 1pt; background-color: #000000;" disabled>&nbsp;</option>';
        $optionsArray[] = implode("\n", $option_default);
        $optionsArray[] = implode("\n", $options);

        $optionsArray[] = implode("\n", $option_selected);
        $optionsArray[] = implode("\n", $option_disabled);

        $html = implode($sep, $optionsArray);

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
