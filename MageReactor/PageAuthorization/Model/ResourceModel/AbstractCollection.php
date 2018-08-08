<?php 
namespace MageReactor\PageAuthorization\Model\ResourceModel;
use Magento\Store\Model\Store;

class AbstractCollection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $storeManager;

	protected $metadataPool;

	public function __construct(
		\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
		\Magento\Framework\Event\ManagerInterface $eventManager,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\EntityManager\MetadataPool $metadataPool,
		\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
		\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
	){
		$this->storeManager = $storeManager;
		$this->metadataPool = $metadataPool;
		parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
	}

	protected function performAfterLoad($tableName, $linkField)
	{
		$linkedIds = $this->getColumnValues($linkField);
		if (count($linkedIds) && $tableName == 'mr_page_authorization_store') {
			$connection = $this->getConnection();
			$select = $connection->select()->from(['mr_page_authorization_entity_store' => $this->getTable($tableName)])
								->where('mr_page_authorization_entity_store.' . $linkField . ' IN (?)', $linkedIds);
			$result = $connection->fetchAll($select);
			if ($result) {
				$storesData = [];
				foreach ($result as $storeData) {
					$storesData[$storeData[$linkField]][] = $storeData['store_id'];
				}
				
				foreach ($this as $item) {
					$linkedId = $item->getData($linkField);
					if (!isset($storesData[$linkedId])) {
						continue;
					}
					$storeIdKey = array_search(Store::DEFAULT_STORE_ID, $storesData[$linkedId], true);
					if ($storeIdKey !== false) {
						$stores = $this->storeManager->getStores(false, true);
						$storeId = current($stores)->getId();
						$storeCode = key($stores);
					} else {
						$storeId = current($storesData[$linkedId]);
						$storeCode = $this->storeManager->getStore($storeId)->getCode();
					}
					$item->setData('_first_store_id', $storeId);
					$item->setData('store_code', $storeCode);
					$item->setData('store_id', $storesData[$linkedId]);
				}
			}
		}

		if( count($linkedIds) && $tableName == 'mr_page_authorization_cms_pages' ){
			$connection = $this->getConnection();
			$select = $connection->select()->from(['mr_page_authorization_entity_cms_pages' => $this->getTable($tableName)])
								->where('mr_page_authorization_entity_cms_pages.' . $linkField . ' IN (?)', $linkedIds);
			
			$result = $connection->fetchAll($select);
			if ($result) {
				$cmsPagesData = [];
				foreach ($result as $cmsPageData) {
					$cmsPagesData[$cmsPageData[$linkField]][] = $cmsPageData['cms_page_id'];
				}

				foreach ($this as $item) {
					$linkedId = $item->getData($linkField);
					if (!isset($cmsPagesData[$linkedId])) {
						continue;
					}
					$item->setData('cms_page_id', $cmsPagesData[$linkedId]);
				}
			}
		}


		if( count($linkedIds) && $tableName == 'mr_page_authorization_categories' ){
			$connection = $this->getConnection();
			$select = $connection->select()->from(['mr_page_authorization_entity_categories' => $this->getTable($tableName)])
								->where('mr_page_authorization_entity_categories.' . $linkField . ' IN (?)', $linkedIds);
			
			$result = $connection->fetchAll($select);
			if ($result) {
				$categories = [];
				foreach ($result as $categoryData) {
					$categories[$categoryData[$linkField]][] = $categoryData['category_id'];
				}

				foreach ($this as $item) {
					$linkedId = $item->getData($linkField);
					if (!isset($categories[$linkedId])) {
						continue;
					}
					$item->setData('category_id', $categories[$linkedId]);
				}
			}
		}
	}

	protected function joinStoreRelationTable($tableName, $linkField)
	{	
		$this->getSelect()->join(
			['store_table' => $this->getTable($tableName)],
			'main_table.' . $linkField . ' = store_table.' . $linkField,
			[]
		)->group(
			'main_table.' . $linkField
		);
		parent::_renderFiltersBefore();
	}

	public function getSelectCountSql()
	{
		$countSelect = parent::getSelectCountSql();
		$countSelect->reset(\Magento\Framework\DB\Select::GROUP);
	
		return $countSelect;
	}
}
?>