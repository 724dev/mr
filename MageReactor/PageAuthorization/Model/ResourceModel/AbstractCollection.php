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
		if (count($linkedIds)) {
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