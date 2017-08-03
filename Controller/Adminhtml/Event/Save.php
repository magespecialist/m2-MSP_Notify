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

class Save extends AbstractEventController
{

    public function execute()
    {
        $event = $this->getEvent();
        $data = $this->getRequest()->getPost();

        try {
            $data['linked_events'] = preg_split("/(\\r\\n)+/", $data['linked_events']);

            if (!$event->getId()) {
                unset($data['event_id']);
            }

            $event->setData((array)$data);

            $this->eventRepository->save($event);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("Can't save event: %1", $e->getMessage()));
            $this->_redirect("*/*/edit", ['id' => $this->getRequest()->getParam('id')]);
        }
        $this->messageManager->addSuccessMessage(__('Event has been saved'));
        $this->_redirect('*/*/index');
    }
}
