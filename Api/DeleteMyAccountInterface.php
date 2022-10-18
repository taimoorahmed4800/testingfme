<?php

namespace FME\DeleteMyAccount\Api;

interface DeleteMyAccountInterface {
    
    /**
     * Get All Accounts Requests
     * @api
     * @return array
     */
    public function getAllAccountsRequests();


    /**
     * Set Account For Delete
     * @api
     * @return array
     */
    public function setAccountForDelete();

    /**
     * Get Account by Email
     * @api
     * @return array
     */
    public function getAccountbyEmail();

    
}