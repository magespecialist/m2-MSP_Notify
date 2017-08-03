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

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use MSP\Notify\Model\NotificationTemplate;

class Edit extends Container
{

    protected $registry;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data
    ) {
    
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        $this->_objectId = 'msp_notify_template_id';
        $this->_controller = 'Adminhtml\Template';
        $this->_blockGroup = 'MSP_Notify';

        parent::_construct();

        $this->buttonList->update('save', 'label', __("Save template"));
        $this->buttonList->remove('delete');
    }

    public function getHeaderText()
    {
        $template = $this->getTemplate();

        if ($template->getId()) {
            return __("Edit template %1", $template->getName());
        } else {
            return __("New template");
        }
    }

    /**
     * @return NotificationTemplate
     * @throws LocalizedException
     */
    protected function getNotificationTemplate()
    {
        $template = $this->registry->registry('current_msp_template');

        if (is_null($template)) {
            throw new LocalizedException(__('Invalid template'));
        }

        return $template;
    }
}
