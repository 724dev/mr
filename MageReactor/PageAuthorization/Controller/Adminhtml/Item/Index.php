<?php
namespace MageReactor\PageAuthorization\Controller\Adminhtml\Item;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $pageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("MageReactor_PageAuthorization::items");
        $resultPage->addBreadcrumb(__("Page Authorization Items"), __("Page Authorization Items"));
        $resultPage->addBreadcrumb(__("Manage Page Authorization Items"), __("Manage Page Authorization Items"));
        $resultPage->getConfig()->getTitle()->prepend(__("Page Authorization Items"));

        $dataPersistor = $this->_objectManager->get("Magento\Framework\App\Request\DataPersistorInterface");
        $dataPersistor->clear("page_authorization");

        return $resultPage;
    }
}