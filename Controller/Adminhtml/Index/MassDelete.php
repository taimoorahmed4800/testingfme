<?php

namespace FME\DeleteMyAccount\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter; 
use FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount\CollectionFactory;
use FME\DeleteMyAccount\Model\DeleteMyAccountFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{
    protected $DeleteMyAccountFactory;
    protected $customerRepository;
    protected $filter;
    protected $collectionFactory;
    protected $authSession;
    protected $r_deletemyaccount;
    const TTL = 86400;

    public function __construct(
        Context $context, 
        \Magento\Backend\Model\Auth\Session $authSession, 
        DeleteMyAccountFactory $DeleteMyAccountFactory,
        Filter $filter, 
        \FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount $r_deletemyaccount,
        CustomerRepositoryInterface $customerRepository,
        CollectionFactory $collectionFactory
    )
    {
        $this->filter = $filter;
        $this->authSession = $authSession;
        $this->r_deletemyaccount = $r_deletemyaccount;
        $this->customerRepository = $customerRepository;
        $this->DeleteMyAccountFactory = $DeleteMyAccountFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {


        $productIds = [];
        $update  = [];
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $collectionSize = $collection->getSize();
        foreach ($collection as $page) {
            $page->delete();
        }



        // $productIds = [];
        // $update  = [];
        // $collection = $this->filter->getCollection($this->collectionFactory->create());
        // $count = 0;
        // foreach($collection as $col) {

        //     $customer_check = $this->_objectManager->get('Magento\Customer\Model\Customer');
        //     $customer_check->setWebsiteId(1);
        //     $customer_check->load($col->getData('customer_id'));

        //     if ( $customer_check->getId() ) {
        //         $this->customerRepository->deleteById($col->getData('customer_id'));
        //          $count++;
        //     } 
        //     $data['action_admin_id'] = $this->authSession->getUser()->getId();
        //     $data['action_admin_name'] = $this->authSession->getUser()->getName();
        //     $data['customer_id'] = $col->getData('customer_id');
        //     $data['deletion_date'] = date('Y-m-d H:i:s', time() + self::TTL);
        //     $this->r_deletemyaccount->updateAccountDetails($data);


        // }
        // $collectionSize = $collection->getSize();
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('deletemyaccount/index/report');
    }
}
