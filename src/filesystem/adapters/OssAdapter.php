<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use OSS\OssClient;
use League\Flysystem\Config;
use League\Flysystem\Adapter\AbstractAdapter;
use RuntimeException;
use yuncms\filesystem\Filesystem;

/**
 * Class OssAdapter
 */
class OssAdapter extends AbstractAdapter
{

    /**
     * @var OssClient
     */
    private $ossClient;

    /**
     * @var string AliYun bucket
     */
    private $bucket;

    /**
     * @var string
     */
    private $endpoint = 'oss-cn-hangzhou.aliyuncs.com';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected static $metaOptions = [
        'Content-Length',
        'Content-Md5',
        'Content-Type',
        'Content-Disposition',
        'Cache-Control',
        'Content-Encoding',
        'Last-Modified',
        'Expires',
        'x-oss-callback',
        'headers'
    ];

    /**
     * OssAdapter constructor.
     * @param OssClient $client
     * @param $bucket
     * @param string $prefix
     * @param array $options
     */
    public function __construct(OssClient $client, $bucket, $prefix = '', array $options = [])
    {
        $this->ossClient = $client;
        $this->bucket = $bucket;
        $this->setPathPrefix($prefix);
        $this->options = $options;
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $options = $this->getOptionsFromConfig($config);
        return $this->ossClient->putObject($this->bucket, $location, $contents, $options);
    }

