<?php

namespace Magelearn\Contactus\Model\ResourceModel;

/**
 * Class Contactus
 * @package Magelearn\Contactus\Model\ResourceModel
 */

class Contactus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magelearn_contactus', 'id');
    }
}