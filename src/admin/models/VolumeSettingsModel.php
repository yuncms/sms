<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\models;

use Yii;
use yii\base\Model;
use yuncms\models\Volume;

/**
 * 存储卷配置基类
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class VolumeSettingsModel extends Model
{
    /** @var integer */
    public $timeout;

    /** @var string */
    public $identity;

    /** @var string */
    public $name;

    /** @var string */
    public $title;

    public $class;

    /** @var Volume */
    private $_volume;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['identity', 'name', 'title', 'class'], 'string'],
            [['timeout'], 'number', 'max' => 30, 'min' => 1],
            [['timeout'], 'default', 'value' => 5]
        ];
    }

    /**
     * 设置渠道实例
     * @param Volume $channel
     */
    public function setVolume($channel)
    {
        $this->_volume = $channel;
        $this->setAttributes([
            'identity' => $channel->identity,
            'name' => $channel->name,
            'class' => $channel->className
        ]);
    }

    /**
     * 删除换行
     * @param string $str
     * @return mixed
     */
    public function deleteCRLF($str)
    {
        return str_replace(["\r\n", "\n", "\r"], '', $str);
    }

    /**
     * 保存渠道配置
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $this->_volume->configuration = $this->getAttributes();
            return $this->_volume->save();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'timeout' => Yii::t('yuncms/transaction', 'Timeout'),
        ];
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }
}