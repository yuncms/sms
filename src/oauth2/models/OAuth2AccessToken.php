<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\models;

use Yii;
use yii\helpers\VarDumper;
use yii\db\ActiveRecord;
use yuncms\oauth2\Exception;
use yuncms\user\models\User;

/**
 * This is the model class for table "oauth_access_token".
 *
 * @property string $access_token
 * @property string $client_id
 * @property integer $user_id
 * @property integer $expires
 * @property string $scope
 *
 * @property OAuth2Client $client
 * @property User $user
 */
class OAuth2AccessToken extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_access_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'client_id', 'user_id', 'expires'], 'required'],
            [['user_id', 'expires'], 'integer'],
            [['scope'], 'string'],
            [['access_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'access_token' => Yii::t('yuncms','Access Token'),
            'client_id' => Yii::t('yuncms','Client ID'),
            'user_id' => Yii::t('yuncms','User ID'),
            'expires' => Yii::t('yuncms','Expires'),
            'scope' => Yii::t('yuncms','Scopes'),
        ];
    }

    /**
     * @param array $attributes
     * @return OAuth2AccessToken
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public static function createAccessToken(array $attributes)
    {
        static::deleteAll(['<', 'expires', time()]);
        $attributes['access_token'] = Yii::$app->security->generateRandomString(40);
        $accessToken = new static($attributes);
        if ($accessToken->save()) {
            return $accessToken;
        } else {
            Yii::error(__CLASS__ . ' validation error:' . VarDumper::dumpAsString($accessToken->errors));
        }
        throw new Exception(Yii::t('yuncms', 'Unable to create access token'), Exception::SERVER_ERROR);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(OAuth2Client::class, ['client_id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
