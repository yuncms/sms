<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `trade_charges`.
 */
class m180315_104011_create_trade_charges_table extends Migration
{
    public $tableName = '{{%trade_charges}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey(),
            'paid' => $this->boolean()->defaultValue(false),//boolean 是否已付款
            'refunded' => $this->boolean()->defaultValue(false),//boolean 是否存在退款信息
            'reversed' => $this->boolean()->defaultValue(false),//boolean 订单是否撤销
            'channel' => $this->string(50)->notNull(),//付款渠道
            'order_no' => $this->string()->notNull(),//商户订单号，适配每个渠道对此参数的要求，必须在商户的系统内唯一
            'client_ip' => $this->ipAddress()->notNull(),//发起支付请求客户端的 IP 地址
            'amount' => $this->unsignedInteger()->notNull(),//订单总金额（必须大于 0），单位为对应币种的最小货币单位，人民币为分
            'amount_settle' => $this->unsignedInteger()->notNull(),//清算金额，单位为对应币种的最小货币单位，人民币为分。
            'currency' => $this->string(3)->notNull(),//3 位 ISO 货币代码，人民币为  cny 。
            'subject' => $this->string(32)->notNull(),//商品标题，该参数最长为 32 个 Unicode 字符
            'body' => $this->string(128)->notNull(),//商品描述信息，该参数最长为 128 个 Unicode 字符
            //'extra',
            'time_paid' => $this->unixTimestamp(),//订单支付完成时的 Unix 时间戳。（银联支付成功时间为接收异步通知的时间）
            'time_expire' => $this->unixTimestamp(),//订单失效时间
            'time_settle' => $this->unixTimestamp(),//订单清算时间，用 Unix 时间戳表示。（暂不生效）
            'transaction_no' => $this->string(64),//支付渠道返回的交易流水号。
            //'refunds',//退款详情列表
            'amount_refunded' => $this->unsignedInteger()->notNull(),//已退款总金额，单位为对应币种的最小货币单位，例如：人民币为分。
            'failure_code' => $this->string(),//订单的错误码
            'failure_msg' => $this->string(),//订单的错误消息的描述。
            'metadata',
            //'credential',
            'description' => $this->string(255),//订单附加说明，最多 255 个 Unicode 字符。
            'created_at' => $this->unixTimestamp(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
