<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Model;

use Magento\Framework\Model\AbstractModel;

class Contactus extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(
            \Magelearn\Contactus\Model\ResourceModel\Contactus::class
        );
    }
}