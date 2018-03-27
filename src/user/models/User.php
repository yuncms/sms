<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\helpers\Url;
use yuncms\assets\UserAsset;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\PasswordHelper;
use creocoder\taggable\TaggableBehavior;

/**
 * This is the model class for table "{{%user}}".
 *
 * Magic methods:
 * @method ActiveRecord getTagValues($asArray = null)
 * @method ActiveRecord setTagValues($values)
 * @method ActiveRecord addTagValues($values)
 * @method ActiveRecord removeTagValues($values)
 * @method ActiveRecord removeAllTagValues()
 * @method ActiveRecord hasTagValues($values)
 *
 * @property integer $avatar
 * @property-read bool $isAvatar 是否有头像
 *
 * Defined relations:
 * @property UserExtra $extra
 * @property UserLoginHistory[] $userLoginHistories
 * @property UserProfile $profile
 * @property UserSocialAccount[] $socialAccounts
 * @property Tag[] $tags
 * @property UserToken[] $userTokens
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class User extends \yuncms\models\BaseUser
{
    //场景定义
    const SCENARIO_CREATE = 'create';//后台或控制台创建用户
    const SCENARIO_UPDATE = 'update';//后台或控制台修改用户
    const SCENARIO_REGISTER = 'basic_create';//邮箱注册
    const SCENARIO_EMAIL_REGISTER = 'email_create';//邮箱注册
    const SCENARIO_MOBILE_REGISTER = 'mobile_create';//手机号注册
    const SCENARIO_SETTINGS = 'settings';//更新
    const SCENARIO_CONNECT = 'connect';//账户链接或自动注册新用户
    const SCENARIO_PASSWORD = 'password';

    //头像
    const AVATAR_BIG = 'big';
    const AVATAR_MIDDLE = 'middle';
    const AVATAR_SMALL = 'small';

    /**
     * @var UserProfile|null
     */
    private $_profile;

    /** @var  UserExtra|null */
    private $_extra;

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors,[
            'taggable' => [
                'class' => TaggableBehavior::class,
                'tagValuesAsArray' => true,
                'tagRelation' => 'tags',
                'tagValueAttribute' => 'id',
                'tagFrequencyAttribute' => 'frequency',
            ]
        ]);
    }

    /**
     * 定义场景
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            static::SCENARIO_CREATE => ['nickname', 'email', 'password'],
            static::SCENARIO_UPDATE => ['nickname', 'email', 'password'],
            static::SCENARIO_REGISTER => ['nickname', 'password'],
            static::SCENARIO_EMAIL_REGISTER => ['nickname', 'email', 'password'],
            static::SCENARIO_MOBILE_REGISTER => ['mobile', 'password'],
            static::SCENARIO_SETTINGS => ['username', 'email', 'password'],
            static::SCENARIO_CONNECT => ['nickname', 'email', 'password'],//链接账户密码可以为空邮箱可以为空
            static::SCENARIO_PASSWORD => ['password'],//只修改密码
        ]);
    }

    /**
     * 验证规则
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            // nickname rules
            'nicknameRequired' => ['nickname', 'required', 'on' => [self::SCENARIO_EMAIL_REGISTER, self::SCENARIO_CONNECT]],
            // email rules
            'emailRequired' => ['email', 'required', 'on' => [self::SCENARIO_EMAIL_REGISTER]],
            //mobile rules
            'mobileRequired' => ['mobile', 'required', 'on' => [self::SCENARIO_MOBILE_REGISTER]],
            // password rules
            'passwordRequired' => ['password', 'required', 'on' => [self::SCENARIO_EMAIL_REGISTER, self::SCENARIO_MOBILE_REGISTER]],
            // tags rules
            'tags' => ['tagValues', 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtra()
    {
        return $this->hasOne(UserExtra::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoginHistories()
    {
        return $this->hasMany(UserLoginHistory::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    /**
     * 返回所有已经连接的社交媒体账户
     * @return UserSocialAccount[] Connected accounts ($provider => $account)
     */
    public function getSocialAccounts()
    {
        $connected = [];
        /** @var UserSocialAccount[] $accounts */
        $accounts = $this->hasMany(UserSocialAccount::class, ['user_id' => 'id'])->all();
        /**
         * @var UserSocialAccount $account
         */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->viaTable('{{%user_tag}}', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(UserToken::class, ['user_id' => 'id']);
    }

    /**
     * 返回用户是否有头像
     * @return boolean Whether the user is blocked or not.
     */
    public function getIsAvatar()
    {
        return $this->avatar != 0;
    }

    /**
     * 获取头像Url
     * @param string $size
     * @return string
     */
    public function getAvatar($size = self::AVATAR_MIDDLE)
    {
        $size = in_array($size, [self::AVATAR_BIG, self::AVATAR_MIDDLE, self::AVATAR_SMALL]) ? $size : self::AVATAR_BIG;
        if ($this->getIsAvatar()) {
            $avatarFileName = "_avatar_{$size}.jpg";
            return $this->getAvatarUrl($this->id) . $avatarFileName . '?_t=' . $this->updated_at;
        } else {
            $avatarUrl = "/img/no_avatar_{$size}.gif";
            if (Yii::getAlias('@webroot', false)) {
                $baseUrl = UserAsset::register(Yii::$app->view)->baseUrl;
                return Url::to($baseUrl . $avatarUrl, true);
            } else {
                return '';
            }
        }
    }

    /**
     * 设置用户资料
     * @param UserProfile $profile
     */
    public function setProfile(UserProfile $profile)
    {
        $this->_profile = $profile;
    }

    /**
     * 设置用户延伸资料
     * @param UserExtra $extra
     */
    public function setExtra($extra)
    {
        $this->_extra = $extra;
    }

    /**
     * 此方法用于注册新用户帐户。 如果 enableConfirmation 设置为true，则此方法
     * 将生成新的确认令牌，并使用邮件发送给用户。
     *
     * @return boolean
     */
    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->password = Yii::$app->settings->get('user.enableGeneratingPassword') ? PasswordHelper::generate(8) : $this->password;
        if ($this->scenario == self::SCENARIO_EMAIL_REGISTER) {
            $this->email_confirmed_at = Yii::$app->settings->get('user.enableConfirmation') ? null : time();
        }
        $this->trigger(self::BEFORE_REGISTER);
        if (!$this->save()) {
            return false;
        }
        if (Yii::$app->settings->get('user.enableConfirmation') && !empty($this->email)) {
            /** @var UserToken $token */
            $token = new UserToken(['type' => UserToken::TYPE_CONFIRMATION]);
            $token->link('user', $this);
            $this->sendMessage($this->email, Yii::t('yuncms', 'Welcome to {0}', Yii::$app->name), 'welcome', ['user' => $this, 'token' => isset($token) ? $token : null, 'module' => $this->module, 'showPassword' => false]);
        } else {
            Yii::$app->user->login($this, Yii::$app->settings->get('user.rememberFor'));
        }
        $this->trigger(self::AFTER_REGISTER);
        return true;
    }

    /**
     * 创建新用户帐户。 如果用户不提供密码，则会生成密码。
     *
     * @return boolean
     */
    public function createUser()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->password = $this->password == null ? PasswordHelper::generate(8) : $this->password;
        $this->trigger(self::BEFORE_CREATE);
        if (!$this->save()) {
            return false;
        }
        $this->trigger(self::AFTER_CREATE);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            if ($this->_profile == null) {
                $this->_profile = new UserProfile();
            }
            $this->_profile->link('user', $this);

            if ($this->_extra == null) {
                $this->_extra = new UserExtra();
            }
            $this->_extra->link('user', $this);
        }
    }
}