<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Zend\Json\Json;

class Channel extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('msp_notify_channel', 'channel_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $config = $object->getData('adapter_configuration');

        $object->setData('adapter_configuration', Json::encode($config));
        return parent::_beforeSave($object);
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $channel)
    {
        $channel->setData('adapter_configuration', Json::decode($channel->getData('adapter_configuration'), Json::TYPE_ARRAY));

        return $this;
    }
}
