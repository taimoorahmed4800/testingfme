<?php

namespace FME\DeleteMyAccount\Model;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
class DeleteMyAccount extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_ENABLED  = 1;
    const STATUS_DISABLED = 0;

    protected function _construct()
    {
        $this->_init('FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount');
    }

     public function __construct(
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ){
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    public function getAvailableStatuses()
    {


        $availableOptions = ['New' => 'New',
                          'Under Process' => 'Under Process',
                          'Pending' => 'Pending',
                          'Done' => 'Done'];

        return $availableOptions;
    }

    public function getBudgetStatuses()
    {


        $options = ['Approved' => 'Approved',
                          'Approval Pending' => 'Approval Pending',
                          'Open' => 'Open',
                          'No Approval' => 'No Approval'];

        return $options;
    }
    public function getStatuses()
    {
        return [
               self::STATUS_ENABLED  => __('Enabled'),
               self::STATUS_DISABLED => __('Disabled'),
              ];
    }

}
