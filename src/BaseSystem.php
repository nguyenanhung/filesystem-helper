<?php
/**
 * Project filesystem-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 15/02/2023
 * Time: 20:24
 */

namespace nguyenanhung\Libraries\Filesystem;

/**
 * Class BaseSystem
 *
 * @package   nguyenanhung\Libraries\Filesystem
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class BaseSystem implements Environment
{
    public function getVersion()
    {
        return self::VERSION;
    }
}
