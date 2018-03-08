<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\payment;

use yuncms\payment\contracts\GatewayInterface;
use yuncms\payment\traits\GatewayTrait;

/**
 * Class Gateway
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Gateway implements GatewayInterface
{
    use GatewayTrait;


}