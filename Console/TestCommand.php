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

namespace MSP\Notify\Console;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use MSP\Notify\Api\Data\NotificationInterfaceFactory;
use MSP\Notify\Api\NotificatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{

    const TEST_EVENT = 'msp_notify_test_dispatch';

    protected $notificationFactory;
    protected $notificator;
    protected $state;

    public function __construct(
        NotificationInterfaceFactory $notificationInterfaceFactory,
        NotificatorInterface $notificator,
        State $state,
        $name = null
    ) {
    
        $this->notificationFactory = $notificationInterfaceFactory;
        $this->notificator = $notificator;
        $this->state = $state;

        parent::__construct($name);
    }

    public function configure()
    {
        $this->setName('msp:notify:enqueue')
            ->setDescription('Send a test notification')
            ->addOption('adapter', 'a', InputOption::VALUE_REQUIRED, 'Specify an adapter by code')
            ->addOption('adapter-configuration', null, InputOption::VALUE_REQUIRED, 'Adapter configuration in JSON format');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (\Exception $e) {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        }

        $notification = $this->notificationFactory->create();
        $notification->setEvent(static::TEST_EVENT);
        $notification->setAdapterConfiguration($input->getOption('adapter-configuration'));
        $notification->setAdapterCode($input->getOption('adapter'));
        $notification->prepareMessage();
        $notification->setIsTest(true);

        $notification->send();
    }
}
