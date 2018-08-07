<?php
namespace MageReactor\PageAuthorization\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use MageReactor\PageAuthorization\Api\Data\ItemInterface;

class Item extends AbstractModel implements IdentityInterface, ItemInterface
{

    const NOROUTE_PAGE_ID = 'no-route';
    
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    const CACHE_TAG = 'mr_magereactor_item';
    
    protected $_cacheTag = 'mr_magereactor_item';
    
    protected $_eventPrefix = 'mr_magereactor_item';
    
    protected $_idFieldName = 'item_id';
    
    private $scopeConfig;

    protected $date;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []

    ){
        $this->date = $date;
        parent::__construct($context, $registry);
    }

    protected function _construct()
    {
        $this->_init('MageReactor\PageAuthorization\Model\ResourceModel\Item');
    }

    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoutePage();
        }
        return parent::load($id, $field);
    }

    public function noRoutePage()
    {
        return $this->load(self::NOROUTE_PAGE_ID, $this->getIdFieldName());
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setId($id)
    {
        return $this->setData(self::ITEM_ID, $id);
    }

    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

    public function setCreatedAt($created_at)
    {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    public function setUpdatedAt($updated_at)
    {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * Processing object before save data
     *
     * @return $this
     */
    public function beforeSave(){
        $date = $this->date->gmtDate();
        if( $this->isObjectNew() ){
            $this->setCreatedAt($date);
        }
        $this->setUpdatedAt($date);
        parent::beforeSave();
    }
}
