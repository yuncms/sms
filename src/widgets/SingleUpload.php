<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

/**
 * Class SingleUpload
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SingleUpload extends MultipleUpload
{
    /**
     * @var bool 关闭多文件上传
     */
    public $multiple = false;

    /**
     * @var int 最大
     */
    public $maxFileSize = 0;

    /**
     * @var int 最大上传文件数量
     */
    public $maxNumberOfFiles = 1;
}