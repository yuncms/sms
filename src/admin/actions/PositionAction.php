<?php
namespace yuncms\admin\actions;

use Yii;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * Position action switches custom sorting position of the existing record.
 * This action supports [yii2tech/ar-position](https://github.com/yii2tech/ar-position) extension.
 *
 * @see https://github.com/yii2tech/ar-position
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 1.0
 */
class PositionAction extends Action
{
    /**
     * @var string name of the query param, which is used for new model position specification.
     */
    public $positionParam = 'at';


    /**
     * Updates existing record specified by id.
     * @param mixed $id id of the model to be deleted.
     * @return mixed response.
     * @throws BadRequestHttpException on invalid request.
     * @throws MethodNotAllowedHttpException on invalid request.
     * @throws \yii\base\InvalidConfigException
     */
    public function run($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException('Method Not Allowed. This url can only handle post');
        }
        $position = Yii::$app->request->getQueryParam($this->positionParam, null);
        if (empty($position)) {
            throw new BadRequestHttpException(Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->positionParam]));
        }

        $model = $this->findModel($id);

        $this->positionModel($model, $position);

        return $this->respondSuccess($model);
    }

    /**
     * @param \yii\db\ActiveRecordInterface|\yii2tech\ar\position\PositionBehavior $model
     * @param $position
     * @throws BadRequestHttpException
     */
    protected function positionModel($model, $position)
    {
        switch (strtolower($position)) {
            case 'up':
            case 'prev':
                $model->movePrev();
                break;
            case 'down':
            case 'next':
                $model->moveNext();
                break;
            case 'top':
            case 'first':
                $model->moveFirst();
                break;
            case 'bottom':
            case 'last':
                $model->moveLast();
                break;
            default:
                if (is_numeric($position)) {
                    $model->moveToPosition($position);
                } else {
                    throw new BadRequestHttpException(Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->positionParam]));
                }
        }
    }

    /**
     * Composes success response.
     * @param \yii\db\ActiveRecordInterface $model
     * @return mixed response.
     */
    protected function respondSuccess($model)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'success' => true
            ];
        }

        return $this->controller->redirect($this->createReturnUrl('view', $model));
    }

    /**
     * @inheritdoc
     */
    public function createReturnUrl($defaultActionId = 'index', $model = null)
    {
        if ($this->returnUrl !== null) {
            return parent::createReturnUrl($defaultActionId, $model);
        }

        $url = parent::createReturnUrl($defaultActionId, $model);
        unset($url[$this->positionParam]);
        return $url;
    }
}