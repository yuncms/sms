<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rbac;

use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class GuestRule
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class GuestRule extends Rule
{
    const RULE_NAME = 'guest_rule';

    /**
     * @inheritdoc
     */
    public $name = self::RULE_NAME;

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return Yii::$app->user->isGuest;
    }
}