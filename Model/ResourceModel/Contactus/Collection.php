<?php

namespace Magelearn\Contactus\Model\ResourceModel\Contactus;

use \Magelearn\Contactus\Model\ResourceModel\AbstractCollection;

/**
 * Class Collection
 * @package Magelearn\Contactus\Model\ResourceModel\Contactus
 */

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_previewFlag;
    protected function _construct()
    {
        $this->_init(
            'Magelearn\Contactus\Model\Contactus',
            'Magelearn\Contactus\Model\ResourceModel\Contactus'
        );
        $this->_map['fields']['id'] = 'main_table.id';
    }
}