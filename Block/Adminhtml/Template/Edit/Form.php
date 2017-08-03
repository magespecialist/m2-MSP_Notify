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

namespace MSP\Notify\Block\Adminhtml\Template\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use MSP\Notify\Base\AdapterList;

class Form extends Generic
{

    protected $adapterlist;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        AdapterList $adapterList,
        array $data
    ) {
    

        $this->adapterlist = $adapterList;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Generic information')]);
        $model = $this->_coreRegistry->registry('current_msp_notify_template');


        $fieldset->addField(
            'msp_notify_template_id',
            'hidden',
            [
                'name' => 'msp_notify_template_id',
                'value' => $model->getId()
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Template Name'),
                'id' => 'name',
                'title' => __('Template Name'),
                'required' => true
            ]
        );


        $fieldset->addField(
            'enabled',
            'select',
            [
                'name' => 'enabled',
                'label' => __('Enabled'),
                'id' => 'enabled',
                'title' => __('Enabled'),
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );

        $fieldset->addField(
            'adapter_code',
            'select',
            [
                'name' => 'adapter_code',
                'label' => __('Adapter'),
                'id' => 'adapter_code',
                'title' => __('Adapter'),
                'required' => true,
                'values' => $this->adapterlist->toOptionArray()
            ]
        );


        $form->setUseContainer(true);
        $this->setForm($form);

        foreach ($this->adapterlist->getList() as $adapter) {
            $form->addElement($adapter->getConfigurationFieldset($model->getData($adapter->getAdapterCode())));
        }

        $form->setValues($model->getData());

        $fieldset->addField(
            'events',
            'textarea',
            [
                'name' => 'events',
                'label' => __('Observed events'),
                'id' => 'events',
                'title' => __('Observed events'),
                'required' => true,
                'note' => __("Insert events names that triggers this notification, one by line"),
                'value' => implode(PHP_EOL, empty($model->getEvents()) ? []: $model->getEvents()),
            ]
        );


        return parent::_prepareForm();
    }
}
