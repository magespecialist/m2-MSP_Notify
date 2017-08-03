<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model;

use Magento\Framework\Model\AbstractModel;
use MSP\Notify\Api\ChannelRepositoryInterface;
use MSP\Notify\Api\Data\ChannelInterface;
use Zend\Json\Json;

class Event extends AbstractModel implements \MSP\Notify\Api\Data\EventInterface
{
    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ChannelRepositoryInterface $channelRepository,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
    
        $this->channelRepository = $channelRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('\MSP\Notify\Model\ResourceModel\Event');
    }

    public function getId()
    {
        return $this->getData(\MSP\Notify\Api\Data\EventInterface::ID);
    }

    public function getLinkedEvents()
    {
        $data = $this->getData(\MSP\Notify\Api\Data\EventInterface::LINKED_EVENTS);

        if (!is_array($data)) {
            $this->setData(\MSP\Notify\Api\Data\EventInterface::LINKED_EVENTS, explode(',', $data));
        }
        return $this->_getData(\MSP\Notify\Api\Data\EventInterface::LINKED_EVENTS);
    }

    public function getChannelId()
    {
        return $this->getData(\MSP\Notify\Api\Data\EventInterface::CHANNEL_ID);
    }

    public function setId($value)
    {
        $this->setData(\MSP\Notify\Api\Data\EventInterface::ID, $value);
        return $this;
    }

    public function setLinkedEvents($value)
    {
        $this->setData(\MSP\Notify\Api\Data\EventInterface::LINKED_EVENTS, Json::encode($value));
        return $this;
    }

    public function setChannelId($value)
    {
        $this->setData(\MSP\Notify\Api\Data\EventInterface::CHANNEL_ID, $value);
        return $this;
    }

    /**
     * get Channel Instance
     * @return ChannelInterface
     */
    public function getChannel()
    {
        $channel = $this->channelRepository->getById($this->getChannelId());

        return $channel;
    }
}
