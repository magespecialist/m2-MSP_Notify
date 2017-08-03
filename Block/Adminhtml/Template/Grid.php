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

namespace MSP\Notify\Block\Adminhtml\Template;

use Magento\Backend\Block\Widget\Container;

class Grid extends Container
{

    protected function getNewNotificationUrl()
    {
        return $this->getUrl('adminhtml/mspnotify/edit');
    }

    protected function _prepareLayout()
    {
        $this->buttonList->add('add_new', [
            'id' => 'msp_notify_add_template',
            'label' => __("Add notification"),
            'class' => 'add primary',
            'onclick' => 'setLocation(\'' . $this->getNewNotificationUrl() . '\')',
        ]);

        parent::_prepareLayout();
    }
}
