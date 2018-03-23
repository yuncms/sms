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
    const TYPE_NATIVE = 'NATIVE';//原生扫码支付
    const TYPE_JS_API = 'JSAPI';//应用内JS API,如微信
    const TYPE_APP = 'APP';//app支付
    const TYPE_H5 = 'WAP';//H5支付
    const TYPE_PC = 'PC';//PC支付
    const TYPE_MICROPAY = 'MICROPAY';//刷卡支付
    const TYPE_OFFLINE = 'OFFLINE';//离线（汇款、转账等）支付
}