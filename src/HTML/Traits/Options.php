<?php

namespace UTMTemplate\HTML\Traits;

trait Options
{
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
}
