<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:28
 */

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package      CodeIgniter
 * @author       EllisLab Dev Team
 * @copyright    Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright    Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license      https://opensource.org/licenses/MIT	MIT License
 * @link         https://codeigniter.com
 * @since        Version 1.0.0
 * @filesource
 */

use nguyenanhung\Libraries\Filesystem\Filesystem;
use nguyenanhung\Libraries\Filesystem\Mimes;

/**
 * CodeIgniter File Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         EllisLab Dev Team
 * @link           https://codeigniter.com/user_guide/helpers/file_helper.html
 */

if (!function_exists('is_really_writable')) {
    /**
     * Tests for file writability
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute. is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @link    https://bugs.php.net/bug.php?id=54709
     *
     * @param string $file
     *
     * @return    bool
     */
    function is_really_writable($file): bool
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') || !ini_get('safe_mode'))) {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        }

        if (!is_file($file) || ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }

        fclose($fp);

        return true;
    }
}

if (!function_exists('read_file')) {
    /**
     * Read File
     *
     * Opens the file specified in the path and returns it as a string.
     *
     * @param string $file Path to file
     *
     * @return    string    File contents
     * @todo          Remove in version 3.1+.
     * @deprecated    3.0.0    It is now just an alias for PHP's native file_get_contents().
     *
     */
    function read_file(string $file): string
    {
        return @file_get_contents($file);
    }
}

if (!function_exists('write_file')) {
    /**
     * Write File
     *
     * Writes data to the file specified in the path.
     * Creates a new file if non-existent.
     *
     * @param string $path File path
     * @param string $data Data to write
     * @param string $mode fopen() mode (default: 'wb')
     *
     * @return    bool
     */
    function write_file(string $path, string $data, string $mode = 'wb'): bool
    {
        if (!$fp = @fopen($path, $mode)) {
            return false;
        }

        flock($fp, LOCK_EX);

        for ($result = $written = 0, $length = mb_strlen($data); $written < $length; $written += $result) {
            if (($result = fwrite($fp, mb_substr($data, $written))) === false) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return is_int($result);
    }
}

if (!function_exists('delete_files')) {
    /**
     * Delete Files
     *
     * Deletes all files contained in the supplied directory path.
     * Files must be writable or owned by the system in order to be deleted.
     * If the second parameter is set to TRUE, any directories contained
     * within the supplied base directory will be nuked as well.
     *
     * @param string $path File path
     * @param bool $del_dir Whether to delete any directories found in the path
     * @param bool $htdocs Whether to skip deleting .htaccess and index page files
     * @param int $_level Current directory depth level (default: 0; internal use only)
     *
     * @return    bool
     */
    function delete_files(string $path, bool $del_dir = false, bool $htdocs = false, int $_level = 0): bool
    {
        // Trim the trailing slash
        $path = rtrim($path, '/\\');

        if (!$current_dir = @opendir($path)) {
            return false;
        }

        while (false !== ($filename = @readdir($current_dir))) {
            if ($filename !== '.' && $filename !== '..') {
                $filepath = $path . DIRECTORY_SEPARATOR . $filename;

                if (is_dir($filepath) && (isset($filename[0]) && $filename[0] !== '.') && !is_link($filepath)) {
                    delete_files($filepath, $del_dir, $htdocs, $_level + 1);
                } elseif ($htdocs !== true or !preg_match(
                        '/^(\.htaccess|index\.(html|htm|php)|web\.config)$/i',
                        $filename
                    )) {
                    @unlink($filepath);
                }
            }
        }

        closedir($current_dir);

        if (($del_dir === true && $_level > 0)) {
            return @rmdir($path);
        }

        return true;
    }
}

if (!function_exists('get_filenames')) {
    /**
     * Get Filenames
     *
     * Reads the specified directory and builds an array containing the filenames.
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param string $source_dir path to source
     * @param bool $include_path whether to include the path as part of the filename
     * @param bool $_recursion internal variable to determine recursion status - do not use in calls
     *
     * @return    array|bool
     */
    function get_filenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_fileData = array();

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_fileData = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            while (false !== ($file = readdir($fp))) {
                if (is_dir($source_dir . $file) && $file[0] !== '.') {
                    get_filenames($source_dir . $file . DIRECTORY_SEPARATOR, $include_path, true);
                } elseif ($file[0] !== '.') {
                    $_fileData[] = ($include_path === true) ? $source_dir . $file : $file;
                }
            }

            closedir($fp);

            return $_fileData;
        }

        return false;
    }
}

