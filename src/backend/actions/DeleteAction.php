<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\backend\actions;

use Yii;
use yii\web\ServerErrorHttpException;
use yuncms\backend\Action;

class DeleteAction extends Action
{
    /** @var  string|array 编辑成功后跳转地址,此参数直接传给yii::$app->controller->redirect() */
    public $successRedirect = ['index'];

    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        if ($model->delete() === false) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Failed to delete the object for unknown reason.'));
        } else {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Delete success.'));
            return $this->controller->redirect($this->successRedirect);
        }
    }
}