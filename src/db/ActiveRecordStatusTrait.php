<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db;


trait ActiveRecordStatusTrait
{


    //状态定义
    const STATUS_DRAFT = 0b0;//草稿
    const STATUS_REVIEW = 0b1;//待审核
    const STATUS_REJECTED = 0b10;//拒绝
    const STATUS_PUBLISHED = 0b11;//发布
    const STATUS_ARCHIVED = 0b110;//存档

    /**
     * 是否草稿状态
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status == static::STATUS_DRAFT;
    }

    /**
     * 是否发布状态
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status == static::STATUS_PUBLISHED;
    }
}