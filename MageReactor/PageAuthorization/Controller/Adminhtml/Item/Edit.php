<?php
namespace MageReactor\PageAuthorization\Controller\Adminhtml\Item;

class Edit extends \Magento\Backend\App\Action
{
    protected $_coreRegistry = null;

    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("MageReactor_PageAuthorization::item_save");
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("MageReactor_PageAuthorization::item")
            ->addBreadcrumb(__("Item"), __("Item"))
            ->addBreadcrumb(__("Manage Item"), __("Manage Item"));
        return $resultPage;
    }
    
    public function execute()
    {

        $id = $this->getRequest()->getParam("id");
        $model = $this->_objectManager->create("MageReactor\PageAuthorization\Model\Item");
        if ($id)
        {
            $model->load($id);
            if (!$model->getId())
            {
                $this->messageManager->addError(__("This item  no longer exists."));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath("*/*/");
            }
        }
        $data = $this->_objectManager->get("Magento\Backend\Model\Session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __("Edit Item") : __("New Item"),
            $id ? __("Edit Item") : __("New Item")
        );
        $resultPage->getConfig()->getTitle()->prepend(__("Item"));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __("New Item"));
        return $resultPage;
    }

}

?>