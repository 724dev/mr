<?php
namespace MageReactor\PageAuthorization\Helper;

use \Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLED_ON_CMS_PAGES = 'page_authorization/cms_pages/enabled';
    const XML_PATH_ENABLED_ON_CATEGORY_PAGES = 'page_authorization/category_pages/enabled';

    public function isEnabledOnCmsPages()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLED_ON_CMS_PAGES, ScopeInterface::SCOPE_STORE);
    }

    public function isEnabledOnCategoryPages()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLED_ON_CATEGORY_PAGES, ScopeInterface::SCOPE_STORE);
    }

}
