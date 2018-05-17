<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use RuntimeException;
use yii\base\InvalidArgumentException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\FileNotFoundException;
use yuncms\base\FileNotFoundException as ContractFileNotFoundException;
use yuncms\web\UploadedFile;

/**
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 *
 * @property string $visibility
 * @property string|false $rootUrl
 */
class FilesystemAdapter implements Filesystem, Cloud
{
    /**
     * The Flysystem filesystem implementation.
     *
     * @var \League\Flysystem\Filesystem
     */
    protected $driver;

    /**
     * FilesystemAdapter constructor.
     * @param FilesystemInterface $driver
     */
    public function __construct(FilesystemInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * 判断文件是否存在
     *
     * @param  string $path
     * @return bool
     */
    public function exists($path)
    {
        return $this->driver->has($path);
    }

    /**
     * 获取文件的完整路径
     *
     * @param  string $path
     * @return string
     */
    public function path($path)
    {
        return $this->driver->getAdapter()->getPathPrefix() . $path;
    }

    /**
     * 获取文件的内容。
     *
     * @param  string $path
     * @return string
     *
     * @throws ContractFileNotFoundException
     */
    public function get($path)
    {
        try {
            return $this->driver->read($path);
        } catch (FileNotFoundException $e) {
            throw new ContractFileNotFoundException($path, $e->getCode(), $e);
        }
    }

    /**
     * Write the contents of a file.
     *
     * @param  string $path
     * @param  string|resource $contents
     * @param  mixed $options
     * @return bool
     */
    public function put($path, $contents, $options = [])
    {
        $options = is_string($options) ? ['visibility' => $options] : (array)$options;

        if ($contents instanceof UploadedFile) {
            return $this->putFile($path, $contents, $options);
        }

        return is_resource($contents) ? $this->driver->putStream($path, $contents, $options) : $this->driver->put($path, $contents, $options);
    }

    /**
     * Store the uploaded file on the disk.
     *
     * @param  string $path
     * @param  \yuncms\web\UploadedFile $file
     * @param  array $options
     * @return string|false
     */
    public function putFile($path, $file, $options = [])
    {
        return $this->putFileAs($path, $file, $file->getRename(), $options);
    }

    /**
     * Store the uploaded file on the disk with a given name.
     *
     * @param  string $path
     * @param  \yuncms\web\UploadedFile $file
     * @param  string $name
     * @param  array $options
     * @return string|false
     */
    public function putFileAs($path, $file, $name, $options = [])
    {
        $stream = fopen($file->tempName, 'r+');

        // Next, we will format the path of the file and store the file using a stream since
        // they provide better performance than alternatives. Once we write the file this
        // stream will get closed automatically by us so the developer doesn't have to.
        $result = $this->put(
            $path = trim($path . '/' . $name, '/'), $stream, $options
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $result ? $path : false;
    }

    /**
     * 获取文件的可见性属性
     *
     * @param  string $path
     * @return string
     * @throws FileNotFoundException
     */
    public function getVisibility($path)
    {
        if ($this->driver->getVisibility($path) == AdapterInterface::VISIBILITY_PUBLIC) {
            return Filesystem::VISIBILITY_PUBLIC;
        }
        return Filesystem::VISIBILITY_PRIVATE;
    }

    /**
     * 设置文件可见性
     *
     * @param  string $path
     * @param  string $visibility
     * @return bool
     * @throws FileNotFoundException
     */
    public function setVisibility($path, $visibility)
    {
        return $this->driver->setVisibility($path, $this->parseVisibility($visibility));
    }

    /**
     * Prepend to a file.
     *
     * @param  string $path
     * @param  string $data
     * @param  string $separator
     * @return int
     * @throws ContractFileNotFoundException
     */
    public function prepend($path, $data, $separator = PHP_EOL)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data . $separator . $this->get($path));
        }
        return $this->put($path, $data);
    }

    /**
     * Append to a file.
     *
     * @param  string $path
     * @param  string $data
     * @param  string $separator
     * @return int
     * @throws ContractFileNotFoundException
     */
    public function append($path, $data, $separator = PHP_EOL)
    {
        if ($this->exists($path)) {
            return $this->put($path, $this->get($path) . $separator . $data);
        }

        return $this->put($path, $data);
    }

    /**
     * 删除文件
     *
     * @param  string|array $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;
        foreach ($paths as $path) {
            try {
                if (!$this->driver->delete($path)) {
                    $success = false;
                }
            } catch (FileNotFoundException $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * 复制文件
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileExistsException
     */
    public function copy($from, $to)
    {
        return $this->driver->copy($from, $to);
    }

    /**
     * 移动文件
     *
     * @param  string $from
     * @param  string $to
     * @return bool
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileExistsException
     */
    public function move($from, $to)
    {
        return $this->driver->rename($from, $to);
    }

    /**
     * 获取文件大小
     *
     * @param  string $path
     * @return int
     * @throws FileNotFoundException
     */
    public function size($path)
    {
        return $this->driver->getSize($path);
    }

    /**
     * 获取文件的  mime-type
     *
     * @param  string $path
     * @return string|false
     * @throws FileNotFoundException
     */
    public function mimeType($path)
    {
        return $this->driver->getMimetype($path);
    }

    /**
     * 获取文件最后修改时间
     *
     * @param  string $path
     * @return int
     * @throws FileNotFoundException
     */
    public function lastModified($path)
    {
        return $this->driver->getTimestamp($path);
    }

    /**
     * 获取文件访问Url
     *
     * @param  string $path
     * @return string
     */
    public function url($path)
    {
        $adapter = $this->driver->getAdapter();

        if ($adapter instanceof CachedAdapter) {
            $adapter = $adapter->getAdapter();
        }

        if (method_exists($adapter, 'getUrl')) {
            return $adapter->getUrl($path);
        } else {
            throw new RuntimeException('This driver does not support retrieving URLs.');
        }
    }

    /**
     * 获取文件的临时访问Url
     *
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function temporaryUrl($path, $expiration, array $options = [])
    {
        $adapter = $this->driver->getAdapter();

        if ($adapter instanceof CachedAdapter) {
            $adapter = $adapter->getAdapter();
        }

        if (method_exists($adapter, 'getTemporaryUrl')) {
            return $adapter->getTemporaryUrl($path, $expiration, $options);
        } else {
            throw new RuntimeException('This driver does not support creating temporary URLs.');
        }
    }

    /**
     * Concatenate a path to a URL.
     *
     * @param  string $url
     * @param  string $path
     * @return string
     */
    protected function concatPathToUrl($url, $path)
    {
        return rtrim($url, '/') . '/' . ltrim($path, '/');
    }

    /**
     * 创建文件夹
     *
     * @param  string $path
     * @return bool
     */
    public function makeDirectory($path)
    {
        return $this->driver->createDir($path);
    }

    /**
     * 删除文件夹
     *
     * @param  string $directory
     * @return bool
     */
    public function deleteDirectory($directory)
    {
        return $this->driver->deleteDir($directory);
    }

    /**
     * 刷新文件系统缓存
     *
     * @return void
     */
    public function flushCache()
    {
        $adapter = $this->driver->getAdapter();
        if ($adapter instanceof CachedAdapter) {
            $adapter->getCache()->flush();
        }
    }

    /**
     * 获取文件系统原始驱动
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * 解析可见性值
     *
     * @param  string|null $visibility
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function parseVisibility($visibility)
    {
        if (is_null($visibility)) {
            return;
        }
        switch ($visibility) {
            case Filesystem::VISIBILITY_PUBLIC:
                return AdapterInterface::VISIBILITY_PUBLIC;
            case Filesystem::VISIBILITY_PRIVATE:
                return AdapterInterface::VISIBILITY_PRIVATE;
        }

        throw new InvalidArgumentException('Unknown visibility: ' . $visibility);
    }

    /**
     * Pass dynamic methods call onto Flysystem.
     *
     * @param  string $method
     * @param  array $params
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->driver, $method], $params);
    }
}
