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

namespace MSP\Notify\Ui\Component\Listing\Event;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use MSP\Notify\Api\ChannelRepositoryInterface;

class Channel extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ChannelRepositoryInterface $channelRepository,
        array $components = [],
        array $data = []
    ) {
    
        $this->channelRepository = $channelRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $channelId = $item['channel_id'];
                $name = $this->getData('name');

                try {
                    $channel = $this->channelRepository->getById($channelId);
                    $item[$name] = $channel->getName();
                } catch (NoSuchEntityException $e) {
                    $item[$name] = "Channel not found";
                }
            }
        }

        return $dataSource;
    }
}
