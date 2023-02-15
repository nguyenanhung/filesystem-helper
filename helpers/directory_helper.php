<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:29
 */
/**
 * CodeIgniter Directory Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         EllisLab Dev Team
 * @link           https://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('directory_map')) {
    /**
     * Create a Directory Map
     *
     * Reads the specified directory and builds an array
     * representation of it. Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param string $source_dir      Path to source
     * @param int    $directory_depth Depth of directories to traverse
     *                                (0 = fully recursive, 1 = current dir, etc)
     * @param bool   $hidden          Whether to show hidden files
     *
     * @return    array|bool
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = false)
    {
        if ($fp = @opendir($source_dir)) {
            $fileData = array();
            $newDepth = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (false !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' || $file === '..' || ($hidden === false && $file[0] === '.')) {
                    continue;
                }

                is_dir($source_dir . $file) && $file .= DIRECTORY_SEPARATOR;

                if (($directory_depth < 1 || $newDepth > 0) && is_dir($source_dir . $file)) {
                    $fileData[$file] = directory_map($source_dir . $file, $newDepth, $hidden);
                } else {
                    $fileData[] = $file;
                }
            }

            closedir($fp);

            return $fileData;
        }

        return false;
    }
}

if (!function_exists('directory_get_name')) {
    /**
     * Function directory_get_name
     *
     * @param $path
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 52:48
     */
    function directory_get_name($path)
    {
        return basename($path);
    }
}

if (!function_exists('directory_get_parent')) {
    /**
     * Function directory_get_parent
     *
     * @param $path
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 52:51
     */
    function directory_get_parent($path)
    {
        return dirname($path);
    }
}

if (!function_exists('directory_create')) {
    /**
     * Create a directory and all subdirectories.
     *
     * @param     $path
     * @param int $mode
     *
     * @return bool
     */
    function directory_create($path, $mode = 0777)
    {
        if (!directory_exists($path)) {
            return mkdir($path, $mode, true);
        }

        return true;
    }
}

if (!function_exists('directory_delete')) {
    /**
     * Delete a directory and all of its files.
     *
     * @param $path
     *
     * @return bool
     */
    function directory_delete($path)
    {
        if (directory_exists($path)) {
            $files = directory_list($path);

            foreach ($files as $file) {
                $filePath = string_to_path($path, $file);

                if (is_dir($filePath)) {
                    directory_delete($filePath);
                } else {
                    file_delete($filePath);
                }
            }

            return rmdir($path);
        }

        return file_delete($path);
    }
}

if (!function_exists('directory_exists')) {
    /**
     * Check if a directory exists.
     *
     * @param $path
     *
     * @return bool
     */
    function directory_exists($path)
    {
        return is_dir($path);
    }
}

if (!function_exists('directory_rename')) {
    /**
     * Rename a directory.
     *
     * @param $path
     * @param $newName
     *
     * @return bool
     */
    function directory_rename($path, $newName)
    {
        return file_rename($path, $newName);
    }
}

if (!function_exists('directory_move')) {
    /**
     * Move directory to the specified path.
     *
     * @param $oldPath
     * @param $newPath
     *
     * @return bool
     */
    function directory_move($oldPath, $newPath)
    {
        return file_move($oldPath, $newPath);
    }
}

if (!function_exists('directory_copy')) {
    /**
     * Copy a directory and all of its contents to the specified path
     * and create all necessary subdirectories.
     *
     * @param $oldPath
     * @param $newPath
     *
     * @return void
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 57:10
     */
    function directory_copy($oldPath, $newPath)
    {
        if (directory_exists($oldPath)) {
            if (!directory_exists($newPath)) {
                directory_create($newPath);
            }

            $files = directory_list($oldPath);

            foreach ($files as $file) {
                $oldFilePath = string_to_path($oldPath, $file);
                $newFilePath = string_to_path($newPath, $file);
                if (directory_exists($oldFilePath)) {
                    directory_copy($oldFilePath, $newFilePath);
                } else {
                    file_copy($oldFilePath, $newFilePath);
                }
            }
        } else {
            file_copy($oldPath, $newPath);
        }
    }
}

if (!function_exists('directory_clear')) {
    /**
     * Delete all files and directories inside a directory.
     *
     * @param $path
     */
    function directory_clear($path)
    {
        if (directory_exists($path)) {
            foreach (directory_list($path, true) as $file) {
                directory_delete($file);
            }
        }
    }
}
if (!function_exists('directory_list_php_53')) {
    function directory_list_php_53($directory)
    {
        // create an array to hold directory list
        $results = array();

        // create a handler for the directory
        $handler = opendir($directory);

        // keep going until all files in directory have been read
        while ($file = readdir($handler)) {

            // if $file isn't this directory or its parent,
            // add it to the results array
            if ($file != '.' && $file != '..')
                $results[] = $file;
        }

        // tidy up: close the handler
        closedir($handler);

        // done!
        return $results;
    }
}
if (!function_exists('directory_list')) {
    /**
     * Return a list of files and directories.
     *
     * @param      $path
     * @param bool $absolute
     *
     * @return array
     */
    function directory_list($path, $absolute = false)
    {
        if (PHP_VERSION_ID < 50400) {
            return directory_list_php_53($path);
        } else {
            if (!directory_exists($path)) {
                return array();
            }

            return array_values(array_diff(scandir($path), array('.', '..')));
        }
    }
}
if (!function_exists('directory_list_files')) {
    /**
     * Return a list of files.
     *
     * @param      $path
     * @param bool $absolute
     *
     * @return array
     */
    function directory_list_files($path, $absolute = false)
    {
        if (PHP_VERSION_ID < 50400) {
            return directory_list_php_53($path);
        } else {
            return directory_list($path,$absolute);
        }
    }
}
if (!function_exists('directory_list_directories')) {
    /**
     * Function directory_list_directories
     *
     * @param       $path
     * @param false $absolute
     *
     * @return array
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 52:56
     */
    function directory_list_directories($path, $absolute = false)
    {
        if (PHP_VERSION_ID < 50400) {
            return directory_list_php_53($path);
        } else {
            return directory_list($path,$absolute);
        }
    }
}
