<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\backend\actions;

use Yii;
use yuncms\backend\Action;

/**
 * Class CreateAction
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CreateAction extends Action
{

    public function run($id)
    {
        $model = new Admin();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Create success.'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render($this->id, ['model' => $model]);
    }
}