<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment\contracts;

/**
 * 收款接口
 * @package yuncms\payment\contracts
 */
interface ChargeInterface
{
    /**
     * 收款单号
     * @return int
     */
    public function getId();

    /**
     * 商户订单号，适配每个渠道对此参数的要求，必须在商户的系统内唯一。
     * alipay 、 alipay_wap 、  alipay_qr 、 alipay_scan 、 alipay_pc_direct : 1-64 位，可包含字母、数字、下划线；
     * wx : 2-32 位； wx_pub_scan :1-32 位的数字和字母组合；
     * bfb : 1-20 位；
     * upacp : 8-40 位；
     * yeepay_wap : 1-50 位；
     * jdpay_wap : 1-30 位；
     * qpay :1-30 位；
     * isv_qr 、 isv_scan 、 isv_wap : 8-32 位，不重复，建议时间戳+随机数（或交易顺序号）；
     * cmb_wallet : 6-32 位的数字和字母组合，一天内不能重复，订单日期+订单号唯一定位一笔订单，示例: 20170808test01)。
     * 注： 推荐使用 8-20 位，要求数字或字母，不允许特殊字符。
     * @return string
     */
    public function getOrderNo();

    /**
     * 商品标题，该参数最长为 32 个 Unicode 字符。银联全渠道（ upacp / upacp_wap ）限制在 32 个字节；支付宝部分渠道不支持特殊字符。
     * @return string
     */
    public function getSubject();

    /**
     * 商品描述信息，该参数最长为 128 个 Unicode 字符。 yeepay_wap 对于该参数长度限制为 100 个 Unicode 字符；支付宝部分渠道不支持特殊字符。
     * @return string
     */
    public function getBody();

    /**
     * 订单总金额（必须大于 0），单位为对应币种的最小货币单位，人民币为分。如订单总金额为 1 元， amount 为 100，么么贷商户请查看申请的借贷金额范围。
     * @return int
     */
    public function getAmount();

    /**
     * 3 位 ISO 货币代码，人民币为  cny 。
     * @return string
     */
    public function getCurrency();

    /**
     * 发起支付请求客户端的 IPv4 地址，如: 127.0.0.1。
     * @return string
     */
    public function getClientIp();

    /**
     * 订单失效时间的 Unix 时间戳。
     * 时间范围在订单创建后的 1 分钟到 15 天，默认为 1 天，创建时间以服务器时间为准。
     * 微信对该参数的时间范围在订单创建后的 1 分钟到 7 天，默认为 2 小时；银联对该参数的有效值限制为 1 小时内。
     * @return int
     */
    public function getTimeExpire();

    /**
     * 订单附加说明，最多 255 个 Unicode 字符。
     * @return string
     */
    public function getDescription();
}