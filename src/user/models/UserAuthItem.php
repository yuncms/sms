<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\rbac\Item;
use yii\helpers\Json;
use yuncms\base\Model;
use yuncms\helpers\UserRBACHelper;

/**
 * This is the model class for table "tbl_auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $ruleName
 * @property string $data
 *
 * @property Item $item
 * @property-read boolean $isNewRecord
 *
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class UserAuthItem extends Model
{
    public $name;
    public $type;
    public $description;
    public $ruleName;
    public $data;

    /**
     * @var Item
     */
    private $_item;

    /**
     * Initialize object
     * @param Item $item
     * @param array $config
     */
    public function __construct($item = null, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->data = $item->data === null ? null : Json::encode($item->data);
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruleName'], 'checkRule'],
            [['name', 'type'], 'required'],
            [['name'], 'checkUnique', 'when' => function () {
                return $this->isNewRecord || ($this->_item->name != $this->name);
            }],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @return \yii\rbac\ManagerInterface|\yuncms\rbac\DbManager
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAuthManager()
    {
        if (Yii::$app instanceof \yuncms\admin\Application) {
            return Yii::$app->getUserAuthManager();
        }
        return Yii::$app->getAuthManager();
    }

    /**
     * Check role is unique
     * @throws \yii\base\InvalidConfigException
     */
    public function checkUnique()
    {
        $authManager = self::getAuthManager();
        $value = $this->name;
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" has already been taken.');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }

    /**
     * Check for rule
     * @throws \yii\base\InvalidConfigException
     */
    public function checkRule()
    {
        $name = $this->ruleName;
        if (!self::getAuthManager()->getRule($name)) {
            try {
                $rule = Yii::createObject($name);
                if ($rule instanceof \yii\rbac\Rule) {
                    $rule->name = $name;
                    self::getAuthManager()->add($rule);
                } else {
                    $this->addError('ruleName', Yii::t('yuncms', 'Invalid rule "{value}"', ['value' => $name]));
                }
            } catch (\Exception $exc) {
                $this->addError('ruleName', Yii::t('yuncms', 'Rule "{value}" does not exists', ['value' => $name]));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('yuncms', 'Auth Name'),
            'type' => Yii::t('yuncms', 'Auth Type'),
            'description' => Yii::t('yuncms', 'Auth Description'),
            'ruleName' => Yii::t('yuncms', 'Auth Rule Name'),
            'data' => Yii::t('yuncms', 'Auth Data'),
        ];
    }

    /**
     * Check if is new record.
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }

    /**
     * Find role
     * @param string $id
     * @return null|\self
     * @throws \yii\base\InvalidConfigException
     */
    public static function find($id)
    {
        $item = self::getAuthManager()->getRole($id);
        if ($item !== null) {
            return new self($item);
        }
        return null;
    }

    /**
     * Save role to [[\yii\rbac\authManager]]
     * @return boolean
     * @throws \Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $manager = self::getAuthManager();
            if ($this->_item === null) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                } else {
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName = $this->ruleName;
            $this->_item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }
            UserRBACHelper::invalidate();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds an item as a child of another item.
     * @param array $items
     * @return integer
     * @throws \yii\base\InvalidConfigException
     */
    public function addChildren($items)
    {
        $manager = self::getAuthManager();
        $success = 0;
        if ($this->_item) {
            foreach ($items as $name) {
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->addChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        if ($success > 0) {
            UserRBACHelper::invalidate();
        }
        return $success;
    }

    /**
     * Remove an item as a child of another item.
     * @param array $items
     * @return integer
     * @throws \yii\base\InvalidConfigException
     */
    public function removeChildren($items)
    {
        $manager = self::getAuthManager();
        $success = 0;
        if ($this->_item !== null) {
            foreach ($items as $name) {
                $child = $manager->getPermission($name);
                if ($this->type == Item::TYPE_ROLE && $child === null) {
                    $child = $manager->getRole($name);
                }
                try {
                    $manager->removeChild($this->_item, $child);
                    $success++;
                } catch (\Exception $exc) {
                    Yii::error($exc->getMessage(), __METHOD__);
                }
            }
        }
        if ($success > 0) {
            UserRBACHelper::invalidate();
        }
        return $success;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getItems()
    {
        $manager = self::getAuthManager();
        $avaliable = [];
        if ($this->type == Item::TYPE_ROLE) {
            foreach (array_keys($manager->getRoles()) as $name) {
                $avaliable[$name] = 'role';
            }
        }
        foreach (array_keys($manager->getPermissions()) as $name) {
            $avaliable[$name] = $name[0] == '/' ? 'route' : 'permission';
        }

        $assigned = [];
        foreach ($manager->getChildren($this->_item->name) as $item) {
            $assigned[$item->name] = $item->type == 1 ? 'role' : ($item->name[0] == '/' ? 'route' : 'permission');
            unset($avaliable[$item->name]);
        }
        unset($avaliable[$this->name]);
        return [
            'avaliable' => $avaliable,
            'assigned' => $assigned
        ];
    }

    /**
     * Get item
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Get type name
     * @param mixed $type
     * @return string|array
     */
    public static function getTypeName($type = null)
    {
        $result = [
            Item::TYPE_PERMISSION => 'Permission',
            Item::TYPE_ROLE => 'Role'
        ];
        if ($type === null) {
            return $result;
        }

        return $result[$type];
    }
}
