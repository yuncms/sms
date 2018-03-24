<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\backend;

use yii\filters\VerbFilter;
use yuncms\base\Model;

/**
 * Class ActiveController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActiveController extends Controller
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

    /**
     * @var string the scenario used for updating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $updateScenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string the scenario used for creating a model.
     * @see \yii\base\Model::scenarios()
     */
    public $createScenario = Model::SCENARIO_DEFAULT;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'batch-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yuncms\backend\IndexAction',
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => 'yuncms\backend\ViewAction',
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => 'yuncms\backend\CreateAction',
                'modelClass' => $this->modelClass,
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yuncms\backend\UpdateAction',
                'modelClass' => $this->modelClass,
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yuncms\backend\DeleteAction',
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}