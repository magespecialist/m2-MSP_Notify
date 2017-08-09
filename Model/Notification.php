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

use Magento\Framework\Config\Reader\Filesystem;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use MSP\Notify\Api\AdapterRepositoryInterface;
use MSP\Notify\Api\ChannelRepositoryInterface;
use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Api\EventRepositoryInterface;
use MSP\Notify\Block\Notification\TemplateFactory;
use MSP\Notify\Model\ResourceModel\Notification\Collection;

class Notification extends AbstractModel implements NotificationInterface
{

    protected $adapterRepository;
    protected $templateFactory;
    protected $reader;
    /**
     * @var EventRepositoryInterface
     */
    private $eventRepository;
    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        \MSP\Notify\Model\ResourceModel\Notification $resource,
        Collection $resourceCollection,
        AdapterRepositoryInterface $adapterRepository,
        EventRepositoryInterface $eventRepository,
        ChannelRepositoryInterface $channelRepository,
        TemplateFactory $templateFactory,
        Filesystem $reader,
        array $data = []
    ) {
    
        $this->adapterRepository = $adapterRepository;
        $this->templateFactory = $templateFactory;
        $this->reader = $reader;
        $this->eventRepository = $eventRepository;
        $this->channelRepository = $channelRepository;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected $_eventPrefix = 'msp_notification_';

    protected function _construct()
    {
        $this->_init('MSP\Notify\Model\ResourceModel\Notification');
    }


    public function getMessage()
    {
        return $this->getData('message');
    }

    public function send()
    {
        if (!$this->getIsTest()) {
            $this->getResource()->save($this);
        }

        $adapterCode = $this->getAdapterCode();

        if (!$adapterCode) {
            return;
        }

        $adapter = $this->adapterRepository->get($adapterCode);

        $result = $adapter->notify($this);

        if ($result) {
            $this->setStatus(static::STATUS_COMPLETE);
        } else {
            $this->setStatus(static::STATUS_FAILED);
        }

        $this->setExecutedAt('now');
        if (!$this->getIsTest()) {
            $this->getResource()->save($this);
        }
    }

    public function prepareMessage($object = null)
    {
        $template = $this->getTemplateDataFromConfig();
        $block = $this->templateFactory->create();
        $block->setTemplate($template['file']);
        $block->setObject($object);
        $block->setEvent($this->getEvent());
        $this->setData('message', $block->toHtml());
    }

    protected function getTemplateDataFromConfig()
    {
        $template = $this->reader->read();

        if (isset($template[$this->getEvent()])) {
            return $template[$this->getEvent()];
        } else {
            return $template['default'];
        }
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->setData('status', $status);

        return $this;
    }

    /**
     * @param $event
     * @return $this
     */
    public function setEventId($event)
    {
        $this->setData('event_id', $event);

        return $this;
    }

    /**
     * @return bool|string
     */
    protected function getAdapterCode()
    {
        try {
            $channel = $this->getChannel();
            $code = $channel->getAdapterCode();
        } catch (NoSuchEntityException $e) {
            $code = false;
        }

        return $code;
    }

    /**
     * @return \MSP\Notify\Api\Data\EventInterface
     */
    public function getEventObject()
    {
        return $this->eventRepository->getById($this->getEventId());
    }

    /**
     * @return \MSP\Notify\Api\Data\ChannelInterface
     */
    public function getChannel()
    {
        $event = $this->getEventObject();
        $channel = $this->channelRepository->getById($event->getChannelId());

        return $channel;
    }

    public function getChannelConfiguration()
    {
        $channel = $this->getChannel();

        $config = $channel->getAdapterConfiguration();

        return $config[$channel->getAdapterCode()];
    }

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEvent($eventName)
    {
        $this->setData('event', $eventName);
        return $this;
    }
}
