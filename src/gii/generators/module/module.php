<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?= $ns ?>;

use Yii;

/**
 * <?= $generator->moduleID ?> module definition class
 */
class <?= $className ?> extends \yuncms\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';
}
