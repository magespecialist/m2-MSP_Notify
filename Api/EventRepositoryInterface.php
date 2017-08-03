<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Api;

interface EventRepositoryInterface
{
    /**
     * Save object
     * @param \MSP\Notify\Api\Data\EventInterface $object
     * @return void
     */
    public function save(\MSP\Notify\Api\Data\EventInterface $object);

    /**
     * Get object by id
     * @param int $id
     * @param $forceReload
     * @return \MSP\Notify\Api\Data\EventInterface
     */
    public function getById($id, $forceReload = false);

    /**
     * Get event by event name
     * @param string $event
     * @return \MSP\Notify\Api\Data\EventInterface[]
     */
    public function getByEvent($event);

    /**
     * Delete object
     * @param \MSP\Notify\Api\Data\EventInterface $object
     * @return void
     */
    public function delete(\MSP\Notify\Api\Data\EventInterface $object);

    /**
     * Delete object by id
     * @param $id
     * @return void
     */
    public function deleteById($id);

    /**
     * Get a list of object
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MSP\Notify\Api\Data\EventSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
