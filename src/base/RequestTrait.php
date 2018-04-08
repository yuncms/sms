<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

/**
 * Trait RequestTrait
 *
 * @property \yuncms\web\Request|\yuncms\console\Request $this
 * @package yuncms\base
 */
trait RequestTrait
{
    /**
     * Returns the requested script name being used to access Craft (e.g. “index.php”).
     *
     * @return string
     */
    public function getScriptFilename(): string
    {
        return basename($this->getScriptFile());
    }
}