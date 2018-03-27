<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web\controllers;

use yuncms\web\Controller;

/**
 * 健康检查
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class HealthController extends Controller
{
    /**
     * Ping
     * @return string
     */
    public function actionPing()
    {
        return $this->asRaw('Pong');
    }
}