if (!function_exists('get_dir_file_info')) {
    /**
     * Get Directory File Information
     *
     * Reads the specified directory and builds an array containing the filenames,
     * filesize, dates, and permissions
     *
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param string $source_dir path to source
     * @param bool $top_level_only Look only at the top level directory specified?
     * @param bool $_recursion internal variable to determine recursion status - do not use in calls
     *
     * @return    array|bool
     */
    function get_dir_file_info($source_dir, $top_level_only = true, $_recursion = false)
    {
        static $_fileData = array();
        $relative_path = $source_dir;

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_fileData = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }

            // Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
            while (false !== ($file = readdir($fp))) {
                if (is_dir($source_dir . $file) && $file[0] !== '.' && $top_level_only === false) {
                    get_dir_file_info($source_dir . $file . DIRECTORY_SEPARATOR, $top_level_only, true);
                } elseif ($file[0] !== '.') {
                    $_fileData[$file] = get_file_info($source_dir . $file);
                    $_fileData[$file]['relative_path'] = $relative_path;
                }
            }

            closedir($fp);

            return $_fileData;
        }

        return false;
    }
}

if (!function_exists('get_file_info')) {
    /**
     * Get File Info
     *
     * Given a file and path, returns the name, path, size, date modified
     * Second parameter allows you to explicitly declare what information you want returned
     * Options are: name, server_path, size, date, readable, writable, executable, fileperms
     * Returns FALSE if the file cannot be found.
     *
     * @param string $file path to file
     * @param mixed $returned_values array or comma separated string of information returned
     *
     * @return array|false
     */
    function get_file_info(string $file, $returned_values = array('name', 'server_path', 'size', 'date'))
    {
        if (!file_exists($file)) {
            return false;
        }

        if (is_string($returned_values)) {
            $returned_values = explode(',', $returned_values);
        }
        $fileInfo = array();
        foreach ($returned_values as $key) {
            switch ($key) {
                case 'name':
                    $fileInfo['name'] = basename($file);
                    break;
                case 'server_path':
                    $fileInfo['server_path'] = $file;
                    break;
                case 'size':
                    $fileInfo['size'] = filesize($file);
                    break;
                case 'date':
                    $fileInfo['date'] = filemtime($file);
                    break;
                case 'readable':
                    $fileInfo['readable'] = is_readable($file);
                    break;
                case 'writable':
                    $fileInfo['writable'] = is_really_writable($file);
                    break;
                case 'executable':
                    $fileInfo['executable'] = is_executable($file);
                    break;
                case 'fileperms':
                    $fileInfo['fileperms'] = fileperms($file);
                    break;
            }
        }

        return $fileInfo;
    }
}

if (!function_exists('get_mime_by_extension')) {
    /**
     * Get Mime by Extension
     *
     * Translates a file extension into a mime type based on config/mimes.php.
     * Returns FALSE if it can't determine the type, or open the mime config file
     *
     * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
     * It should NOT be trusted, and should certainly NOT be used for security
     *
     * @param string $filename File name
     *
     * @return    bool|string
     */

    function get_mime_by_extension(string $filename)
    {
        return Mimes::getMimeByExtension($filename);
    }
}

if (!function_exists('symbolic_permissions')) {
    /**
     * Symbolic Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * standard symbolic notation representing that value
     *
     * @param int $perms Permissions
     *
     * @return    string
     */
    function symbolic_permissions(int $perms): string
    {
        return (new Filesystem())->symbolicPermissions($perms);
    }
}

if (!function_exists('octal_permissions')) {
    /**
     * Octal Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * a three character string representing the file's octal permissions
     *
     * @param int $perms Permissions
     *
     * @return    string
     */
    function octal_permissions(int $perms): string
    {
        return (new Filesystem())->octalPermissions($perms);
    }
}

if (!function_exists('file_get_directory')) {
    /**
     * Function file_get_directory - Get name of the file's directory.
     *
     * @param $path
     *
     * @return array|string|string[]
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 56:47
     */
    function file_get_directory($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }
}

if (!function_exists('file_get_extension')) {
    /**
     * Function file_get_extension - Get name of the file's directory.
     *
     * @param $path
     *
     * @return array|string|string[]
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 56:39
     */
    function file_get_extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}

if (!function_exists('file_get_name')) {
    /**
     * Function file_get_name - Get name of the file's directory.
     *
     * @param $path
     *
     * @return array|string|string[]
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 56:55
     */
    function file_get_name($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }
}

