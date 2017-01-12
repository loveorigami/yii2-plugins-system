<?php
namespace lo\plugins\components;
use yii\base\Event;

class ViewEvent extends Event
{
    /**
     * @var string the content being rendered.
     */
    public $content;
}