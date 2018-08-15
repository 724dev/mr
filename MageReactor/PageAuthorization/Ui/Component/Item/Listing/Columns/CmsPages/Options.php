<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns\CmsPages;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    protected $cmsPageModel;

    public function __construct(
        \Magento\Cms\Model\Page $cmsPageModel
    ){
        $this->cmsPageModel = $cmsPageModel;
    }
    
    public function toOptionArray()
    {
        $collection = $this->cmsPageModel->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        $this->currentOptions = [];
        foreach($collection as $item){
            $this->currentOptions[] = array(
                'label' => $item->getTitle(),
                'value' => $item->getId()
            );
        }

        array_unshift($this->currentOptions, [
            'label' => '--Please Select--',
            'value' => 0
        ]);

        return $this->currentOptions;
    }
}
