<?php
namespace MageReactor\PageAuthorization\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\MetadataPool;
use MageReactor\PageAuthorization\Api\Data\ItemInterface;
use Magento\Framework\Model\AbstractModel;

class Item extends AbstractDb
{
    protected $_storeManager;
    
    protected $dateTime;
    
    protected $entityManager;
    
    protected $metadataPool;
    
    protected $_itemStoreTable;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        DateTime $dateTime,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_itemStoreTable = "mr_page_authorization_store";
        $this->_itemCmsPagesTable = "mr_page_authorization_cms_pages";
        $this->_itemCategoryPagesTable = "mr_page_authorization_categories";
        $this->_storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
    }


    protected function _construct()
    {
        $this->_init('mr_page_authorization', 'item_id');
    }
    
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(ItemInterface::class)->getEntityConnection();
    }
    
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object){
        if( $object->getId() && !empty($object->getStoreId())){
            $storeIds = $object->getStoreId();
            $connection = $this->getConnection();
            $condition = [
                'item_id= ?' => $object->getId(),
            ];
            $connection->delete($this->_itemStoreTable, $condition);
            $condition = [
                'item_id= ?' => $object->getId(),
                'store_id IN (?)' => $storeIds,
            ];
            $connection->delete($this->_itemStoreTable, $condition);
            foreach ($storeIds as $storeId) {
                $connection->insertOnDuplicate($this->_itemStoreTable,[
                    "item_id" => $object->getId(),
                    "store_id" => $storeId
                ], []);
            }
        }

        if( $object->getId() && !empty($object->getCmsPageId())){
            $cmsPageIds = $object->getCmsPageId();
            $connection = $this->getConnection();
            $condition = [
                'item_id= ?' => $object->getId(),
            ];
            $connection->delete($this->_itemCmsPagesTable, $condition);
            $condition = [
                'item_id= ?' => $object->getId(),
                'cms_page_id IN (?)' => $cmsPageIds,
            ];
            $connection->delete($this->_itemCmsPagesTable, $condition);
            foreach ($cmsPageIds as $cmsPageId) {
                $connection->insertOnDuplicate($this->_itemCmsPagesTable,[
                    "item_id" => $object->getId(),
                    "cms_page_id" => $cmsPageId
                ], []);
            }
        }

        if( $object->getId() && !empty($object->getCategoryId())){
            $categoryIds = $object->getCategoryId();
            $connection = $this->getConnection();
            $condition = [
                'item_id= ?' => $object->getId(),
            ];
            $connection->delete($this->_itemCategoryPagesTable, $condition);
            $condition = [
                'item_id= ?' => $object->getId(),
                'category_id IN (?)' => $categoryIds,
            ];
            $connection->delete($this->_itemCategoryPagesTable, $condition);
            foreach ($categoryIds as $categoryId) {
                $connection->insertOnDuplicate($this->_itemCategoryPagesTable,[
                    "item_id" => $object->getId(),
                    "category_id" => $categoryId
                ], []);
            }
        }
        parent::_afterSave($object);
    }
}
?>