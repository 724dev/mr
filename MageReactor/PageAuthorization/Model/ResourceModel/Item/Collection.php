<?php
namespace MageReactor\PageAuthorization\Model\ResourceModel\Item;

use MageReactor\PageAuthorization\Api\Data\ItemInterface;
use MageReactor\PageAuthorization\Model\ResourceModel\AbstractCollection;


class Collection extends AbstractCollection
{
	protected $_idFieldName = 'item_id';

	protected $_previewFlag;

	protected function _construct()
	{
		$this->_init('\MageReactor\PageAuthorization\Model\Item', '\MageReactor\PageAuthorization\Model\ResourceModel\Item');
		$this->_map['fields']['item_id'] = 'main_table.item_id';
		$this->_map['fields']['store'] = 'store_table.store_id';
		$this->_map['fields']['cms_page'] = 'cms_page_table.page_id';
		$this->_map['fields']['catalog_category_entity'] = 'category_table.entity_id';
	}

	public function setFirstStoreFlag($flag = false)
	{
		$this->_previewFlag = $flag;
		return $this;
	}

	protected function _afterLoad()
	{
		$entityMetadata = $this->metadataPool->getMetadata(ItemInterface::class);
		$this->performAfterLoad('mr_page_authorization_store', $entityMetadata->getLinkField());
		$this->performAfterLoad('mr_page_authorization_cms_pages', $entityMetadata->getLinkField());
		$this->performAfterLoad('mr_page_authorization_categories', $entityMetadata->getLinkField());
		$this->_previewFlag = false;
	
		return parent::_afterLoad();
	}

	protected function _renderFiltersBefore()
	{
		$entityMetadata = $this->metadataPool->getMetadata(ItemInterface::class);
		$this->joinStoreRelationTable('mr_page_authorization_store', $entityMetadata->getLinkField());
	}
}

?>