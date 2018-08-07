<?php
namespace MageReactor\PageAuthorization\Block\Adminhtml\Item\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'on_click' => sprintf("location.href = 'mr_page_authorization/item/save';"),
            'class' => 'save primary',
            'sort_order' => 10
        ];
    }    
}
