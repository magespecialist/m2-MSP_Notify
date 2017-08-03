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

namespace MSP\Notify\Ui\Component\Form\Event;

use Magento\Ui\DataProvider\AbstractDataProvider;
use MSP\Notify\Model\Channel;
use MSP\Notify\Model\ChannelRepository;
use MSP\Notify\Model\Event;
use MSP\Notify\Model\ResourceModel\Event\CollectionFactory;
use Zend\Json\Json;

class DataProvider extends AbstractDataProvider
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ChannelRepository
     */
    private $channelRepository;

    public function __construct(
        CollectionFactory $collectionFactory,
        ChannelRepository $channelRepository,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
    
        $this->collection = $collectionFactory->create();
        $this->channelRepository = $channelRepository;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $items = $this->collection->getItems();

        /** @var Event $event */
        foreach ($items as $event) {
            $this->loadedData[$event->getId()] = $event->getData();
            $this->loadedData[$event->getId()]['channel'] = $this->channelRepository->getById($event->getChannelId())->getName();
            $this->loadedData[$event->getId()]['linked_events'] = implode(PHP_EOL, $event->getLinkedEvents());
        }

        return $this->loadedData;
    }
}
