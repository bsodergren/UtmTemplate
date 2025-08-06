<?php

namespace UTMTemplate\Traits;

use KubAT\PhpSimple\HtmlDomParser;
use UTMTemplate\Functions\Traits\Parser;
use UTMTemplate\HTML\Elements;
use UTMTemplate\Traits\Callbacks\IfStatement;
use UTMTemplate\Traits\Callbacks\Variable;

trait Callbacks
{
    use IfStatement;
    use Parser;
    use Variable;

    public $registered_callbacks = [
        'LANG_CALLBACK' => 'callback_text_variable',
        'VARIABLE_CALLBACK' => 'callback_parse_variable',
        'JS_VAR_CALLBACK' => 'callback_parse_variable',
        'CSS_VAR_CALLBACK' => 'callback_parse_variable',
        'STYLESHEET_CALLBACK' => 'callback_parse_include',
        'JAVASCRIPT_CALLBACK' => 'callback_parse_include',
        'TEMPLATE_CALLBACK' => 'callback_parse_include',
        'IF_CALLBACK' => 'callback_if_statement',
        'EXPLODE_CALLBACK' => 'callback_explode_callback',
        'BUTTON_CALLBACK' => 'callback_parse_button',
        'ICON_CALLBACK' => 'callback_parse_icon',
    ];

    public static function get_callback($method)
    {
        $parts = explode('::', $method);

        $key = array_search($parts[1], self::$Registered_Callbacks);

        [$_, $filter] = explode('::', $key);

        return self::$Registered_Callbacks[$filter];
        // utmdump(Template::$Registered_Callbacks[$filter]);
        // ;
    }

    public function callback_parse_icon($matches)
    {
        return self::Icons($matches[1], $matches);
    }

    public function callback_parse_button($matches)
    {
        utmdump($matches);
    }

    public function callback_explode_callback($matches)
    {
        $data = str_getcsv($matches[1], ',', "'");

        return str_replace($data[1], $data[2], $data[0]).$data[2];
    }

    public function callback_parse_source_include($matches)
    {
        if ('!' == $matches[1]) {
            return '';
        }

        $method = $matches[2];

        return Elements::$method($matches[3]);
    }

    public function callback_parse_include($matches)
    {
        if ('!' == $matches[1]) {
            return '';
        }
        $method = $matches[2];

        return Elements::$method($matches[3]);
    }

    public function parse_urllink($text)
    {
        $dom = HtmlDomParser::str_get_html($text);

        if (false === $dom) {
            return $text;
        }

        $elems = $dom->find('a');

        if (0 == \count($elems)) {
            return $text;
        }

        foreach ($elems as $a) {
            $a->setAttribute('data-bs-placement', 'top');
            $a->setAttribute('data-bs-toggle', 'tooltip');
            $a->setAttribute('title', $a->href);
        }

        return $dom;
    }

    private function callback_badge($matches)
    {
        $text = $matches[3];
        $font = '';
        $class = $matches[2];
        if (str_contains($matches[2], ',')) {
            $arr = explode(',', $matches[2]);
            $class = $arr[0];
            $font = 'fs-'.$arr[1];
        }

        $style = 'class="badge text-bg-'.$class.' '.$font.'"';

        return '<span '.$style.'>'.$text.'</span>';
    }

    private function callback_color($matches)
    {
        $text = $matches[3];
        $style = 'style="';
        if (str_contains($matches[2], ',')) {
            $colors = explode(',', $matches[2]);
            $style .= 'color: '.$colors[0].'; background:'.$colors[1].';';
        } else {
            $style .= 'color: '.$matches[2].';';
        }
        $style .= '"';

        return '<span '.$style.'>'.$text.'</span>';
    }
}
