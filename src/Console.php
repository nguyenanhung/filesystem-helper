<?php
/**
 * Project filesystem-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/24/2021
 * Time: 01:10
 */

namespace nguyenanhung\Libraries\Filesystem;

/**
 * Class Console
 *
 * @package   nguyenanhung\Libraries\Filesystem
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Console
{
    /**
     * Function writeLn
     *
     * @param        $message
     * @param string $newLine
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/24/2021 11:45
     */
    public static function writeLn($message, $newLine = "\n")
    {
        if (function_exists('json_encode') && (is_array($message) || is_object($message))) {
            $message = json_encode($message);
        }
        echo $message . $newLine;
    }
}
