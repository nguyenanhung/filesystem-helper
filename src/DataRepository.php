<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:38
 */

namespace nguyenanhung\Libraries\Filesystem;

if (!class_exists('nguyenanhung\Libraries\Filesystem\DataRepository')) {
    /**
     * Class DataRepository
     *
     * @package   nguyenanhung\Libraries\Filesystem
     * @author    713uk13m <dev@nguyenanhung.com>
     * @copyright 713uk13m <dev@nguyenanhung.com>
     */
    class DataRepository extends BaseSystem
    {
        /**
         * Function getData - Hàm lấy nội dung config được quy định trong thư mục config
         *
         * @param string $configName
         *
         * @return array|mixed
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/08/2021 40:44
         */
        public static function getData(string $configName)
        {
            $path = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $configName . '.php';
            if (is_file($path) && file_exists($path)) {
                return require $path;
            }

            return array();
        }

        /**
         * Hàm lấy nội dung Data từ 1 file bất kỳ trong hệ thống
         *
         * @param string $filename Đường dẫn file config
         *
         * @return array|mixed
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 09:25
         *
         */
        public static function getDataContent(string $filename)
        {
            if (is_file($filename) && file_exists($filename)) {
                return require $filename;
            }

            return array();
        }
    }
}
