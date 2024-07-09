<?php

if (!function_exists('dbEscapeLikeRaw')) {
    function dbEscapeLikeRaw(string $str): string
    {
        $ret = str_replace(['%', '_'], ['\%', '\_'], DB::getPdo()->quote($str));
        return $ret && strlen($ret) >= 2 ? trim($str, "\%") : $ret;
    }
}
