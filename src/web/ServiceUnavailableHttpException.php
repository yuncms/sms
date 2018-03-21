<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use yii\web\HttpException;

/**
 * Class ServiceUnavailableHttpException
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ServiceUnavailableHttpException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string|null $message The error message.
     * @param int $code The error code.
     * @param \Exception|null $previous The previous exception used for the exception chaining.
     */
    public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
    {
        parent::__construct(503, $message, $code, $previous);
    }
}