<?php

namespace yuncms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\admin\models\VolumeSettingsModel;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\filesystem\Adapter;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%volumes}}".
 *
 * @property integer $id
 * @property string $identity
 * @property string $name
 * @property string $className
 * @property string $configuration
 * @property bool $pub
 * @property string $url
 * @property bool $status
 * @property integer $created_at
 * @property \yuncms\admin\models\VolumeSettingsModel $settingsModel
 * @property integer $updated_at
 */
class Volume extends ActiveRecord
{
    const STATUS_ACTIVE = 0b0;
    const STATUS_DISABLED = 0b1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%volumes}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'configuration' => [
                'class' => JsonBehavior::class,
                'attributes' => ['configuration'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'name'], 'required'],

            [['identity', 'name'], 'string', 'max' => 64],
            [['className', 'url'], 'string', 'max' => 255],
            [['identity'], 'unique'],

            [['pub', 'status'], 'boolean'],
            [['configuration'], JsonValidator::class],

            ['status', 'default', 'value' => self::STATUS_DISABLED],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLED]],

        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'identity' => Yii::t('yuncms', 'Volume Identity'),
            'name' => Yii::t('yuncms', 'Volume Name'),
            'className' => Yii::t('yuncms', 'Volume ClassName'),
            'configuration' => Yii::t('yuncms', 'Volume Config'),
            'pub' => Yii::t('yuncms', 'Pub'),
            'status' => Yii::t('yuncms', 'Status'),
            'created_at' => Yii::t('yuncms', 'Created At'),
            'updated_at' => Yii::t('yuncms', 'Updated At'),
        ];
    }

    /**
     * 是否发布状态
     * @return bool
     */
    public function isPublic()
    {
        return empty($this->url);
    }

    /**
     * 获取渠道设置模型
     * @return VolumeSettingsModel
     */
    public function getSettingsModel()
    {
        /** @var Adapter $volumeClass */
        $volumeClass = $this->className;
        /** @var VolumeSettingsModel $model */
        $model = $volumeClass::getSettingsModel();
        $model->setVolume($this);
        if ($this->configuration) {
            $model->setAttributes($this->configuration->toArray(), false);
        }
        return $model;
    }

    /**
     * @inheritdoc
     * @return VolumeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VolumeQuery(get_called_class());
    }
}
