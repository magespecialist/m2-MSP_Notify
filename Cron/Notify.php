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
namespace MSP\Notify\Cron;

use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Base\Config;
use MSP\Notify\Model\ResourceModel\Notification\CollectionFactory;

class Notify
{
    private $collectionFactory;
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        CollectionFactory $collectionFactory,
        Config $config
    ) {
    
        $this->collectionFactory = $collectionFactory;
        $this->config = $config;
    }

    public function execute()
    {
        if (!$this->config->isEnabledGlobally()) {
            return true;
        }

        $collection = $this->collectionFactory->create();

        $collection->addFieldToFilter('status', NotificationInterface::STATUS_PENDING);

        foreach ($collection as $notification) {
        /** @var $notification NotificationInterface */
            $notification->send();
        }

        return true;
    }
}
