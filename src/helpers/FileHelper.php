<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidArgumentException;

/**
 * Class FileHelper
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * @var string the path (or alias) of a PHP file containing MIME type information.
     */
    public static $mimeMagicFile = '@yuncms/helpers/mimeTypes.php';

    /**
     * 读取并删除文件
     * @param string $path
     * @return bool|string
     * @throws ErrorException
     */
    public static function readAndDelete($path)
    {
        $path = self::normalizePath($path);
        $contents = file_get_contents($path);
        if ($contents === false) {
            return false;
        }
        self::removeFile($path);
        return $contents;
    }

    /**
     * Checks if given fileName has a extension
     *
     * @param string $fileName the filename
     * @return boolean has extension
     */
    public static function hasExtension(string $fileName): bool
    {
        return (strpos($fileName, ".") !== false);
    }

    /**
     * Write the contents of a file.
     *
     * @param  string $path
     * @param  string $contents
     * @param  bool $lock
     * @return int
     */
    public static function put(string $path, string $contents, bool $lock = false): int
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Append to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function append(string $path, string $data): int
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Delete a file.
     *
     * @param string $file
     * @throws ErrorException
     */
    public static function removeFile(string $file)
    {
        // Copied from [[removeDirectory()]]
        try {
            unlink($file);
        } catch (ErrorException $e) {
            if (DIRECTORY_SEPARATOR === '\\') {
                // last resort measure for Windows
                $lines = [];
                exec("DEL /F/Q \"$file\"", $lines, $deleteError);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param string $path
     * @return string original file base name
     */
    public static function baseName(string $path): string
    {
        // https://github.com/yiisoft/yii2/issues/11012
        return mb_substr(pathinfo('_' . $path, PATHINFO_FILENAME), 1, null, '8bit');
    }

    /**
     * @param string $path
     * @return string file extension
     */
    public static function extension(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * 使用RecursiveDirectoryIterator遍历文件，列出所有文件路径
     * @param \RecursiveDirectoryIterator|string $directory 指定了目录的RecursiveDirectoryIterator实例
     * @return array $files 文件列表
     */
    public static function files(\RecursiveDirectoryIterator $directory): array
    {
        if (!$directory instanceof \RecursiveDirectoryIterator) {
            $directory = new \RecursiveDirectoryIterator($directory);
        }
        $files = [];
        for (; $directory->valid(); $directory->next()) {
            if ($directory->isDir() && !$directory->isDot()) {
                if ($directory->haschildren()) {
                    $files = array_merge($files, static::files($directory->getChildren()));
                };
            } else if ($directory->isFile()) {
                $files[] = $directory->getPathName();
            }
        }
        return $files;
    }

    /**
     * Empty the specified directory of all files and folders.
     *
     * @param  string $directory
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public static function cleanDirectory(string $directory): bool
    {
        self::removeDirectory($directory);
        return self::createDirectory($directory);
    }

    /**
     * Sanitizes a filename.
     *
     * @param string $filename the filename to sanitize
     * @param array $options options for sanitization. Valid options are:
     *
     * - `asciiOnly`: bool, whether only ASCII characters should be allowed. Defaults to false.
     * - `separator`: string|null, the separator character to use in place of whitespace. defaults to '-'. If set to null, whitespace will be preserved.
     *
     * @return string The cleansed filename
     */
    public static function sanitizeFilename(string $filename, array $options = []): string
    {
        $asciiOnly = $options['asciiOnly'] ?? false;
        $separator = array_key_exists('separator', $options) ? $options['separator'] : '-';
        $disallowedChars = ['â€”', 'â€“', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8211;', '&#8212;',
            '+', '%', '^', '~', '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', '\'',
            '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}'
        ];

        // Replace any control characters in the name with a space.
        $filename = preg_replace("/\\x{00a0}/iu", ' ', $filename);

        // Strip any characters not allowed.
        $filename = str_replace($disallowedChars, '', strip_tags($filename));

        if ($separator !== null) {
            $filename = preg_replace('/(\s|' . preg_quote($separator, '/') . ')+/u', $separator, $filename);
        }

        // Nuke any trailing or leading .-_
        $filename = trim($filename, '.-_');

        $filename = $asciiOnly ? StringHelper::toAscii($filename) : $filename;

        return $filename;
    }

    /**
     * Returns whether the file path is an absolute path.
     *
     * @param string $path A file path
     * @return bool
     */
    public static function isAbsolutePath($path): bool
    {
        return strspn($path, '/\\', 0, 1)
            || (strlen($path) > 3 && ctype_alpha($path[0])
                && ':' === $path[1]
                && strspn($path, '/\\', 2, 1)
            )
            || null !== parse_url($path, PHP_URL_SCHEME);
    }

    /**
     * Returns whether a given directory is empty (has no files) recursively.
     *
     * @param string $dir the directory to be checked
     * @return bool whether the directory is empty
     * @throws InvalidArgumentException if the dir is invalid
     * @throws ErrorException in case of failure
     */
    public static function isDirectoryEmpty(string $dir): bool
    {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException("The dir argument must be a directory: $dir");
        }

        if (!($handle = opendir($dir))) {
            throw new ErrorException("Unable to open the directory: $dir");
        }

        // It's empty until we find a file
        $empty = true;

        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($path) || !static::isDirectoryEmpty($path)) {
                $empty = false;
                break;
            }
        }

        closedir($handle);

        return $empty;
    }

    /**
     * Tests whether a file/directory is writable.
     *
     * @param string $path the file/directory path to test
     *
     * @return bool whether the path is writable
     * @throws ErrorException in case of failure
     */
    public static function isWritable(string $path): bool
    {
        // If it's a directory, test on a temp sub file
        if (is_dir($path)) {
            return static::isWritable($path . DIRECTORY_SEPARATOR . uniqid('test_writable', true) . '.tmp');
        }

        // Remember whether the file already existed
        $exists = file_exists($path);

        if (($f = @fopen($path, 'ab')) === false) {
            return false;
        }

        @fclose($f);

        // Delete the file if it didn't exist already
        if (!$exists) {
            static::removeFile($path);
        }

        return true;
    }
}