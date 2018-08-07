<?php
namespace MageReactor\PageAuthorization\Model;

use MageReactor\PageAuthorization\Api\Data;
use MageReactor\PageAuthorization\Api\ItemRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use MageReactor\PageAuthorization\Model\ResourceModel\Item as ResourceItem;
use MageReactor\PageAuthorization\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;


class ItemRepository implements ItemRepositoryInterface
{
    protected $resource;
    
    protected $itemFactory;
    
    protected $itemCollectionFactory;
    
    protected $dataObjectHelper;
    
    protected $dataObjectProcessor;
    
    protected $dataItemFactory;
    
    private $storeManager;
    
    public function __construct(
        ResourceItem $resource,
        ItemFactory $itemFactory,
        Data\ItemInterfaceFactory $dataItemFactory,
        ItemCollectionFactory $itemCollectionFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ){
        $this->resource = $resource;
        $this->itemFactory = $itemFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataItemFactory = $dataItemFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }
    
    public function save(\MageReactor\PageAuthorization\Api\Data\ItemInterface $item)
    {
        return $item;
    }
    
    public function getById($item_id)
    {
        $item = $this->itemFactory->create();
        $this->resource->load($item, $item_id);
        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item with id "%1" does not exist.', $item_id));
        }
        return $item;
    }

   
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        return $criteria;
    }

   
    public function delete(\MageReactor\PageAuthorization\Api\Data\ItemInterface $item)
    {
        return true;
    }


    public function deleteById($item_id)
    {
        return $this->delete($this->getById($item_id));
    }
}
