<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns;

use Magento\Framework\Data\OptionSourceInterface;

class CmsPages implements OptionSourceInterface
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
        // echo $collection->getSelect();
        // exit;
        // if ($this->options !== null) {
        //     return $this->options;
        // }

        // $this->currentOptions['All Store Views']['label'] = __('All Store Views');
        // $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS;

        // $this->generateCurrentOptions();

        // $this->options = array_values($this->currentOptions);

        array_unshift($this->currentOptions, [
            'label' => '--Please Select--',
            'value' => 0
        ]);

        return $this->currentOptions;
    }
}
