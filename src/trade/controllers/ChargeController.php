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

/**
 * Class PayController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ChargeController extends Controller
{
    public function actionCreate()
    {

    }


    public function actionView()
    {

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