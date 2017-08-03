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

namespace MSP\Notify\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use MSP\Notify\Api\ChannelRepositoryInterface;
use MSP\Notify\Api\Data\ChannelInterface;

class Channel extends AbstractSource
{

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
    

        $this->channelRepository = $channelRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $result = [];

        $order = $this->sortOrderBuilder
           ->setField('name')
           ->setDirection(SortOrder::SORT_ASC)
           ->create();

        $criteria = $this->searchCriteriaBuilder
           ->setSortOrders([$order])
           ->create();

        $channels = $this->channelRepository->getList($criteria);

       /** @var ChannelInterface $channel */
        foreach ($channels->getItems() as $channel) {
            $result[] = [
               'label' => $channel->getName(),
               'value' => $channel->getId()
            ];
        }

        return $result;
    }
}
