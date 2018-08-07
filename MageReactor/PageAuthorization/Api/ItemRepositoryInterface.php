<?php

namespace MageReactor\PageAuthorization\Api;

interface ItemRepositoryInterface
{
   
    public function save(\MageReactor\PageAuthorization\Api\Data\ItemInterface $item);

   
    public function getById($item_id);

    
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

   
    public function delete(\MageReactor\PageAuthorization\Api\Data\ItemInterface $item);

   
    public function deleteById($item_id);
}
