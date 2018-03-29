<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\services;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yuncms\helpers\FileHelper;

/**
 * Class Path
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Path extends Component
{
    /**
     * @var string
     */
    private $_runtimePath;

    /**
     * @var string
     */
    private $_vendorPath;

    /**
     * Returns the path to the `vendor/` directory.
     *
     * @return string
     * @throws Exception
     */
    public function getVendorPath(): string
    {
        if ($this->_vendorPath !== null) {
            return $this->_vendorPath;
        }

        $vendorPath = Yii::getAlias('@vendor');

        if ($vendorPath === false) {
            throw new Exception('There was a problem getting the vendor path.');
        }

        return $this->_vendorPath = FileHelper::normalizePath($vendorPath);
    }

    /**
     * Returns the path to the `@runtime/` directory.
     *
     * @return string
     * @throws Exception
     */
    public function getRuntimePath(): string
    {
        if ($this->_runtimePath !== null) {
            return $this->_runtimePath;
        }
        $runtimePath = Yii::getAlias('@runtime');

        if ($runtimePath === false) {
            throw new Exception('There was a problem getting the vendor path.');
        }
        return $this->_runtimePath = FileHelper::normalizePath($runtimePath);
    }

    /**
     * Returns the path to the `@runtime/temp/` directory.
     *
     * @return string
     * @throws Exception
     */
    public function getTempPath(): string
    {
        $path = $this->getRuntimePath() . DIRECTORY_SEPARATOR . 'temp';
        FileHelper::createDirectory($path);
        return $path;
    }

    /**
     * Returns the path to the `@runtime/logs/` directory.
     *
     * @return string
     * @throws Exception
     */
    public function getLogPath(): string
    {
        $path = $this->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs';
        FileHelper::createDirectory($path);
        return $path;
    }

    /**
     * Returns the path to the `@runtime/sessions/` directory.
     *
     * @return string
     * @throws Exception
     */
    public function getSessionPath(): string
    {
        $path = $this->getRuntimePath() . DIRECTORY_SEPARATOR . 'sessions';
        FileHelper::createDirectory($path);
        return $path;
    }

    /**
     * Returns the path to the file cache directory.
     *
     * This will be located at `@runtime/cache/` by default, but that can be overridden with the 'cachePath'.
     *
     * @return string
     * @throws Exception
     */
    public function getCachePath(): string
    {
        $path = $this->getRuntimePath() . DIRECTORY_SEPARATOR . 'cache';
        FileHelper::createDirectory($path);
        return $path;
    }
}