<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Api;

interface ChannelRepositoryInterface
{
    /**
     * Save object
     * @param \MSP\Notify\Api\Data\ChannelInterface $object
     * @return void
     */
    public function save(\MSP\Notify\Api\Data\ChannelInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @param $forceReload
     * @return \MSP\Notify\Api\Data\ChannelInterface
     */
    public function getById($id, $forceReload = false);

    /**
     * Delete object
     * @param \MSP\Notify\Api\Data\ChannelInterface $object
     * @return void
     */
    public function delete(\MSP\Notify\Api\Data\ChannelInterface $object);

    /**
     * Delete object by id
     * @param $id
     * @return void
     */
    public function deleteById($id);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MSP\Notify\Api\Data\ChannelSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
