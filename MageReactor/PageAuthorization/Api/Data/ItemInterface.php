<?php

namespace MageReactor\PageAuthorization\Api\Data;

interface ItemInterface
{
    const ITEM_ID = "item_id";
    const IS_ACTIVE = "is_active";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    public function getId();

    public function getIsActive();

    public function getCreatedAt();

    public function getUpdatedAt();

    public function setId($id);

    public function setIsActive($is_active);

    public function setCreatedAt($created_at);

    public function setUpdatedAt($update_at);
}