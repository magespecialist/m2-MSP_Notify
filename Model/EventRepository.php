<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Model;

use MSP\Notify\Api\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    protected $objectResource;
    protected $objectFactory;
    protected $searchResultsFactory;
    protected $collectionFactory;

    protected $registry = [];

    public function __construct(
        \MSP\Notify\Api\Data\EventInterfaceFactory $objectFactory,
        \MSP\Notify\Model\ResourceModel\Event $objectResource,
        \MSP\Notify\Model\ResourceModel\Event\CollectionFactory $collectionFactory,
        \MSP\Notify\Api\Data\EventSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->objectFactory = $objectFactory;
        $this->objectResource = $objectResource;
        $this->collectionFactory = $collectionFactory;
    }

    protected function clearRegistry($id)
    {
        if (isset($this->registry[$id])) {
            unset($this->registry[$id]);
        }
    }

    public function save(\MSP\Notify\Api\Data\EventInterface $object)
    {
        $this->objectResource->save($object);
        $this->clearRegistry($object->getId());

        return $object;
    }

    public function getById($id, $forceReload = false)
    {
        if (!isset($this->registry[$id]) || $forceReload) {
            $object = $this->objectFactory->create();
            $this->objectResource->load($object, $id);

            $this->registry[$id] = $object;
        }

        return $this->registry[$id];
    }

    public function delete(\MSP\Notify\Api\Data\EventInterface $object)
    {
        $this->objectResource->delete($object);
        $this->clearRegistry($object->getId());
    }

    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->objectFactory->create()->getCollection();
        $this->applySearchCriteriaToCollection($searchCriteria, $collection);

        $vendors = $this->convertCollectionToDataItemsArray($collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($vendors);

        return $searchResults;
    }

    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \MSP\Notify\Model\ResourceModel\Event\Collection $collection
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ?: 'eq';
            $fields[] = $filter->getField();

            $conditions[] = [$condition => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    protected function convertCollectionToDataItemsArray(\MSP\Notify\Model\ResourceModel\Event\Collection $collection)
    {
        $vendors = array_map(function (\MSP\Notify\Api\Data\EventInterface $vendor) {
            $dataObject = $this->objectFactory->create();
            $dataObject->setData($vendor->getData());
            return $dataObject;
        }, $collection->getItems());

        return $vendors;
    }

    protected function applySearchCriteriaToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \MSP\Notify\Model\ResourceModel\Event\Collection $collection
    ) {
        $this->applySearchCriteriaFiltersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaSortOrdersToCollection($searchCriteria, $collection);
        $this->applySearchCriteriaPagingToCollection($searchCriteria, $collection);
    }

    protected function applySearchCriteriaFiltersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \MSP\Notify\Model\ResourceModel\Event\Collection $collection
    ) {
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
    }

    protected function applySearchCriteriaSortOrdersToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \MSP\Notify\Model\ResourceModel\Event\Collection $collection
    ) {
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $isAscending = $sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC;
                $collection->addOrder($sortOrder->getField(), $isAscending ? 'ASC' : 'DESC');
            }
        }
    }

    protected function applySearchCriteriaPagingToCollection(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \MSP\Notify\Model\ResourceModel\Event\Collection $collection
    ) {
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
    }

    /**
     * Get event by event name
     * @param string $event
     * @return \MSP\Notify\Api\Data\EventInterface[]
     */
    public function getByEvent($event)
    {
        if (!isset($this->registry[$event])) {
            $collection = $this->collectionFactory->create();

            $collection->addFieldToFilter('linked_events', array('like' => '%' . $event . '%'));

            $this->registry[$event] = $collection->getItems();
        }

        return $this->registry[$event];
    }
}
