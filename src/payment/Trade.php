<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment;


/**
 * Class Trade
 * @package yuncms\payment
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Trade
{
    //交易类型
    const TYPE_SCAN = 'scan';//扫码支付
    const TYPE_JS_API = 'JSAPI';//应用内JS API,如微信
    const TYPE_APP = 'APP';//app支付
    const TYPE_WAP = 'wap';//H5支付
    const TYPE_WEB = 'web';//PC支付
    const TYPE_POST = 'pos';//刷卡支付

}