<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\trade\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yuncms\trade\models\TradeCharges;
use yuncms\web\Controller;
use yuncms\web\Response;

/**
 * Class PayController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ChargeController extends Controller
{

    /**
     * 创建支付单
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TradeCharges();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/trade/charge/pay', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * WEB付款
     * @param int $id
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPay($id)
    {
        try {
            $charge = $this->findModel($id);
            $paymentParams = [];
            Yii::$app->payment->get($charge->channel)->payment($charge, $paymentParams);
            if (Yii::$app->request->isAjax) {
                return $this->renderPartial('pay', ['charge' => $charge, 'paymentParams' => $paymentParams]);
            } else {
                return $this->render('pay', ['charge' => $charge, 'paymentParams' => $paymentParams]);
            }
        } catch (NotFoundHttpException $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['/trade/charge/create']);
        }
    }

    public function actionView()
    {

    }

    /**
     * 交易查询
     * @param string $id
     * @return TradeCharges
     * @throws NotFoundHttpException
     */
    public function actionQuery($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->findModel($id);
    }

    /**
     * 获取支付单号
     * @param int $id
     * @return TradeCharges
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = TradeCharges::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yuncms', 'The requested trade does not exist.'));
        }
    }
}