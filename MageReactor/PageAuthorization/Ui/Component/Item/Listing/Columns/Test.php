<?php
namespace MageReactor\PageAuthorization\Ui\Component\Item\Listing\Columns;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Test extends Column
{
    protected $cmsPageModel;

    public function __construct(
        \Magento\Cms\Model\Page $cmsPageModel,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->cmsPageModel = $cmsPageModel;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareItems(array & $items)
    {
        print_r($items);
        exit;

        return $items;
    }
}
