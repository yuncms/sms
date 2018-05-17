<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;
use yuncms\helpers\FileHelper;

/**
 * Class File
 * @package yuncms\base
 */
class File extends \SplFileInfo
{
    /**
     * Constructs a new file from the given path.
     *
     * @param string $path The path to the file
     * @param bool $checkPath Whether to check the path or not
     *
     * @throws FileNotFoundException If the given path is not a file
     */
    public function __construct($path, $checkPath = true)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }
        parent::__construct($path);
    }

    /**
     * Returns the mime type of the file.
     *
     * The mime type is guessed using a MimeTypeGuesser instance, which uses finfo(),
     * mime_content_type() and the system binary "file" (in this order), depending on
     * which of those are available.
     *
     * @return string|null The guessed mime type (e.g. "application/pdf")
     *
     * @throws \yii\base\InvalidConfigException
     * @see FileHelper
     */
    public function getMimeType()
    {
        return FileHelper::getMimeType($this->getPathname());
    }

    /**
     * Moves the file to a new location.
     * @param string $directory The destination folder
     * @param string $name The new file name
     * @return self A File object representing the new file
     * @throws FileException if the target file could not be created
     * @throws FileNotFoundException
     */
    public function move($directory, $name = null)
    {
        $target = $this->getTargetFile($directory, $name);
        if (!@rename($this->getPathname(), $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
        }
        @chmod($target, 0666 & ~umask());

        return $target;
    }

    /**
     * 获取目标文件实例
     * @param string $directory
     * @param string $name
     * @return File
     * @throws FileException
     * @throws FileNotFoundException
     */
    protected function getTargetFile($directory, $name = null)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }
        $target = rtrim($directory, '/\\').DIRECTORY_SEPARATOR.(null === $name ? $this->getBasename() : $this->getName($name));
        return new self($target, false);
    }

    /**
     * Returns locale independent base name of the given path.
     *
     * @param string $name The new file name
     * @return string containing
     */
    protected function getName($name)
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);
        return $originalName;
    }
}