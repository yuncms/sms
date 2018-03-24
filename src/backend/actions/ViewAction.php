<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\backend\actions;

use yii\web\NotFoundHttpException;
use yuncms\backend\Action;

/**
 * Class ViewAction
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ViewAction extends Action
{
    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);
        return $this->controller->render($this->id, ['model' => $model]);
    }
}