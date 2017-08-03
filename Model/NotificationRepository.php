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

namespace MSP\Notify\Model;

use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Api\Data\NotificationInterfaceFactory;
use MSP\Notify\Api\NotificationRepositoryInterface;
use MSP\Notify\Model\ResourceModel\Notification as NorificationResource;

class NotificationRepository implements NotificationRepositoryInterface
{

    private $notificationResource;
    private $notificationFactory;

    public function __construct(
        NotificationInterfaceFactory $notificationInterfaceFactory,
        NorificationResource $notificationResource
    ) {
    
        $this->notificationFactory = $notificationInterfaceFactory;
        $this->notificationResource = $notificationResource;
    }

    /**
     * @param $id
     * @return NotificationInterface
     */
    public function get($id)
    {
        $object = $this->notificationFactory->create();
        return $this->notificationResource->load($object, $id);
    }

    /**
     * @param NotificationInterface $notification
     */
    public function save(NotificationInterface $notification)
    {
        $this->notificationResource->save($notification);
    }
}
