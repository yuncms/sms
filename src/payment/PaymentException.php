<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment;

use yii\base\Exception;

/**
 * Class PaymentException
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class PaymentException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Payment Exception';
    }
}