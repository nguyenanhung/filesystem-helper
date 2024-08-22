<?php

/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:43
 */
if (!function_exists('is_php')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param string $version
     *
     * @return    bool    TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string)$version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}
if (!function_exists('is_php_before')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param string $version
     *
     * @return    bool    TRUE if the current version is $version or higher
     */
    function is_php_before($version)
    {
        static $_is_php;
        $version = (string)$version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '<=');
        }

        return $_is_php[$version];
    }
}
if (!function_exists('stringToPath')) {
    /**
     * Function stringToPath - Combine multiple strings to a path.
     *
     * @param $paths
     *
     * @return array|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 54:11
     */
    function stringToPath($paths)
    {
        if (!is_array($paths)) {
            $paths = func_get_args();
        }

        $path = implode(DIRECTORY_SEPARATOR, $paths);
        $path = preg_replace('#' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . '+#', DIRECTORY_SEPARATOR, $path);

        return preg_replace('#([' . DIRECTORY_SEPARATOR . ']+$)#', '', $path);
    }
}
if (!function_exists('string_to_path')) {
    /**
     * Function string_to_path - Combine multiple strings to a path.
     *
     * @param $paths
     *
     * @return array|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 54:11
     */
    function string_to_path($paths)
    {
        return stringToPath($paths);
    }
}
if (!function_exists('remove_invisible_characters')) {
    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param string $str
     * @param bool $url_encoded
     *
     * @return    string
     */
    function remove_invisible_characters($str, $url_encoded = true)
    {
        $nonDisplay = array();
        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $nonDisplay[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
            $nonDisplay[] = '/%1[0-9a-f]/i';    // url encoded 16-31
            $nonDisplay[] = '/%7f/i';    // url encoded 127
        }
        $nonDisplay[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127
        do {
            $str = preg_replace($nonDisplay, '', $str, -1, $count);
        } while ($count);

        return $str;
    }
}
