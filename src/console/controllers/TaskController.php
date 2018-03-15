<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\console\controllers;

use Yii;
use yii\base\Event;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class CronController
 *
 * 0/5 * * * * /path/to/yii cron/minute >/dev/null 2>&1
 * 30 * * * * /path/to/yii cron/hourly >/dev/null 2>&1
 * 00 18 * * * /path/to/yii cron/daily >/dev/null 2>&1
 * 00 00 15 * * /path/to/yii cron/month >/dev/null 2>&1
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TaskController extends Controller
{
    /**
     * @event Event 每分钟触发事件
     */
    const EVENT_ON_MINUTE_RUN = "minute";

    /**
     * @event Event 每小时触发事件
     */
    const EVENT_ON_HOURLY_RUN = "hourly";

    /**
     * @event Event 每天触发事件
     */
    const EVENT_ON_DAILY_RUN = "daily";

    /**
     * @event Event 每月触发事件
     */
    const EVENT_ON_MONTH_RUN = "month";

    /**
     * @var string
     */
    protected $dateTime;

    /**
     * @var string 任务配置文件
     */
    public $taskFile = '@vendor/yuncms/tasks.php';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->dateTime = Yii::$app->formatter->asDatetime(time());
        $this->taskFile = Yii::getAlias($this->taskFile);
    }

    /**
     * 初始化并注册计划任务
     */
    public function initTask()
    {
        if (is_file($this->taskFile)) {
            $tasks = require $this->taskFile;
            foreach ($tasks as $task) {
                if (isset($task['class'])) {
                    Event::on($task['class'], $task['event'], $task['callback']);
                } else {
                    Event::on($task[0], $task[1], $task[2]);
                }
            }
        }
    }

    /**
     * Show task configuration.
     */
    public function actionShow()
    {
        if (is_file($this->taskFile)) {
            $tasks = require $this->taskFile;
            print_r($tasks);
        } else {
            $this->stdout("Task configuration file does not exist." . PHP_EOL, Console::FG_YELLOW);
        }
    }

    /**
     * Executes minute cron tasks.
     * @return int
     */
    public function actionMinute()
    {
        $this->stdout($this->dateTime . " Executing minute tasks." . PHP_EOL, Console::FG_YELLOW);
        $this->trigger(self::EVENT_ON_MINUTE_RUN);
        return ExitCode::OK;
    }

    /**
     * Executes hourly cron tasks.
     * @return int
     */
    public function actionHourly()
    {
        $this->stdout($this->dateTime . " Executing hourly tasks." . PHP_EOL, Console::FG_YELLOW);
        $this->trigger(self::EVENT_ON_HOURLY_RUN);
        return ExitCode::OK;
    }

    /**
     * Executes daily cron tasks.
     * @return int
     */
    public function actionDaily()
    {
        $this->stdout($this->dateTime . " Executing daily tasks." . PHP_EOL, Console::FG_YELLOW);
        $this->trigger(self::EVENT_ON_DAILY_RUN);
        return ExitCode::OK;
    }

    /**
     * Executes month cron tasks.
     * @return int
     */
    public function actionMonth()
    {
        $this->stdout($this->dateTime . " Executing month tasks." . PHP_EOL, Console::FG_YELLOW);
        $this->trigger(self::EVENT_ON_MONTH_RUN);
        return ExitCode::OK;
    }
}