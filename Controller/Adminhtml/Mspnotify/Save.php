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
namespace MSP\Notify\Controller\Adminhtml\Mspnotify;

use MSP\Notify\Api\AdapterInterface;
use MSP\Notify\Controller\Adminhtml\AbstractController;

class Save extends AbstractController
{

    public function execute()
    {
        $model = $this->getTemplate();

        $data = $this->getRequest()->getPostValue();
        $adapterConfig = $data[$data['adapter_code']];

        /** @var AdapterInterface $adapter */
        foreach ($this->adapterlist->getList() as $adapter) {
            unset($data[$adapter->getAdapterCode()]);
        }

        $data['adapter_configuration'] = $model->serializeAdapterConfiguration($adapterConfig);

        if (!$data) {
            $this->_redirect('*/*/index');
            return null;
        }

        if ($model->isObjectNew()) {
            unset($data['msp_notify_template_id']);
        }

        $model->setData($data);

        $result = $model->getResource()->save($model);

        if ($result) {
            $this->getMessageManager()->addSuccessMessage(__('Notification saved'));
        } else {
            $this->getMessageManager()->addErrorMessage(__("Can't save"));
        }

        $this->_redirect("*/*/index");
    }
}
