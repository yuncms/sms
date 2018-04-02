<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\trade\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yuncms\trade\models\TradeRefunds;
use yuncms\web\Controller;

/**
 * Class RefundController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class RefundController extends Controller
{
    /**
     * 创建退款单
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TradeRefunds();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/trade/refund/view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * 查看退款详情
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * 获取退款单号
     * @param int $id
     * @return TradeRefunds
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = TradeRefunds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yuncms', 'The requested refund does not exist.'));
        }
    }
}