<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use yuncms\oauth2\Exception;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%oauth2_refresh_token}}".
 *
 * @property string $refresh_token
 * @property string $client_id
 * @property integer $user_id
 * @property integer $expires
 * @property string $scope
 *
 * @property OAuth2Client $client
 * @property User $user
 */
class OAuth2RefreshToken extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_refresh_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refresh_token', 'client_id', 'user_id', 'expires'], 'required'],
            [['user_id', 'expires'], 'integer'],
            [['scope'], 'string'],
            [['refresh_token'], 'string', 'max' => 40],
            [['client_id'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'refresh_token' => Yii::t('yuncms', 'Refresh Token'),
            'client_id' => Yii::t('yuncms', 'Client ID'),
            'user_id' => Yii::t('yuncms', 'User ID'),
            'expires' => Yii::t('yuncms', 'Expires'),
            'scope' => Yii::t('yuncms', 'Scope'),
        ];
    }

    /**
     *
     * @param array $attributes
     * @throws Exception
     * @return OAuth2RefreshToken
     * @throws \yii\base\Exception
     */
    public static function createRefreshToken(array $attributes)
    {
        static::deleteAll(['<', 'expires', time()]);
        $attributes['refresh_token'] = Yii::$app->security->generateRandomString(40);
        $refreshToken = new static($attributes);

        if ($refreshToken->save()) {
            return $refreshToken;
        } else {
            \Yii::error(__CLASS__ . ' validation error:' . VarDumper::dumpAsString($refreshToken->errors));
        }
        throw new Exception('Unable to create refresh token', Exception::SERVER_ERROR);
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
