<?php

if (!function_exists('array_flattWithUnderscore')) {
    function array_flattWithUnderscore($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, array_flattWithUnderscore($value, $prepend . $key . ':'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}

function array_setFromUnderscore(&$array, $key, $value)
{
    if ($key === null) {
        return $array = $value;
    }

    $keys = explode(':', $key);

    while (count($keys) > 1) {
        $key = array_shift($keys);

        // If the key doesn't exist at this depth, we will just create an empty array
        // to hold the next value, allowing us to create the arrays to hold final
        // values at the correct depth. Then we'll keep digging into the array.
        if (!isset($array[$key]) || !is_array($array[$key])) {
            $array[$key] = [];
        }

        $array = &$array[$key];
    }

    $array[array_shift($keys)] = $value;

    return $array;
}

