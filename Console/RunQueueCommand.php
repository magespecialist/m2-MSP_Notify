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
use MSP\Notify\Cron\Notify;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunQueueCommand extends Command
{

    const TEST_EVENT = 'msp_notify_test_dispatch';

    protected $notificationFactory;
    protected $notificator;
    protected $state;
    /**
     * @var Notify
     */
    private $cron;

    public function __construct(
        State $state,
        Notify $cron,
        $name = null
    ) {
    
        $this->state = $state;
        $this->cron = $cron;

        parent::__construct($name);
    }

    public function configure()
    {
        $this->setName('msp:notify:run')
            ->setDescription('Process notification queue');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->getAreaCode();
        } catch (\Exception $e) {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
        }

        $this->cron->execute();
    }
}
