<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('msp_notify_event', 'event_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $events = $object->getData('linked_events');
        if (is_array($events)) {
            $events = implode(',', $events);
            $object->setData('linked_events', $events);
        }

        return parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $events = $object->getData('linked_events');
        $events = explode(',', $events);
        $object->setData('linked_events', $events);

        return parent::_afterLoad($object);
    }
}
