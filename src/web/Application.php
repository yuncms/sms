<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use Yii;
use yii\web\Cookie;
use yuncms\base\ApplicationTrait;

/**
 * Class Application
 * @property Request $request The request component
 * @property Response $response The response component
 * @property User $user The user component
 * @package yuncms\web
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class Application extends \yii\web\Application
{
    use ApplicationTrait;

    /**
     * @inheritdoc
     */
    protected function bootstrap()
    {
        parent::bootstrap();
        //$this->setLanguage();
        //$this->catchOffline();
    }

    /**
     * 检查离线
     */
    public function catchOffline()
    {
        if (Yii::$app->settings->get('close', 'system')) {
            $this->catchAll = [
                'site/offline',
                'reason' => Yii::$app->settings->get('closeReason', 'system')
            ];
        }
    }

    /**
     * set language
     * @param null $language
     * @return void
     */
    public function setLanguage($language = null)
    {
        if (is_null($language)) {
            if (($language = Yii::$app->request->getQueryParam('_lang')) !== null) {
                $this->language = $language;
                Yii::$app->response->cookies->add(new Cookie(['name' => '_lang', 'value' => $language]));
            } else if (($cookie = Yii::$app->request->cookies->get('_lang')) !== null) {
                $this->language = $cookie->value;
            } else if (($language = Yii::$app->request->getPreferredLanguage()) !== null) {
                $this->language = $language;
            }
        } else {
            $this->language = $language;
        }
    }

    /**
     * @inheritdoc
     */
    public function setVendorPath($path)
    {
        parent::setVendorPath($path);

        // Override the @bower and @npm aliases if using asset-packagist.org
        // todo: remove this whenever Yii is updated with support for asset-packagist.org
        $altBowerPath = $this->getVendorPath() . DIRECTORY_SEPARATOR . 'bower-asset';
        $altNpmPath = $this->getVendorPath() . DIRECTORY_SEPARATOR . 'npm-asset';
        if (is_dir($altBowerPath)) {
            Yii::setAlias('@bower', $altBowerPath);
        }
        if (is_dir($altNpmPath)) {
            Yii::setAlias('@npm', $altNpmPath);
        }

        // Override where Yii should find its asset deps
        Yii::setAlias('@bower/bootstrap/dist', $this->getVendorPath() . '/yuncms/framework/resources/lib/bootstrap');
        Yii::setAlias('@bower/jquery/dist', $this->getVendorPath() . '/yuncms/framework/resources/lib/jquery');
        Yii::setAlias('@bower/inputmask/dist', $this->getVendorPath() . '/yuncms/framework/resources/lib/inputmask');
        Yii::setAlias('@bower/punycode', $this->getVendorPath() . '/yuncms/framework/resources/lib/punycode');
        Yii::setAlias('@bower/yii2-pjax', $this->getVendorPath() . '/yuncms/framework/resources/lib/yii2-pjax');
        Yii::setAlias('@bower/font-awesome', $this->getVendorPath() . '/yuncms/framework/resources/lib/font-awesome');
    }
}