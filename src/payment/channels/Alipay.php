<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\channels;

use Yii;
use yii\base\InvalidConfigException;
use yuncms\payment\Channel;

/**
 * Class Alipay
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Alipay extends Channel
{
    const SIGNATURE_METHOD_RSA = 'RSA';
    const SIGNATURE_METHOD_RSA2 = 'RSA2';

    /**
     * @var integer
     */
    public $appId;

    /**
     * @var string 私钥
     */
    public $privateKey;

    /**
     * @var string 公钥
     */
    public $publicKey;

    /**
     * @var string 签名方法
     */
    public $signType = self::SIGNATURE_METHOD_RSA2;

    /**
     * @var string 网关地址
     */
    public $baseUrl = 'https://openapi.alipay.com';

    /**
     * 初始化
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!in_array('sha256', openssl_get_md_methods(), true)) {
            trigger_error('need openssl support sha256', E_USER_ERROR);
        }
        if (empty ($this->appId)) {
            throw new InvalidConfigException ('The "appId" property must be set.');
        }
        if (empty ($this->privateKey)) {
            throw new InvalidConfigException ('The "privateKey" property must be set.');
        }
        if (empty ($this->publicKey)) {
            throw new InvalidConfigException ('The "publicKey" property must be set.');
        }
        $this->initPrivateKey();
        $this->initPublicKey();
    }

    /**
     * 初始化私钥
     * @throws InvalidConfigException
     */
    protected function initPrivateKey()
    {
        $privateKey = Yii::getAlias($this->privateKey);
        if (!is_file($privateKey)) {
            $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($this->privateKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            $privateKey = "file://" . $privateKey;
        }
        $this->privateKey = openssl_pkey_get_private($privateKey);
        if ($this->privateKey === false) {
            throw new InvalidConfigException(openssl_error_string());
        }
    }

    /**
     * 初始化公钥
     * @throws InvalidConfigException
     */
    protected function initPublicKey()
    {
        $publicKey = Yii::getAlias($this->publicKey);
        if (!is_file($publicKey)) {
            $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($this->publicKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        } else {
            $publicKey = "file://" . $publicKey;
        }
        $this->publicKey = openssl_pkey_get_public($publicKey);
        if ($this->publicKey === false) {
            throw new InvalidConfigException(openssl_error_string());
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Yii::t('yuncms', 'Alipay');
    }


}