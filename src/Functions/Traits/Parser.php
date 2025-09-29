<?php

namespace UTMTemplate\Functions\Traits;

trait Parser
{
    private function parse_variable($matches)
    {
        $text = '';
        $key = $matches[1];

        if (\defined($key)) {
            $text = \constant($key);
        } elseif (\is_array($this->replacement_array)) {
            if (\array_key_exists($key, $this->replacement_array)) {
                $text = $this->replacement_array[$key];
                //  unset($this->replacement_array[$key]);
            }
        } else {
            return $key;
        }

        if (\array_key_exists(2, $matches)
        && \array_key_exists(3, $matches)
        ) {
            if ('+' == $matches[2]) {
                $text = (int) $text + (int) $matches[3];
            }
        }

        if (\array_key_exists(4, $matches)) {
            if (\array_key_exists($key, $GLOBALS)) {
                $globalArray = $GLOBALS[$key];
                if (\array_key_exists($matches[5], $globalArray)) {
                    $text = $globalArray[$matches[5]];
                }
            }
        }

        return $text;
    }

    private function parseVars($matches)
    {
        if (0 == \count($matches)) {
            return [];
        }
        $parts = explode(',', $matches[2]);
        foreach ($parts as $value) {
            if (str_contains($value, '=')) {
                $v_parts = explode('=', $value);
                if (str_contains($v_parts[0], '?')) {
                    $q_parts = explode('?', $v_parts[0]);

                    $values['query'][$q_parts[1]] = $v_parts[1];
                    continue;
                }
                $values[$v_parts[0]] = $v_parts[1];
                continue;
            }
            $values['var'][] = $value;
        }

        return $values;
    }
}
