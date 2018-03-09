<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\behaviors;

use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yuncms\base\Snowflake;

/**
 * Class SnowflakeBehavior
 *
 * ```
 * return [
 *      'snowflake'=>[
 *          'class' => 'xutl\snowflake\SnowflakeBehavior',
 *          'attribute' => 'id',
 *      ],
 * ];
 * ```
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SnowflakeBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $attribute = 'id';

    /**
     * @inheritdoc
     */
    public $value;

    /**
     * @var string|Snowflake
     */
    protected $snowflake = 'snowflake';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->attribute],];
        }
        if ($this->attribute === null) {
            throw new InvalidConfigException('Either "attribute" property must be specified.');
        }
        $this->snowflake = Instance::ensure($this->snowflake, 'yuncms\base\Snowflake');
    }

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return $this->snowflake->next();
        }
        return parent::getValue($event);
    }
}