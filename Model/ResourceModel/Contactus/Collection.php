<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Model\ResourceModel\Contactus;

use Magelearn\Contactus\Model\Contactus as ContactusModel;
use Magelearn\Contactus\Model\ResourceModel\Contactus as ContactusResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(ContactusModel::class, ContactusResource::class);
    }
}