    /**
     * Write a new file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $result = $this->write($location, stream_get_contents($resource), $config);
        if (is_resource($resource)) {
            fclose($resource);
        }
        return $result;
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $options = $this->getOptionsFromConfig($config);
        return $this->ossClient->putObject($this->bucket, $location, $contents, $options);
    }

    /**
     * Update a file using a stream.
     *
     * @param string $path
     * @param resource $resource
     * @param Config $config Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $result = $this->write($location, stream_get_contents($resource), $config);
        if (is_resource($resource)) {
            fclose($resource);
        }
        return $result;
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function rename($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);
        $this->ossClient->copyObject($this->bucket, $location, $this->bucket, $destination);
        $this->ossClient->deleteObject($this->bucket, $location);
        return true;
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function copy($path, $newpath)
    {
        $location = $this->applyPathPrefix($path);
        $destination = $this->applyPathPrefix($newpath);
        $this->ossClient->copyObject($this->bucket, $location, $this->bucket, $destination);
        return true;
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
        $location = $this->applyPathPrefix($path);
        $this->ossClient->deleteObject($this->bucket, $location);
        return true;
    }

    /**
     * Delete a directory.
     *
     * @param string $dirName
     *
     * @return bool
     * @throws \OSS\Core\OssException
     */
    public function deleteDir($dirName)
    {
        $location = $this->applyPathPrefix($dirName);
        $lists = $this->listContents($location, true);
        if (!$lists) {
            return false;
        }
        $objectList = [];
        foreach ($lists as $value) {
            $objectList[] = $value['path'];
        }
        $this->ossClient->deleteObjects($this->bucket, $objectList);
        return true;
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        $this->ossClient->createObjectDir($this->bucket, $dirname);
        return true;
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     *
     * Aliyun OSS ACL value: 'default', 'private', 'public-read', 'public-read-write'
     * @throws \OSS\Core\OssException
     */
    public function setVisibility($path, $visibility)
    {
        $location = $this->applyPathPrefix($path);
        $this->ossClient->putObjectAcl(
            $this->bucket,
            $location,
            ($visibility == 'public') ? 'public-read' : 'private'
        );
        return true;
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return array|bool|null
     */
    public function has($path)
    {
        $location = $this->applyPathPrefix($path);
        return $this->ossClient->doesObjectExist($this->bucket, $location);
    }

    /**
     * Read a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function read($path)
    {
        $location = $this->applyPathPrefix($path);
        return [
            'contents' => $this->ossClient->getObject($this->bucket, $location)
        ];
    }

    /**
     * Read a file as a stream.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function readStream($path)
    {
        $location = $this->applyPathPrefix($path);
        $resource = ($this->ossClient->isUseSSL() ? 'https://' : 'http://') . $this->bucket . '.' . $this->endpoint . '/' . $location;
        return [
            'stream' => $resource = fopen($resource, 'r')
        ];
    }

    /**
     * 获取文件访问Url
     * @param string $path
     * @return string
     * @throws \OSS\Core\OssException
     */
    public function getUrl($path)
    {
        $location = $this->applyPathPrefix($path);
        if (($this->ossClient->getObjectAcl($this->bucket, $location)) == 'private') {
            throw new RuntimeException('This object does not support retrieving URLs.');
        }
        return ($this->ossClient->isUseSSL() ? 'https://' : 'http://') . $this->bucket . '.' . $this->endpoint . '/' . $location;
    }

    /**
     * 获取文件临时访问路径
     * @param $path
     * @param $expiration
     * @param $options
     * @return string
     * @throws \OSS\Core\OssException
     */
    public function getTemporaryUrl($path, $expiration, $options)
    {
        $location = $this->applyPathPrefix($path);
        $temporaryUrl = $this->ossClient->signUrl($this->bucket, $location, $expiration, OssClient::OSS_HTTP_GET, $options);
        return $temporaryUrl;
    }

    /**
     * List contents of a directory.
     *
     * @param string $directory
     * @param bool $recursive
     *
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function listContents($directory = '', $recursive = false)
    {
        $directory = rtrim($directory, '\\/');

        $result = [];
        $nextMarker = '';
        while (true) {
            // max-keys 用于限定此次返回object的最大数，如果不设定，默认为100，max-keys取值不能大于1000。
            // prefix   限定返回的object key必须以prefix作为前缀。注意使用prefix查询时，返回的key中仍会包含prefix。
            // delimiter是一个用于对Object名字进行分组的字符。所有名字包含指定的前缀且第一次出现delimiter字符之间的object作为一组元素
            // marker   用户设定结果从marker之后按字母排序的第一个开始返回。
            $options = [
                'max-keys' => 1000,
                'prefix' => $directory . '/',
                'delimiter' => '/',
                'marker' => $nextMarker,
            ];
            $res = $this->ossClient->listObjects($this->bucket, $options);

            // 得到nextMarker，从上一次$res读到的最后一个文件的下一个文件开始继续获取文件列表
            $nextMarker = $res->getNextMarker();
            $prefixList = $res->getPrefixList(); // 目录列表
            $objectList = $res->getObjectList(); // 文件列表
            if ($prefixList) {
                foreach ($prefixList as $value) {
                    $result[] = [
                        'type' => 'dir',
                        'path' => $value->getPrefix()
                    ];
                    if ($recursive) {
                        $result = array_merge($result, $this->listContents($value->getPrefix(), $recursive));
                    }
                }
            }
            if ($objectList) {
                foreach ($objectList as $value) {
                    if (($value->getSize() === 0) && ($value->getKey() === $directory . '/')) {
                        continue;
                    }
                    $result[] = [
                        'type' => 'file',
                        'path' => $value->getKey(),
                        'timestamp' => strtotime($value->getLastModified()),
                        'size' => $value->getSize()
                    ];
                }
            }
            if ($nextMarker === '') {
                break;
            }
        }

        return $result;
    }

    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMetadata($path)
    {
        $location = $this->applyPathPrefix($path);
        return $this->ossClient->getObjectMeta($this->bucket, $location);
    }

    /**
     * Get the size of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getSize($path)
    {
        $location = $this->applyPathPrefix($path);
        $response = $this->ossClient->getObjectMeta($this->bucket, $location);
        return [
            'size' => $response['content-length']
        ];
    }

    /**
     * Get the mimetype of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMimetype($path)
    {
        $location = $this->applyPathPrefix($path);
        $response = $this->ossClient->getObjectMeta($this->bucket, $location);
        return [
            'mimetype' => $response['content-type']
        ];
    }

    /**
     * Get the timestamp of a file.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getTimestamp($path)
    {
        $location = $this->applyPathPrefix($path);
        $response = $this->ossClient->getObjectMeta($this->bucket, $location);
        return [
            'timestamp' => $response['last-modified']
        ];
    }

    /**
     * Get the visibility of a file.
     *
     * @param string $path
     *
     * @return array|false
     * @throws \OSS\Core\OssException
     */
    public function getVisibility($path)
    {
        $location = $this->applyPathPrefix($path);
        $response = $this->ossClient->getObjectAcl($this->bucket, $location);
        return [
            'visibility' => $response,
        ];
    }

    /**
     * Get options from the config.
     *
     * @param Config $config
     *
     * @return array
     */
    protected function getOptionsFromConfig(Config $config)
    {
        $options = $this->options;

        if ($visibility = $config->get('visibility')) {
            // For local reference
            $options['visibility'] = $visibility;
            // For external reference
            $options['headers']['x-oss-object-acl'] = $visibility === Filesystem::VISIBILITY_PUBLIC ? 'public-read' : 'private';
        }

        if ($mimetype = $config->get('mimetype')) {
            // For local reference
            $options['mimetype'] = $mimetype;
            // For external reference
            $options['ContentType'] = $mimetype;
        }

        foreach (static::$metaOptions as $option) {
            if (!$config->has($option)) {
                continue;
            }
            $options[$option] = $config->get($option);
        }

        return $options;
    }
}
