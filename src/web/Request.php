<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use yuncms\base\RequestTrait;

/**
 * Class Request
 * @property bool $isWeChat Whether this is an wechat request. This property is read-only.
 * @property void $file
 * @property bool $isAliPay Whether this is an alipay request. This property is read-only.
 *
 * @package yuncms\web
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Request extends \yii\web\Request
{
    use RequestTrait;

    public $files;

    /**
     * Returns whether this is an wechat request.
     * @return bool whether this is an wechat request.
     */
    public function getIsWeChat()
    {
        $userAgent = $this->headers->get('User-Agent', '');
        return stripos($userAgent, 'MicroMessenger') !== false;
    }

    /**
     * Returns whether this is an alipay request.
     * @return bool whether this is an alipay request.
     */
    public function getIsAliPay()
    {
        $userAgent = $this->headers->get('User-Agent', '');
        return stripos($userAgent, 'Alipay') !== false;
    }

    public function getFile()
    {

    }
}