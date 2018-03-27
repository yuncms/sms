<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use DateTime;
use DateTimeZone;
use yuncms\db\ActiveRecord;
use yuncms\validators\MobileValidator;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property integer $gender
 * @property string $mobile
 * @property string $email
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $location
 * @property string $address
 * @property string $website
 * @property string $timezone
 * @property string $birthday
 * @property integer $current
 * @property string $qq
 * @property string $weibo
 * @property string $wechat
 * @property string $facebook
 * @property string $twitter
 * @property string $company
 * @property string $company_job
 * @property string $school
 * @property string $introduction
 * @property string $bio
 *
 * @property User $user
 * @property UserExtra $extra
 *
 * @property-read string $genderName 性别
 * @property-read string $currentName 工作状态
 */
class UserProfile extends ActiveRecord
{
    // 性别
    const GENDER_UNCONFIRMED = 0b0;
    const GENDER_MALE = 0b1;
    const GENDER_FEMALE = 0b10;

    //当前状态
    const CURRENT_OTHER = 0b0;//其他
    const CURRENT_WORK = 0b1;//正常工作
    const CURRENT_FREELANCE = 0b10;//自由职业者
    const CURRENT_START = 0b11;//创业
    const CURRENT_OUTSOURCE = 0b100;//外包
    const CURRENT_JOB = 0b101;//求职
    const CURRENT_STUDENT = 0b110;//学生

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //性别
            ['gender', 'default', 'value' => self::GENDER_UNCONFIRMED],
            ['gender', 'in', 'range' => [
                self::GENDER_MALE,
                self::GENDER_FEMALE,
                self::GENDER_UNCONFIRMED
            ]],

            //职业状态
            ['current', 'default', 'value' => self::CURRENT_OTHER],
            ['current', 'in', 'range' => [
                self::CURRENT_OTHER,
                self::CURRENT_WORK,
                self::CURRENT_FREELANCE,
                self::CURRENT_START,
                self::CURRENT_OUTSOURCE,
                self::CURRENT_JOB,
                self::CURRENT_STUDENT,
            ]],

            //手机号
            ['mobile', 'match', 'pattern' => User::$mobileRegexp],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            [
                'mobile',
                MobileValidator::class,
                'when' => function ($model) {
                    return $model->country == 'China';
                }
            ],

            ['email', 'email'],
            ['email', 'trim'],

            ['birthday', 'date', 'format' => 'php:Y-m-d', 'min' => '1900-01-01', 'max' => date('Y-m-d')],
            ['birthday', 'string', 'max' => 15],

            ['website', 'url'],

            ['qq', 'integer', 'min' => 10001, 'max' => 9999999999],
            ['timezone', 'validateTimeZone'],

            [['bio'], 'string'],
            [['country', 'province', 'city', 'location', 'address', 'company', 'company_job', 'school', 'introduction'], 'string', 'max' => 255],


            [['weibo', 'wechat', 'facebook', 'twitter'], 'string', 'max' => 50],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('yuncms', 'User ID'),
            'gender' => Yii::t('yuncms', 'Gender'),
            'mobile' => Yii::t('yuncms', 'Mobile'),
            'email' => Yii::t('yuncms', 'Email'),
            'country' => Yii::t('yuncms', 'Country'),
            'province' => Yii::t('yuncms', 'Province'),
            'city' => Yii::t('yuncms', 'City'),
            'location' => Yii::t('yuncms', 'Location'),
            'address' => Yii::t('yuncms', 'Address'),
            'website' => Yii::t('yuncms', 'Website'),
            'timezone' => Yii::t('yuncms', 'Timezone'),
            'birthday' => Yii::t('yuncms', 'Birthday'),
            'current' => Yii::t('yuncms', 'Current'),
            'qq' => Yii::t('yuncms', 'QQ'),
            'weibo' => Yii::t('yuncms', 'Weibo'),
            'wechat' => Yii::t('yuncms', 'Wechat'),
            'facebook' => Yii::t('yuncms', 'Facebook'),
            'twitter' => Yii::t('yuncms', 'Twitter'),
            'company' => Yii::t('yuncms', 'Company'),
            'company_job' => Yii::t('yuncms', 'Company Job'),
            'school' => Yii::t('yuncms', 'School'),
            'introduction' => Yii::t('yuncms', 'Introduction'),
            'bio' => Yii::t('yuncms', 'Bio'),
        ];
    }

    /**
     * 获取性别的字符串标识
     */
    public function getGenderName()
    {
        switch ($this->gender) {
            case self::GENDER_UNCONFIRMED:
                $genderName = Yii::t('yuncms', 'Secrecy');
                break;
            case self::GENDER_MALE:
                $genderName = Yii::t('yuncms', 'Male');
                break;
            case self::GENDER_FEMALE:
                $genderName = Yii::t('yuncms', 'Female');
                break;
            default:
                throw new \RuntimeException('Your database is not supported!');
        }
        return $genderName;
    }

    /**
     * 获取职业的字符串标识
     * @return string
     */
    public function getCurrentName()
    {
        switch ($this->current) {
            case self::CURRENT_OTHER:
                $currentName = Yii::t('yuncms', 'Other');
                break;
            case self::CURRENT_WORK:
                $currentName = Yii::t('yuncms', 'Work');
                break;
            case self::CURRENT_FREELANCE:
                $currentName = Yii::t('yuncms', 'Freelance');
                break;
            case self::CURRENT_START:
                $currentName = Yii::t('yuncms', 'Start');
                break;
            case self::CURRENT_OUTSOURCE:
                $currentName = Yii::t('yuncms', 'Outsource');
                break;
            case self::CURRENT_JOB:
                $currentName = Yii::t('yuncms', 'Job');
                break;
            case self::CURRENT_STUDENT:
                $currentName = Yii::t('yuncms', 'Student');
                break;
            default:
                throw new \RuntimeException('Your database is not supported!');
        }
        return $currentName;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getExtra()
    {
        return $this->hasOne(UserExtra::class, ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UserProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserProfileQuery(get_called_class());
    }

    /**
     * 验证时区
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @return void
     */
    public function validateTimeZone($attribute)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new \DateTimeZone(Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param DateTimeZone $timeZone
     * @internal param DateTimeZone $timezone the timezone to save to the user's profile
     * @return void
     */
    public function setTimeZone(DateTimeZone $timeZone)
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param DateTime $dateTime the datetime to convert
     * @return DateTime
     */
    public function toLocalTime(DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }
}
