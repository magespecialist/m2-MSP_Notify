<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use MSP\Notify\Api\AdapterInterface;
use MSP\Notify\Api\AdapterRepositoryInterface;
use MSP\Notify\Api\Data\ChannelInterface;

class Channel extends AbstractModel implements \MSP\Notify\Api\Data\ChannelInterface
{

    /**
     * @var AdapterRepositoryInterface
     */
    private $adapterRepository;

    public function __construct(
        Context $context,
        Registry $registry,
        AdapterRepositoryInterface $adapterRepository,
        AbstractModel $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {

        $this->adapterRepository = $adapterRepository;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('\MSP\Notify\Model\ResourceModel\Channel');
    }

    public function getId()
    {
        return $this->getData(\MSP\Notify\Api\Data\ChannelInterface::ID);
    }

    public function getAdapterCode()
    {
        return $this->getData(\MSP\Notify\Api\Data\ChannelInterface::ADAPTER_CODE);
    }

    public function getAdapterConfiguration()
    {
        return $this->getData(\MSP\Notify\Api\Data\ChannelInterface::ADAPTER_CONFIGURATION);
    }

    public function setId($value)
    {
        $this->setData(\MSP\Notify\Api\Data\ChannelInterface::ID, $value);
        return $this;
    }

    public function setAdapterCode($value)
    {
        $this->setData(\MSP\Notify\Api\Data\ChannelInterface::ADAPTER_CODE, $value);
        return $this;
    }

    public function setAdapterConfiguration($value)
    {
        $this->setData(\MSP\Notify\Api\Data\ChannelInterface::ADAPTER_CONFIGURATION, $value);
        return $this;
    }

    /**
     * Get adapter instance
     * @return AdapterInterface
     */
    public function getAdapterInstance()
    {
        $adapter = $this->adapterRepository->get($this->getAdapterCode());

        return $adapter;
    }

    /**
     * Get channel name
     * @return string
     */
    public function getName()
    {
        return $this->getData(ChannelInterface::NAME);
    }

    /**
     * Set channel name
     * @param string $value
     * @return $this
     */
    public function setName($value)
    {
        $this->setData(ChannelInterface::NAME, $value);

        return $this;
    }
}
