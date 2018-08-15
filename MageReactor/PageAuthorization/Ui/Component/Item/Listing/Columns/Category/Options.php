<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns\Category;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    protected $categoryModel;

    public function __construct(
        \Magento\Catalog\Model\Category $categoryModel
    ){
        $this->categoryModel = $categoryModel;
    }
    
    public function toOptionArray()
    {
        $collection = $this->categoryModel->getCollection();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('is_active', 1);
        $this->currentOptions = [];

        if( $collection->getSize() ){
            foreach($collection as $item){
                $this->currentOptions[] = array(
                    'label' => $item->getName(),
                    'value' => $item->getId()
                );
            }   
        }

        array_unshift($this->currentOptions, [
            'label' => '--Please Select--',
            'value' => 0
        ]);

        return $this->currentOptions;
    }
}
