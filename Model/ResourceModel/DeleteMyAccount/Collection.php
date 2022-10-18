<?php

namespace FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount;

use FME\DeleteMyAccount\Model\ResourceModel\AbstractCollection;

/**
 * CMS page collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'delete_account_id';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('FME\DeleteMyAccount\Model\DeleteMyAccount', 'FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount');
        $this->_map['fields']['delete_account_id'] = 'main_table.delete_account_id';
    }
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
