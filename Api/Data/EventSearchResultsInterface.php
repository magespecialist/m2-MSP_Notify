<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Api\Data;

interface EventSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get an array of objects
     * @return \MSP\Notify\Api\Data\EventInterface[]
     */
    public function getItems();

    /**
     * Set objects list
     * @param \MSP\Notify\Api\Data\EventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
