<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

/**
 * Class Response
 * @package yuncms\web
 * @author Tongle Xu <xutongle@gmail.com>
 *
 * @property string $lastModifiedHeader
 * @property null|string $contentType
 */
class Response extends \yii\web\Response
{
    /**
     * @var bool whether the response has been prepared.
     */
    private $_isPrepared = false;

    /**
     * Returns the Content-Type header (sans `charset=X`) that the response will most likely include.
     *
     * @return string|null
     */
    public function getContentType(): string
    {
        // If the response hasn't been prepared yet, go with what the formatter is going to set
        if (!$this->_isPrepared) {
            switch ($this->format) {
                case self::FORMAT_HTML:
                    return 'text/html';
                case self::FORMAT_XML:
                    return 'application/xml';
                case self::FORMAT_JSON:
                    return 'application/json';
                case self::FORMAT_JSONP:
                    return 'application/javascript';
            }
        }

        // Otherwise check the Content-Type header
        if (($header = $this->getHeaders()->get('content-type')) === null) {
            return null;
        }

        if (($pos = strpos($header, ';')) !== false) {
            $header = substr($header, 0, $pos);
        }

        return strtolower(trim($header));
    }

    /**
     * Sets headers that will instruct the client to cache this response.
     *
     * @return static self reference
     */
    public function setCacheHeaders()
    {
        $cacheTime = 31536000; // 1 year
        $this->getHeaders()
            ->set('Expires', gmdate('D, d M Y H:i:s', time() + $cacheTime) . ' GMT')
            ->set('Pragma', 'cache')
            ->set('Cache-Control', 'max-age=' . $cacheTime);

        return $this;
    }

    /**
     * Sets a Last-Modified header based on a given file path.
     *
     * @param string $path The file to read the last modified date from.
     * @return static self reference
     */
    public function setLastModifiedHeader(string $path)
    {
        $modifiedTime = filemtime($path);

        if ($modifiedTime) {
            $this->getHeaders()->set('Last-Modified', gmdate('D, d M Y H:i:s', $modifiedTime) . ' GMT');
        }

        return $this;
    }

    /**
     * 开始分片输出
     * @param string $content
     */
    public function startBuffering($content)
    {
        $this->getHeaders()
            ->set('Surrogate-Control','BigPipe/1.0')
            ->set('X-Accel-Buffering', 'no');
        $this->data = $content;
        $this->send();
    }

    /**
     * 将内容从缓冲区刷出
     * @param string $content
     */
    public function flushBuffering($content = null)
    {
        if (!is_null($content)) {
            echo $content;
        }
        ob_flush();
        flush();
    }

    /**
     * @inheritdoc
     */
    protected function prepare()
    {
        $return = parent::prepare();
        $this->_isPrepared = true;
        return $return;
    }
}