<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\grid;


use Yii;
use yuncms\helpers\Html;

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

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open', [
            'class' => 'btn btn-sm btn-primary',
        ]);
        $this->initDefaultButton('update', 'pencil', [
            'class' => 'btn btn-sm btn-warning',
        ]);
        $this->initDefaultButton('delete', 'trash', [
            'class' => 'btn btn-sm btn-danger',
            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }
}