if (!function_exists('file_read')) {
    /**
     * Read contents of a file.
     *
     * @param $path
     *
     * @return string|null
     */
    function file_read($path)
    {
        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return null;
    }
}

if (!function_exists('file_create')) {
    /**
     * Create a file and all necessary subdirectories.
     *
     * @param $path
     *
     * @return bool
     */
    function file_create($path): bool
    {
        if (!file_exists($path)) {
            $dir = file_get_directory($path);

            if (!is_dir($dir)) {
                directory_create($dir);
            }

            return file_put_contents($path, '') !== false;
        }

        return true;
    }
}

if (!function_exists('file_write')) {
    /**
     * Write to a file.
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    function file_write($path, $content): bool
    {
        file_create($path);

        return file_put_contents($path, $content) !== false;
    }
}

if (!function_exists('file_append')) {
    /**
     * Append contents to the end of file.
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    function file_append($path, $content): bool
    {
        if (file_exists($path)) {
            return file_write($path, file_read($path) . $content);
        }

        return file_write($path, $content);
    }
}

if (!function_exists('file_prepend')) {
    /**
     * Prepend contents to the beginning of file.
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    function file_prepend($path, $content): bool
    {
        if (file_exists($path)) {
            return file_write($path, $content . file_read($path));
        }

        return file_write($path, $content);
    }
}

if (!function_exists('file_delete')) {
    /**
     * Delete a file.
     *
     * @param $path
     *
     * @return bool
     */
    function file_delete($path): bool
    {
        if (file_exists($path)) {
            return unlink($path);
        }

        return true;
    }
}

if (!function_exists('file_move')) {
    /**
     * Move a file from one location to another and
     * create all necessary subdirectories.
     *
     * @param $oldPath
     * @param $newPath
     *
     * @return bool
     */
    function file_move($oldPath, $newPath): bool
    {
        $dir = file_get_directory($newPath);

        if (!directory_exists($dir)) {
            directory_create($dir);
        }

        return rename($oldPath, $newPath);
    }
}

if (!function_exists('file_copy')) {
    /**
     * Copy a file from one location to another
     * and create all necessary subdirectories.
     *
     * @param $oldPath
     * @param $newPath
     *
     * @return bool
     */
    function file_copy($oldPath, $newPath): bool
    {
        $dir = file_get_directory($newPath);

        if (!directory_exists($dir)) {
            directory_create($dir);
        }

        return copy($oldPath, $newPath);
    }
}

if (!function_exists('file_rename')) {
    /**
     * Rename file at the given path.
     *
     * @param $path
     * @param $newName
     *
     * @return bool
     */
    function file_rename($path, $newName): bool
    {
        return (new Filesystem())->fileRename($path, $newName);
    }
}

if (!function_exists('format_size_units')) {
    /**
     * Function format_size_units
     *
     * @param int $bytes
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/18/2021 34:56
     */
    function format_size_units(int $bytes = 0): string
    {
        return (new Filesystem())->formatSizeUnits($bytes);
    }
}

if (!function_exists('create_new_folder')) {
    /**
     * Function create_new_folder
     *
     * @param string $pathname
     * @param int $mode
     *
     * @return bool
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/18/2021 35:49
     */
    function create_new_folder(string $pathname = '', int $mode = 0755): bool
    {
        return (new Filesystem())->createNewFolder($pathname, $mode);
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitize Filename
     *
     * @param string $str Input file name
     * @param bool $relative_path Whether to preserve paths
     *
     * @return    string
     */
    function sanitize_filename(string $str, bool $relative_path = false): string
    {
        $bad = array(
            '../',
            '<!--',
            '-->',
            '<',
            '>',
            "'",
            '"',
            '&',
            '$',
            '#',
            '{',
            '}',
            '[',
            ']',
            '=',
            ';',
            '?',
            '%20',
            '%22',
            '%3c',        // <
            '%253c',    // <
            '%3e',        // >
            '%0e',        // >
            '%28',        // (
            '%29',        // )
            '%2528',    // (
            '%26',        // &
            '%24',        // $
            '%3f',        // ?
            '%3b',        // ;
            '%3d'        // =
        );

        if (!$relative_path) {
            $bad[] = './';
            $bad[] = '/';
        }

        $str = remove_invisible_characters($str, false);

        do {
            $old = $str;
            $str = str_replace($bad, '', $str);
        } while ($old !== $str);

        return stripslashes($str);
    }
}
