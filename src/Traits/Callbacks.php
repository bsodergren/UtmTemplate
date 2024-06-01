<?php

namespace UTMTemplate\Traits;

use KubAT\PhpSimple\HtmlDomParser;
use UTMTemplate\Functions\Traits\Parser;
use UTMTemplate\HTML\Elements;

trait Callbacks
{
    use Parser;

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

    public function callback_if_statement($matches)
    {
        $compare = $matches[1];
        $array = explode('=', $compare);
        $return = '';
        if ($array[0] == $array[1]) {
            $return = $matches[2];
        }

        return $return;
    }

    public function callback_text_variable($matches)
    {
        $key = $matches[1];
        $text = $this->parse_variable($matches);
        if ($text == $key) {
            return $text;
        }

        return $text;
    }

    public function callback_parse_variable($matches)
    {
        $key = $matches[1];
        $text = $this->parse_variable($matches);
        if ($text == $key) {
            return '';
        }

        return $text;
    }

    public function callback_parse_include($matches)
    {
        $method = $matches[1];

        return Elements::$method($matches[2]);
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
