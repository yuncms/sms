<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

use yii\base\BaseObject;
use yuncms\notifications\contracts\ChannelInterface;

/**
 * Class BaseMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BaseMessage extends BaseObject
{
    /**
     * @var array custom module parameters (name => value).
     */
    public $params = [];

    /**
     * The title of the notification.
     * @var string
     */
    protected $title;

    /**
     * @var string The notification's message body
     */
    protected $content;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Return message content.
     *
     * @param ChannelInterface $channel
     *
     * @return string
     */
    public function getTitle(ChannelInterface $channel = null)
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Return message content.
     *
     * @param ChannelInterface $channel
     *
     * @return string
     */
    public function getContent(ChannelInterface $channel = null)
    {
        return $this->content;
    }

    /**
     * Return the template id of message.
     *
     * @param ChannelInterface $channel
     *
     * @return string
     */
    public function getTemplate(ChannelInterface $channel = null)
    {
        return $this->template;
    }

    /**
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param mixed $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return array
     */
    public function getData(ChannelInterface $channel = null)
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}