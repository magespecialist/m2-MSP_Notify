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

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Api\Data\NotificationInterfaceFactory;
use MSP\Notify\Api\Data\NotificationTemplateInterface;
use MSP\Notify\Model\ResourceModel\NotificationTemplate\Collection;
use Zend\Json\Json;

class NotificationTemplate extends AbstractModel implements NotificationTemplateInterface
{

    protected $_eventPrefix = 'msp_notification_template_';

    protected $notificationfactory;

    public function __construct(
        Context $context,
        Registry $registry,
        \MSP\Notify\Model\ResourceModel\NotificationTemplate $resource,
        Collection $resourceCollection,
        NotificationInterfaceFactory $notificationInterfaceFactory,
        array $data = []
    ) {

        $this->notificationfactory = $notificationInterfaceFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('MSP\Notify\Model\ResourceModel\NotificationTemplate');
    }

//    /**
//     * @return array
//     */
//    public function getEvents()
//    {
//        return explode(',', $this->getData('events'));
//    }

//    /**
//     * @param string|array $events
//     * @return $this
//     */
//    public function setEvents($events)
//    {
//        if (is_array($events)) {
//            $this->setData('events', $events);
//        } else {
//            $events = explode("\n\r", $events);
//            $this->setData('events', implode(',', $events));
//        }
//
//        return $this;
//    }

    public function serializeAdapterConfiguration(array $configuration)
    {
        return Json::encode($configuration);
    }

    /**
     * @return array
     */
    public function unserializeAdapterConfiguration()
    {
        return Json::decode($this->getData('adapter_configuration'));
    }

    /**
     * @return NotificationInterface
     */
    public function toNotification()
    {
        $notification = $this->notificationfactory->create();
        $notification
            ->setTemplateId($this->getId())
            ->setAdapterConfiguration($this->getData('adapter_configuration'))
            ->setStatus(NotificationInterface::STATUS_PENDING)
            ->setAdapterCode($this->getAdapterCode());

        return $notification;
    }
}
