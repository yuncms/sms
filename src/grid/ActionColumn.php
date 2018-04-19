<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\grid;


use Yii;

/**
 * Class ActionColumn
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
        if (empty($this->header)) {
            $this->header = Yii::t('yuncms', 'Operation');
        }
    }

}