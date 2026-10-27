<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Contactus extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magelearn_contactus', 'id');
    }
}
