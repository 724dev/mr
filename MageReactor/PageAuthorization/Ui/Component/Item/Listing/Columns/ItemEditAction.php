<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;

class ItemEditAction extends Column {

    protected $urlBuilder;


    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $component = [],
        array $data = []
    ){
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $component, $data);
    }

    public function prepareDataSource(array $dataSource){
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['item_id']) ){
                    $item[$this->getData('name')]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'mr_page_authorization/item/edit',
                            ['id' => $item['item_id']]
                        ),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];

                    $item[$this->getData('name')]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            'mr_page_authorization/item/delete',
                            ['id' => $item['item_id']]
                        ),
                        'label' => __('Delete'),
                        'hidden' => false,
                    ];
                }
            }
        }
        return $dataSource;
    }
}
