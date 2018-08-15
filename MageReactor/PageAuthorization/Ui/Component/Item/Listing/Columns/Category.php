<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Catalog\Model\Category as CategoryModel;

class Category extends Column
{

    protected $categoryModel;

    public function __construct(        
        CategoryModel $categoryModel,
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

        $this->categoryModel = $categoryModel;
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
        if( isset($item['category_id']) && is_array( $item['category_id'] )){
            foreach($item['category_id'] as $key => $categoryId){
                $item['category_id'][$key] = 'ID: '. $categoryId .' ' . $this->categoryModel->load($categoryId)->getName();
            }
            $content = $content . implode('<br/>', $item['category_id']);
        }
        return $content;
    }

    public function prepare()
    {
        parent::prepare();
    }
}
