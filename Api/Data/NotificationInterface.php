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

namespace MSP\Notify\Api\Data;

interface NotificationInterface
{

    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';

    public function getMessage();

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @param $event
     * @return $this
     */
    public function setEventId($event);

    /**
     * @param mixed $messageData
     * @return mixed
     */
    public function prepareMessage($messageData);

    /**
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * @return EventInterface
     */
    public function getEventObject();

    /**
     * @return array
     */
    public function getChannelConfiguration();

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEvent($eventName);

    /**
     * @return mixed
     */
    public function send();
}
