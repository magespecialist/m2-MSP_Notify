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

namespace MSP\Notify\Controller\Adminhtml\Event;

use MSP\Notify\Controller\Adminhtml\AbstractEventController;

class Delete extends AbstractEventController
{

    public function execute()
    {
        $event = $this->getEvent();

        try {
            $this->eventRepository->delete($event);
            $this->messageManager->addSuccessMessage(__("Event %1 has been deleted", $event->getName()));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage("Can't delete event");
        }
        finally {
            $this->_redirect('*/*/index');
        }
    }
}
