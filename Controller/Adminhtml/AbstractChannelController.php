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

namespace MSP\Notify\Controller\Adminhtml;

use Magento\Backend\App\AbstractAction;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use MSP\Notify\Api\ChannelRepositoryInterface;
use MSP\Notify\Api\Data\ChannelInterface;
use MSP\Notify\Api\Data\ChannelInterfaceFactory;
use MSP\Notify\Model\NotificationTemplate;
use MSP\Notify\Model\NotificationTemplateFactory;

abstract class AbstractChannelController extends AbstractAction
{

    const ADMIN_RESOURCE = "MSP_Notify::configuration";

    protected $templateFactory;
    protected $registry;
    protected $adapterlist;
    /**
     * @var ChannelRepositoryInterface
     */
    protected $channelRepository;
    /**
     * @var ChannelInterfaceFactory
     */
    protected $channelFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        ChannelRepositoryInterface $channelRepository,
        ChannelInterfaceFactory $channelFactory
    ) {
    

        $this->registry = $registry;
        $this->channelRepository = $channelRepository;

        parent::__construct($context);
        $this->channelFactory = $channelFactory;
    }

    /**
     * @return ChannelInterface
     */
    protected function getChannel()
    {
        $id = $this->getRequest()->getParam('channel_id');

        if ($id) {
            $channel = $this->channelRepository->getById($id);
        } else {
            $channel = $this->channelFactory->create();
        }
        return $channel;
    }
}
