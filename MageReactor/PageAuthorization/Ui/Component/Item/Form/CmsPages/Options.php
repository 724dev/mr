<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Form\CmsPages;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory as PageCollectionFactory;

class Options implements OptionSourceInterface
{
    protected $pageCollectionFactory;

    protected $cmsPagesTree;

    public function __construct(
        PageCollectionFactory $pageCollectionFactory
    ) {
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    public function toOptionArray()
    {
        return $this->getCategoriesTree();
    }

    protected function getCategoriesTree()
    {
        $matchingNamesCollection = $this->pageCollectionFactory->create();
        $matchingNamesCollection->addFieldToSelect(array('row_id', 'title'));
        $matchingNamesCollection->addFieldToFilter('is_active', 1);
        $this->cmsPagesTree = [];
        foreach($matchingNamesCollection as $page){
            $this->cmsPagesTree[] = array(
                'value' => $page->getId(),
                'label' => 'ID: ' . $page->getId() . ' Title: ' . $page->getTitle(),
                'is_active' => $page->getIsActive()
            );
        }
        return $this->cmsPagesTree;
    }
}
