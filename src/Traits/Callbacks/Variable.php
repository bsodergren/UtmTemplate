<?php

namespace UTMTemplate\Traits\Callbacks;

trait Variable
{
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
}
