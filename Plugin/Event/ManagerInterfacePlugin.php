<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace MSP\Notify\Plugin\Event;

use Closure;
use Magento\Framework\Event\ManagerInterface;
use MSP\Notify\Api\EventRepositoryInterface;
use MSP\Notify\Api\NotificatorInterface;
use MSP\Notify\Base\Config;
use MSP\Notify\Base\NotificationBuilder;

class ManagerInterfacePlugin
{

    private $notificator;
    private $config;

    private $skippedEvents = [
        '_load_before',
        '_load_after',
    ];

    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;
    /**
     * @var NotificationBuilder
     */
    private $notificationBuilder;


    public function __construct(
        NotificatorInterface $notificator,
        Config $config,
        EventRepositoryInterface $eventRepository,
        NotificationBuilder $notificationBuilder
    ) {

        $this->notificator = $notificator;
        $this->config = $config;
        $this->eventRepository = $eventRepository;
        $this->notificationBuilder = $notificationBuilder;
    }

    protected function isValidEvent($eventName)
    {
        foreach ($this->skippedEvents as $event) {
            if (preg_match("/" . $event . "/", $eventName)) {
                return false;
            }
        }

        return true;
    }


    public function aroundDispatch(
        ManagerInterface $subject,
        Closure $procede,
        $eventName,
        array $data = []
    ) {

        $res = $procede($eventName, $data);

        if (!$this->isValidEvent($eventName) || !$this->config->isEnabledGlobally()) {
            return $res;
        }

        $events = $this->getEventsList($eventName);

        foreach ($events as $event) {
            /**@var \MSP\Notify\Api\Data\EventInterface $event **/

            if (!in_array($eventName, $event->getLinkedEvents())) {
                return $res;
            }

            $notification = $this->notificationBuilder
                ->setEvent($event)
                ->setEventName($eventName)
                ->build();

            $notification->prepareMessage($data);
            $this->notificator->enqueue($notification);
        }

        return $res;
    }

    protected function getEventsList($eventName)
    {
        return $this->eventRepository->getByEvent($eventName);
    }
}
