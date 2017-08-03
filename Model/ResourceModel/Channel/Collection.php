<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model\ResourceModel\Channel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'channel_id';

    protected function _construct()
    {
        $this->_init('\MSP\Notify\Model\Channel', '\MSP\Notify\Model\ResourceModel\Channel');
    }
}
