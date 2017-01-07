<?php

namespace lo\plugins\dto;
use lo\plugins\models\Event;

/**
 * Class EventDto
 * @package lo\plugins\dto
 */
class EventDto
{
    public $id;
    public $app_id;
    public $trigger_class;
    public $trigger_event;
    public $handler_class;
    public $handler_method;
    public $data;
    public $text;
    public $status = Event::STATUS_ACTIVE;

    /**
     * PluginDataDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
