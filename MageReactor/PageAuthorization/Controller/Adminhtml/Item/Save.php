<?php
namespace MageReactor\PageAuthorization\Controller\Adminhtml\Item;


class Save extends \Magento\Backend\App\Action
{
	public function __construct(
		\Magento\Backend\App\Action\Context $context
	) {
		parent::__construct($context);
	}

	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed("MageReactor_PageAuthorization::item_save");
	}

	public function execute()
	{
		$data = $this->getRequest()->getPostValue();
		$resultRedirect = $this->resultRedirectFactory->create();
		if ($data) {
			/** @var \MageReactor\PageAuthorization\Model\Item $model */
			$model = $this->_objectManager->create("MageReactor\PageAuthorization\Model\Item");
		
			$id = $this->getRequest()->getParam("id");
			if ($id) {
				$model->load($id);
			}
				
			if (empty($data['item_id'])) {
				$data['item_id'] = null;
			}
			
			$model->setData($data);
				
			$this->_eventManager->dispatch( "mr_page_authorization_item_data_prepare_save",[
				"item" => $model,
				"request" => $this->getRequest()
			]);
				
			try {
				$model->save();
				$this->messageManager->addSuccess(__("Item was successfully saved."));
				$this->_objectManager->get("Magento\Backend\Model\Session")->setFormData(false);
				if ($this->getRequest()->getParam("back")) {
					return $resultRedirect->setPath("*/*/edit", ["id" => $model->getId(), "_current" => true]);
				}
				return $resultRedirect->setPath("*/*/");
			} catch (\Magento\Framework\Exception\LocalizedException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\RuntimeException $e) {
				$this->messageManager->addError($e->getMessage());
			} catch (\Exception $e) {
				$this->messageManager->addException($e, __("Something went wrong while saving the item."));
			}
		
			$this->_getSession()->setFormData($data);
			return $resultRedirect->setPath("*/*/edit", ["id" => $this->getRequest()->getParam("item_id")]);
		}
		return $resultRedirect->setPath("*/*/");
	}

}
?>