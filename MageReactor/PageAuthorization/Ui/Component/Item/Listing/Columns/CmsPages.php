<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Cms\Model\Page as PageModel;

class CmsPages extends Column
{

    protected $pageModel;

    public function __construct(        
        PageModel $pageModel,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );

        $this->pageModel = $pageModel;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }
        return $dataSource;
    }

    protected function prepareItem(array $item)
    {
        $content = '';
        if( isset($item['cms_page_id']) && is_array( $item['cms_page_id'] )){
            foreach($item['cms_page_id'] as $key => $cmsPageId){
                $item['cms_page_id'][$key] = 'ID: '. $cmsPageId .' ' . $this->pageModel->load($cmsPageId)->getTitle();
            }
            $content = $content . implode('<br/>', $item['cms_page_id']);
        }
        return $content;
    }

    public function prepare()
    {
        parent::prepare();
    }
}
