<?php
namespace MageReactor\PageAuthorization\Block\Adminhtml\Item\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    protected $context;

    public function __construct(
        Context $context
    ){
        $this->context = $context;
    }

    public function getItemId()
    {
        return $this->context->getRequest()->getParam('id', null);
    }
    
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
