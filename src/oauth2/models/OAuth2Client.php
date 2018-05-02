<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\Application as WebApplication;
use yuncms\user\models\User;

/**
 * This is the model class for table "oauth2_client".
 *
 * @property string $client_id
 * @property string $client_secret
 * @property string $redirect_uri
 * @property string $grant_type
 * @property string $scope
 * @property string $name
 * @property string $domain
 * @property string $provider
 * @property string $icp
 * @property integer $user_id
 * @property string $registration_ip
 *
 * @property OAuth2AccessToken[] $accessTokens
 * @property OAuth2AuthorizationCode[] $authorizationCodes
 * @property OAuth2RefreshToken[] $refreshTokens
 * @property User $user
 *
 * @property-read bool $isAuthor 是否是作者
 */
class OAuth2Client extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_client}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'domain', 'provider', 'redirect_uri'], 'required'],
            [['name', 'scope', 'provider', 'icp'], 'string'],
            [['grant_type'], 'string'],
            [['grant_type'], 'default', 'value' => Null],
            [['scope'], 'string'],
            [['redirect_uri'], 'url'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_id' => Yii::t('yuncms', 'App Key'),
            'client_secret' => Yii::t('yuncms', 'App Secret'),
            'redirect_uri' => Yii::t('yuncms', 'Redirect URI'),
            'grant_type' => Yii::t('yuncms', 'Grant type'),
            'scope' => Yii::t('yuncms', 'Scope Authority'),
            'name' => Yii::t('yuncms', 'App Name'),
            'domain' => Yii::t('yuncms', 'App Domain'),
            'provider' => Yii::t('yuncms', 'App Provider'),
            'icp' => Yii::t('yuncms', 'ICP Beian'),
            'created_at' => Yii::t('yuncms', 'Created At'),
            'updated_at' => Yii::t('yuncms', 'Updated At'),
        ];
    }

    /**
     * 生成 ClientKey
     * @throws \yii\base\Exception
     */
    public function generateClientKey()
    {
        $this->client_secret = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateClientKey();
            if (Yii::$app instanceof WebApplication) {
                $this->registration_ip = Yii::$app->request->userIP;
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(OAuth2AccessToken::class, ['client_id' => 'client_id']);
    }

    /**
     * User Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorizationCodes()
    {
        return $this->hasMany(OAuth2AuthorizationCode::class, ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefreshTokens()
    {
        return $this->hasMany(OAuth2RefreshToken::class, ['client_id' => 'client_id']);
    }

    /**
     * 是否是作者
     * @return bool
     */
    public function getIsAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }
}
