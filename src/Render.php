<?php
/**
 *
 *   Plexweb
 *
 */

namespace UTMTemplate;

class Render
{
    public static $TemplateObj = null;

    public function __construct()
    {
    }

    public static function html($template, $replacement_array = [])
    {
        return self::return($template, $replacement_array);
    } // end Render::html()

    public static function javascript($template, $replacement_array = [])
    {
        return self::return($template, $replacement_array, '.js');
    } // end Render::html()

    public static function stylesheet($template, $replacement_array = [])
    {
        return self::return($template, $replacement_array, '.css');
    }

    public static function echo($template = '', $replacement_array = [])
    {
        echo self::return($template, $replacement_array);
    }

    public static function return($template = '', $array = [], $extension = 'html')
    {
        if (self::$TemplateObj === null) {
            self::$TemplateObj = new Template();
        }

        $template_obj  = self::$TemplateObj;
        $template_obj->template($template, $array, $extension);
        $html_text     = $template_obj->html;

        foreach ($template_obj->registered_filters as $function => $values) {
            // if (!str_contains($pattern, '::')) {
            //     $pattern = 'self::'.$pattern;
            //     $class = $template_obj;
            // } else {
            $parts     = explode('::', $function);
            // UtmDump([$pattern,$parts,$function]);
            $html_text = \call_user_func_array(
                [$parts[0], $parts[1]],
                [$html_text, $values]
            );

            // $function = $parts[1];
            // }

            // $html_text = preg_replace_callback(\constant($pattern), [$class, $function], $html_text);
        }

        return $html_text;
    }
}
