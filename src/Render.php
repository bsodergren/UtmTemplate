<?php

namespace UTMTemplatec;

use UTMTemplate\Template;


class Render extends Template
{
    public function __construct() {}

    public static function html($template, $replacement_array = '')
    {
        return self::return($template, $replacement_array);
    } // end Render::html()

    public static function javascript($template, $replacement_array = '')
    {
        return self::return($template, $replacement_array, 'js');
    } // end Render::html()

    public static function stylesheet($template, $replacement_array = '')
    {
        return self::return($template, $replacement_array, 'css');
    }

    public static function echo($template = '', $array = '')
    {
        $template_obj = new Template();
        $template_obj->template($template, $array);

        echo $template_obj->html;
    }

    public static function return($template = '', $array = '', $js = '')
    {
        $template_obj = new Template();
        $template_obj->template($template, $array, $js);

        return $template_obj->html;
    }
}
