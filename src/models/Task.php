<?php

namespace yuncms\models;

use Yii;
use yuncms\db\ActiveRecord;
use yuncms\helpers\CronParseHelper;

/**
 * This is the model class for table "{{%tasks}}".
 *
 * @property int $id
 * @property string $name
 * @property string $route
 * @property string $crontab_str
 * @property int $switch
 * @property int $status
 * @property string $last_rundate
 * @property string $next_rundate
 * @property string $exec_memory
 * @property string $exec_time
 */
class Task extends ActiveRecord
{
    const SWITCH_ACTIVE = true;
    const SWITCH_DISABLE = false;

    const STATUS_NORMAL = false;
    const STATUS_SAVE = true;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'route', 'crontab_str'], 'required'],
            [['switch', 'status'], 'integer'],
            [['last_rundate', 'next_rundate'], 'safe'],
            [['execmemory', 'exectime'], 'number'],
            [['name', 'crontab_str'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 100],
            ['crontab_str', 'crontabValidate'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'name' => Yii::t('yuncms', 'Task Name'),
            'route' => Yii::t('yuncms', 'Task Route'),
            'crontab_str' => Yii::t('yuncms', 'Crontab Str'),
            'switch' => Yii::t('yuncms', 'Task Switch'),
            'status' => Yii::t('yuncms', 'Task Status'),
            'last_rundate' => Yii::t('yuncms', 'Last Rundate'),
            'next_rundate' => Yii::t('yuncms', 'Next Rundate'),
            'execmemory' => Yii::t('yuncms', 'Exec Memory'),
            'exectime' => Yii::t('yuncms', 'Exec Time'),
        ];
    }

    /**
     * Validate balance
     */
    public function crontabValidate()
    {
        if (!CronParseHelper::check($this->crontab_str)) {
            $message = Yii::t('yuncms', 'Format verification failed.');
            $this->addError('crontab_str', $message);
        }
    }

    /**
     * 计算下次运行时间
     * @throws \Exception
     */
    public function getNextRunDate()
    {
        return CronParseHelper::formatToDate($this->crontab_str, 1)[0];
    }
}
