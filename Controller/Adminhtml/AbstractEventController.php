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
use MSP\Notify\Api\Data\EventInterface;
use MSP\Notify\Api\EventRepositoryInterface;
use MSP\Notify\Model\EventFactory;

abstract class AbstractEventController extends AbstractAction
{

    const ADMIN_RESOURCE = "MSP_Notify::configuration";

    protected $registry;
    /**
     * @var EventRepositoryInterface
     */
    protected $eventRepository;
    /**
     * @var EventFactory
     */
    protected $eventFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        EventRepositoryInterface $eventRepository,
        EventFactory $eventFactory
    ) {
    

        $this->registry = $registry;
        $this->eventRepository = $eventRepository;
        $this->eventFactory = $eventFactory;

        parent::__construct($context);
    }

    /**
     * @return EventInterface
     */
    protected function getEvent()
    {
        $id = $this->getRequest()->getParam('event_id');

        if ($id) {
            $channel = $this->eventRepository->getById($id);
        } else {
            $channel = $this->eventFactory->create();
        }
        return $channel;
    }
}
