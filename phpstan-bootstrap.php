<?php

// Polyfill mb_split for static analysis environments that may not have ext-mbstring.
if (! function_exists('mb_split')) {
    /**
     * Split multibyte string by regular expression using preg_split fallback.
     * Note: This is a best-effort shim for static analysis tools only.
     *
     * @param string $pattern
     * @param string $string
     * @param int $limit
     * @return array|false
     */
    function mb_split(string $pattern, string $string, int $limit = -1)
    {
        $regex = '/' . $pattern . '/u';
        return preg_split($regex, $string, $limit === -1 ? 0 : $limit);
    }
}
