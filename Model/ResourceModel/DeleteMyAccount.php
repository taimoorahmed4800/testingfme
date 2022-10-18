<?php

namespace FME\DeleteMyAccount\Model\ResourceModel;
class DeleteMyAccount extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('fme_delete_my_account', 'delete_account_id');
    }
    public function insertCustomerData($customerdata) {
        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        )->where(
            'customer_id = ?',
            $customerdata['customer_id']
        );
        $email = $this->getConnection()->fetchCol($select);
        if(!empty($email)) {
            return 1;
        } else {
            $this->getConnection()
            ->insert(
                $this->getTable('fme_delete_my_account'),
                $customerdata
            );
            return 0;
        }
    }
    public function getAllAccountsRequests() {

        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        );
        $allaccounts = $this->getConnection()->fetchAll($select);
        return $allaccounts;

    }
    public function getAccountbyEmail($email) {
        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        )->where(
            'email = ?',
            $email['email']
        );
        $accounts = $this->getConnection()->fetchAll($select);
        return $accounts;
    }
    public function getAccountbyId($id) {
        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        )->where(
            'customer_id = ?',
            $id
        );
        $accounts = $this->getConnection()->fetchRow($select);
        return $accounts;
    }
    public function checkRequestToDelete($id) {

        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        )->where(
            'customer_id = ?',
            $id
        );

        $account = $this->getConnection()->fetchCol($select);
        if(!empty($account)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function setAccountForDelete($data) {
        $select = $this->getConnection()->select()->from(
            $this->getTable('fme_delete_my_account')
        )->where(
            'customer_id = ?',
            $data['customer_id']
        );
        $account = $this->getConnection()->fetchCol($select);
        if(!empty($account)) {
            return "Request Already Added";
        } else {
            $this->getConnection()
            ->insert(
                $this->getTable('fme_delete_my_account'),
                $data
            );
            return "Request Added Successfully";
        }
        
    }

    public function updateAccountDetails($data) {


        // echo "<pre>";
        //     print_r($data);

        // exit;

        $this->getConnection()->update(
            $this->getTable('fme_delete_my_account'),
            $data,
            ['customer_id = ?' => $data['customer_id']]
        );

        // return 1;
    }

    
    
}
