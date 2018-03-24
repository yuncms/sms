<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rbac;

/**
 * Class RbacManager
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DbManager extends \yii\rbac\DbManager
{
    /**
     * @var int 缓存有效期
     */
    public $cacheDuration = 3600;

    /**
     * @var string cache tag
     */
    public $cacheTag = 'rbac';

    /**
     * @var boolean If true then AccessControl only check if route are registered.
     */
    public $onlyRegisteredRoute = false;

    /**
     * @var boolean If false then AccessControl will check without Rule.
     */
    public $strict = true;

    /**
     * Memory cache of assignments
     * @var array
     */
    private $_assignments = [];

    private $_childrenList;

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (!isset($this->_assignments[$userId])) {
            $this->_assignments[$userId] = parent::getAssignments($userId);
        }
        return $this->_assignments[$userId];
    }

    /**
     * @inheritdoc
     */
    protected function getChildrenList()
    {
        if ($this->_childrenList === null) {
            $this->_childrenList = parent::getChildrenList();
        }
        return $this->_childrenList;
    }
}