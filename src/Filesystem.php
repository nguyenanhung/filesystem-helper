<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:20
 */

namespace nguyenanhung\Libraries\Filesystem;

use DateTime;
use Exception;
use Iterator;
use nguyenanhung\Libraries\Filesystem\HeroDoc\DefaultHeroDocTemplates;
use SplFileInfo;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use TheSeer\DirectoryScanner\DirectoryScanner;

if (!class_exists('nguyenanhung\Libraries\Filesystem\Filesystem')) {
    /**
     * Class Filesystem
     *
     * @package   nguyenanhung\Libraries\Filesystem
     * @author    713uk13m <dev@nguyenanhung.com>
     * @copyright 713uk13m <dev@nguyenanhung.com>
     */
    class Filesystem extends SymfonyFilesystem
    {
        /** @var null|array Mảng dữ liệu chứa các thuộc tính cần quét */
        private $scanInclude = ['*.log', '*.txt'];

        /** @var null|array Mảng dữ liệu chứa các thuộc tính bỏ qua không quét */
        private $scanExclude = ['*/Zip-Archive/*.zip'];

        /**
         * Function setInclude
         *
         * @param array $include
         *
         * @return $this
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 09/24/2021 40:32
         */
        public function setInclude(array $include = array()): Filesystem
        {
            $this->scanInclude = $include;

            return $this;
        }

        /**
         * Function setExclude
         *
         * @param array $exclude
         *
         * @return $this
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 09/24/2021 40:34
         */
        public function setExclude(array $exclude = array()): Filesystem
        {
            $this->scanExclude = $exclude;

            return $this;
        }

        /**
         * Hàm quét thư mục và list ra danh sách các file con
         *
         * @param string $path Đường dẫn thư mục cần quét, VD: /your/to/path
         * @param array|null $includes Mảng dữ liệu chứa các thuộc tính cần quét
         * @param array|null $excludes Mảng dữ liệu chứa các thuộc tính bỏ qua không quét
         *
         * @return \Iterator
         * @see   https://github.com/theseer/DirectoryScanner/blob/master/samples/sample.php
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:19
         *
         */
        public function directoryScanner(string $path = '', array $includes = null, array $excludes = null): Iterator
        {
            $scanner = new DirectoryScanner();
            if (is_array($includes) && !empty($includes)) {
                foreach ($includes as $include) {
                    $scanner->addInclude($include);
                }
            }
            if (is_array($excludes) && !empty($excludes)) {
                foreach ($excludes as $exclude) {
                    $scanner->addExclude($exclude);
                }
            }

            return $scanner($path);
        }

        /**
         * Hàm xóa các file Log được chỉ định
         *
         * @param string $path Thư mục cần quét và xóa
         * @param int $dayToDel Số ngày cần giữ lại file
         *
         * @return array Mảng thông tin về các file đã xóa
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:21
         *
         */
        public function cleanLog(string $path = '', int $dayToDel = 3)
        {
            try {
                $getDir = $this->directoryScanner($path, $this->scanInclude, $this->scanExclude);
                $result = array();
                $result['scanPath'] = $path;
                foreach ($getDir as $fileName) {
                    $SplFileInfo = new SplFileInfo($fileName);
                    $filename = $SplFileInfo->getPathname();
                    $format = 'YmdHis';
                    // Lấy thời gian xác định xóa fileName
                    $dateTime = new DateTime("-" . $dayToDel . " days");
                    $deleteTime = $dateTime->format($format);
                    // Lấy modifyTime của file
                    $getFileTime = filemtime($filename);
                    $fileTime = date($format, $getFileTime);
                    if ($fileTime < $deleteTime) {
                        $this->chmod($filename, 0777);
                        $this->remove($filename);
                        $result['listFile'][] .= "Delete file: " . $filename;
                    }
                }

                return $result;
            } catch (Exception $e) {
                if (function_exists('log_message')) {
                    // Save Log if use CodeIgniter Framework
                    log_message('error', 'Error Message: ' . $e->getMessage());
                    log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
                }

                return null;
            }
        }

        /**
         * Hàm xóa các file Log được chỉ định
         *
         * @param string $path Thư mục cần quét và xóa
         * @param int $dayToDel Số ngày cần giữ lại file
         *
         * @return array Mảng thông tin về các file đã xóa
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:21
         *
         */
        public function removeLog(string $path = '', int $dayToDel = 3)
        {
            try {
                return $this->cleanLog($path, $dayToDel);
            } catch (Exception $e) {
                if (function_exists('log_message')) {
                    log_message('error', $e->getMessage());
                    log_message('error', $e->getTraceAsString());
                }

                return null;
            }
        }

        /**
         * Hàm quét và xoá các file Log từ 1 mảng chỉ định
         *
         * @param array $listFolder Mảng chứa dữ liệu các folder cần quét
         * @param int $dayToDelete Số ngày cần lưu giữ
         *
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 07/30/2020 03:15
         */
        public function scanAndCleanLog(array $listFolder = array(), int $dayToDelete = 3)
        {
            if (empty($listFolder)) {
                Console::writeLn("Không có mảng dữ liệu cần quét");
                exit();
            }
            foreach ($listFolder as $folder) {
                Console::writeLn("=========|| DELETE FOLDER LOG: " . $folder . " ||=========");
                Console::writeLn($this->cleanLog($folder, $dayToDelete));
            }
        }

        /**
         * Function formatSizeUnits
         *
         * @param int $bytes
         *
         * @return string
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 09/20/2021 59:38
         */
        public function formatSizeUnits(int $bytes = 0): string
        {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes .= ' bytes';
            } elseif ($bytes === 1) {
                $bytes .= ' byte';
            } else {
                $bytes .= ' byte';
            }

            return $bytes;
        }

        /**
         * Function createNewFolder - Create new folder and put 3 files: index.html, .htaccess and README.md
         *
         * @param string $pathname
         * @param int $mode
         *
         * @return bool
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 32:15
         */
        public function createNewFolder(string $pathname = '', int $mode = 0755): bool
        {
            if (empty($pathname)) {
                return false;
            }
            if (is_dir($pathname)) {
                return true;
            }
            if (!is_dir($pathname)) {
                try {
                    $this->mkdir($pathname, $mode);
                    // Gen file Index.html + .htaccess
                    $fileIndex = trim($pathname . DIRECTORY_SEPARATOR . 'index.html');
                    $fileReadme = trim($pathname . DIRECTORY_SEPARATOR . 'README.md');
                    $fileHtaccess = trim($pathname . DIRECTORY_SEPARATOR . '.htaccess');
                    $fileContentReadme = "# " . basename($pathname) . " README";

                    $this->appendToFile($fileIndex, DefaultHeroDocTemplates::default_403_simple_html());
                    $this->appendToFile($fileHtaccess, DefaultHeroDocTemplates::htaccess_deny_all());
                    $this->appendToFile($fileReadme, $fileContentReadme);

                    return true;
                } catch (Exception $e) {
                    if (function_exists('log_message')) {
                        // Save Log if use CodeIgniter Framework
                        log_message('error', 'Error Message: ' . $e->getMessage());
                        log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
                    }
                    return false;
                }
            }

            return false;
        }

        /**
         * Tests for file is Really Writable
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
        public function isReallyWritable($file): bool
        {
            // If we're on a Unix server with safe_mode off we call is_writable
            if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') or !ini_get('safe_mode'))) {
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

            if (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
                return false;
            }

            fclose($fp);

            return true;
        }

        public function readFile(string $filename): string
        {
            if (method_exists(SymfonyFilesystem::class, 'readFile')) {
                return SymfonyFilesystem::readfile($filename);
            }

            if (is_dir($filename)) {
                throw new IOException(\sprintf('Failed to read file "%s": File is a directory.', $filename));
            }

            return file_get_contents($filename);
        }


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
        public
        function writeFile(
            string $path,
            string $data,
            string $mode = 'wb'
        ): bool {
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
        public
        function deleteFiles(
            string $path,
            bool $del_dir = false,
            bool $htdocs = false,
            int $_level = 0
        ): bool {
            // Trim the trailing slash
            $path = rtrim($path, '/\\');

            if (!$current_dir = @opendir($path)) {
                return false;
            }

            while (false !== ($filename = @readdir($current_dir))) {
                if ($filename !== '.' && $filename !== '..') {
                    $filepath = $path . DIRECTORY_SEPARATOR . $filename;

                    if (is_dir($filepath) && $filename[0] !== '.' && !is_link($filepath)) {
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
        public
        function getFilenames(
            $source_dir,
            $include_path = false,
            $_recursion = false
        ) {
            static $_fileData = array();

            if ($fp = @opendir($source_dir)) {
                // reset the array and make sure $source_dir has a trailing slash on the initial call
                if ($_recursion === false) {
                    $_fileData = array();
                    $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }

                while (false !== ($file = readdir($fp))) {
                    if (is_dir($source_dir . $file) && $file[0] !== '.') {
                        self::getFilenames($source_dir . $file . DIRECTORY_SEPARATOR, $include_path, true);
                    } elseif ($file[0] !== '.') {
                        $_fileData[] = ($include_path === true) ? $source_dir . $file : $file;
                    }
                }

                closedir($fp);

                return $_fileData;
            }

            return false;
        }

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
        public
        function getDirectoryFileInformation(
            $source_dir,
            $top_level_only = true,
            $_recursion = false
        ) {
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
                        self::getDirectoryFileInformation(
                            $source_dir . $file . DIRECTORY_SEPARATOR,
                            $top_level_only,
                            true
                        );
                    } elseif ($file[0] !== '.') {
                        $_fileData[$file] = $this->getFileInfo($source_dir . $file);
                        $_fileData[$file]['relative_path'] = $relative_path;
                    }
                }

                closedir($fp);

                return $_fileData;
            }

            return false;
        }

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
        public
        function getFileInfo(
            string $file,
            $returned_values = array('name', 'server_path', 'size', 'date')
        ) {
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
                        $fileInfo['writable'] = $this->isReallyWritable($file);
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
        public
        function getMimeByExtension(
            string $filename
        ) {
            return Mimes::getMimeByExtension($filename);
        }

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
        public
        function symbolicPermissions(
            int $perms
        ): string {
            if (($perms & 0xC000) === 0xC000) {
                $symbolic = 's'; // Socket
            } elseif (($perms & 0xA000) === 0xA000) {
                $symbolic = 'l'; // Symbolic Link
            } elseif (($perms & 0x8000) === 0x8000) {
                $symbolic = '-'; // Regular
            } elseif (($perms & 0x6000) === 0x6000) {
                $symbolic = 'b'; // Block special
            } elseif (($perms & 0x4000) === 0x4000) {
                $symbolic = 'd'; // Directory
            } elseif (($perms & 0x2000) === 0x2000) {
                $symbolic = 'c'; // Character special
            } elseif (($perms & 0x1000) === 0x1000) {
                $symbolic = 'p'; // FIFO pipe
            } else {
                $symbolic = 'u'; // Unknown
            }

            // Owner
            $symbolic .= (($perms & 0x0100) ? 'r' : '-') . (($perms & 0x0080) ? 'w' : '-') . (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

            // Group
            $symbolic .= (($perms & 0x0020) ? 'r' : '-') . (($perms & 0x0010) ? 'w' : '-') . (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

            // World
            $symbolic .= (($perms & 0x0004) ? 'r' : '-') . (($perms & 0x0002) ? 'w' : '-') . (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

            return $symbolic;
        }

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
        public
        function octalPermissions(
            int $perms
        ): string {
            return mb_substr(sprintf('%o', $perms), -3);
        }

        /**
         * Function fileGetDirectory - Get name of the file's directory.
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 56:42
         */
        public
        function fileGetDirectory(
            $path
        ) {
            return pathinfo($path, PATHINFO_DIRNAME);
        }

        /**
         * Function fileGetExtension
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 57:39
         */
        public
        function fileGetExtension(
            $path
        ) {
            return pathinfo($path, PATHINFO_EXTENSION);
        }

        /**
         * Function fileGetBasename
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 59:10
         */
        public
        function fileGetBasename(
            $path
        ) {
            return pathinfo($path, PATHINFO_BASENAME);
        }

        /**
         * Function fileRead - alias of readFile method
         *
         * @param $file
         *
         * @return false|string|null
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 00:14
         */
        public
        function fileRead(
            $file
        ) {
            return $this->readFile($file);
        }

        /**
         * Create a file and all necessary subdirectories.
         *
         * @param $path
         *
         * @return bool
         */
        public
        function fileCreate(
            $path
        ): bool {
            if (!file_exists($path)) {
                $dir = $this->fileGetDirectory($path);

                if (!is_dir($dir)) {
                    Directory::directoryCreate($dir);
                }

                return file_put_contents($path, '') !== false;
            }

            return true;
        }

        /**
         * Write to a file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public
        function fileWrite(
            $path,
            $content
        ): bool {
            $this->fileCreate($path);

            return file_put_contents($path, $content) !== false;
        }

        /**
         * Append contents to the end of file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public
        function fileAppend(
            $path,
            $content
        ): bool {
            if (file_exists($path)) {
                return $this->fileWrite($path, $this->fileRead($path) . $content);
            }

            return $this->fileWrite($path, $content);
        }

        /**
         * Prepend contents to the beginning of file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public
        function filePrepend(
            $path,
            $content
        ): bool {
            if (file_exists($path)) {
                return $this->fileWrite($path, $content . $this->fileRead($path));
            }

            return $this->fileWrite($path, $content);
        }

        /**
         * Delete a file.
         *
         * @param $path
         *
         * @return bool
         */
        public
        function fileDelete(
            $path
        ): bool {
            if (file_exists($path)) {
                return unlink($path);
            }

            return true;
        }

        /**
         * Move a file from one location to another and
         * create all necessary subdirectories.
         *
         * @param $oldPath
         * @param $newPath
         *
         * @return bool
         */
        public
        function file_move(
            $oldPath,
            $newPath
        ): bool {
            return $this->fileMove($oldPath, $newPath);
        }

        /**
         * Move a file from one location to another and
         * create all necessary subdirectories.
         *
         * @param $oldPath
         * @param $newPath
         *
         * @return bool
         */
        public
        function fileMove(
            $oldPath,
            $newPath
        ): bool {
            $dir = $this->fileGetDirectory($newPath);

            if (!directory_exists($dir)) {
                Directory::directoryCreate($dir);
            }

            return rename($oldPath, $newPath);
        }

        /**
         * Copy a file from one location to another
         * and create all necessary subdirectories.
         *
         * @param $oldPath
         * @param $newPath
         *
         * @return bool
         */
        public
        function fileCopy(
            $oldPath,
            $newPath
        ): bool {
            $dir = $this->fileGetDirectory($newPath);

            if (!is_dir($dir)) {
                Directory::directoryCreate($dir);
            }

            return copy($oldPath, $newPath);
        }

        /**
         * Function fileRename
         *
         * @param $path
         * @param $newName
         *
         * @return bool
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 09/24/2021 13:56
         */
        public
        function fileRename(
            $path,
            $newName
        ): bool {
            try {
                $this->rename($path, $newName);

                return true;
            } catch (IOException $e) {
                if (function_exists('log_message')) {
                    // Save Log if use CodeIgniter Framework
                    log_message('error', 'Error Message: ' . $e->getMessage());
                    log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
                }

                return false;
            }
        }

        /**
         * Sanitize Filename
         *
         * @param string $str Input file name
         * @param bool $relative_path Whether to preserve paths
         *
         * @return    string
         */
        public
        function sanitizeFilename(
            string $str,
            bool $relative_path = false
        ): string {
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
}
