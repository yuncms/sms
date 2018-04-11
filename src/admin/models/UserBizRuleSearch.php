<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yuncms\helpers\UserRBACHelper;
use yuncms\rbac\RouteRule;

/**
 * Description of BizRule
 */
class UserBizRuleSearch extends Model
{
    /**
     * @var string name of the rule
     */
    public $name;


    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('yuncms', 'Rule Name'),
        ];
    }

    /**
     * Search BizRule
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $models = [];
        $included = !($this->load($params) && $this->validate() && trim($this->name) !== '');
        foreach (UserRBACHelper::getAuthManager()->getRules() as $name => $item) {
            if ($name != RouteRule::RULE_NAME && ($included || stripos($item->name, $this->name) !== false)) {
                $models[$name] = new AdminBizRule($item);
            }
        }

        return new ArrayDataProvider([
            'allModels' => $models,
        ]);
    }
}