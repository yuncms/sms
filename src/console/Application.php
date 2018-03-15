<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\console;

use Yii;
use yii\base\Event;
use yuncms\base\ApplicationTrait;

/**
 * Class Application
 *
 * @package yuncms\console
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Application extends \yii\console\Application
{
    use ApplicationTrait;

    /**
     * @var string the path (or alias) of a PHP file containing MIME type information.
     */
    public static $taskFile = '@vendor/yuncms/tasks.php';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $taskFile = Yii::getAlias(static::$taskFile);
        if (is_file($taskFile)) {
            $tasks = require $taskFile;
            foreach ($tasks as $task) {
                if (isset($task['class'])) {
                    Event::on($task['class'], $task['event'], $task['callback']);
                } else {
                    Event::on($task[0], $task[1], $task[2]);
                }
            }
        }
    }
}