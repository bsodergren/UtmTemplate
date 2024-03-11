<?php

namespace UTMTemplatec;

use UTMTemplate\Template;


class Render 
{
    public function __construct() {}

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
