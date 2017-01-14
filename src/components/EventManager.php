<?php
/**
 * EventManager class file.
 * @copyright (c) 2013, Galament
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace lo\plugins\components;

use yii\base\Component;
use yii\base\Event;

/**
 * Attaches events to all app models.
 *
 */
class EventManager extends Component
{
    /**
     * System wide models events settings -
     * an array with structure: [
     *      $eventSenderClassName => [
     *          $eventName => [
     *              [$handlerClassName, $handlerMethodName]
     *          ]
     *      ]
     * ]
     *
     * @since 1.3.0 handler can also keep additional data and $append boolean as for Event::on() method eg:
     *  ... [[$handlerClassName, $handlerMethodName], ['myData'], false]
     *
     * @var array events settings
     */
    public $events = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
       // var_dump($this->events);
        $this->attachEvents($this->events);
    }

    /**
     * attaches all events to all classNames
     * @param array $eventConfig commonly $this->events config
     */
    public function attachEvents($eventConfig)
    {
        foreach ($eventConfig as $className => $events) {
            foreach ($events as $eventName => $handlers) {
                foreach ($handlers as $handler) {
                    if (is_array($handler) && is_callable($handler[0])) {
                        $data = isset($handler[1]) ? array_pop($handler) : null;
                        $append = isset($handler[2]) ? array_pop($handler) : null;
                        Event::on($className, $eventName, $handler[0], $data, $append);
                    } else if (is_callable($handler)) {
                        Event::on($className, $eventName, $handler);
                    }
                }
            }
        }
    }
}