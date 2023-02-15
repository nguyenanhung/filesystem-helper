<?php
/**
 * Project filesystem-helper
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 15/02/2023
 * Time: 21:55
 */

namespace nguyenanhung\Libraries\Filesystem;

class BaseSystem implements Environment
{
    public function getVersion(): string
    {
        return self::VERSION;
    }
}
