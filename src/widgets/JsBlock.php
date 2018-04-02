<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use yii\base\Exception;
use yii\web\View;
use yii\widgets\Block;

/**
 * <?php \yuncms\widgets\JsBlock::begin() ?>
 * <script>
 *      $(function(){
 *          jQuery(".company_introduce").slide({mainCell:".bd ul",effect:"left",autoPlay:true,mouseOverStop:true});
 *      });
 * </script>
 * <?php \yuncms\widgets\JsBlock::end()?>
 */
class JsBlock extends Block
{

    /**
     * @var null
     */
    public $key = null;

    /**
     * @var int
     */
    public $pos = View::POS_READY;

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     * @throws Exception
     */
    public function run()
    {
        $block = ob_get_clean();
        if ($this->renderInPlace) {
            throw new Exception("not implemented yet ! ");
        }
        $block = trim($block);
        $jsBlockPattern = '|^<script[^>]*>(?P<blockContent>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, $block, $matches)) {
            $block = $matches['blockContent'];
        }
        $this->view->registerJs($block, $this->pos, $this->key);
    }

    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     */
    public static function widget($config = [])
    {
        try {
            return parent::widget($config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}