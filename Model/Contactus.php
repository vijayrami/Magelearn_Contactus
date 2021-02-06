<?php

namespace Magelearn\Contactus\Model;

/**
 * Class Contactus
 * @package Magelearn\Contactus\Model
 */

class Contactus extends \Magento\Framework\Model\AbstractModel
{       
    protected function _construct()
    {
        $this->_init('Magelearn\Contactus\Model\ResourceModel\Contactus');
    }
}