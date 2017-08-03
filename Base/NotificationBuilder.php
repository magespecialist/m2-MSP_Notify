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

namespace MSP\Notify\Base;

use MSP\Notify\Api\Data\EventInterface;
use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Model\NotificationFactory;

class NotificationBuilder
{

    /**
     * @var $event EventInterface
     */
    protected $event;

    protected $eventName;


    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    public function __construct(
        NotificationFactory $notificationFactory
    ) {
    
        $this->notificationFactory = $notificationFactory;
    }

    /**
     * @param EventInterface $event
     * @return $this
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;

        return $this;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return NotificationInterface
     */
    public function build()
    {
        /** @var NotificationInterface $notification */
        $notification = $this->notificationFactory->create();


        $notification
            ->setEvent($this->eventName)
            ->setEventId($this->event->getId())
            ->setStatus(NotificationInterface::STATUS_PENDING);

        return $notification;
    }
}
