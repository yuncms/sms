<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\widgets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/font-awesome';

    /**
     * @inherit
     */
    public $css = [
        'css/font-awesome.min.css',
    ];

    /**
     * Initializes the bundle.
     * Set publish options to copy only necessary files (in this case css and font folders)
     * @codeCoverageIgnore
     */
    public function init()
    {
        parent::init();
        $this->publishOptions['beforeCopy'] = function ($from, $to) {
            return preg_match('%(/|\\\\)(fonts|css)%', $from);
        };
    }
}