<?php
namespace FME\DeleteMyAccount\Controller\Index;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Controller\ResultFactory;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $Http;
    protected $_objectManager;
    protected $r_deletemyaccount;
    protected $_messageManager;
    protected $Helper;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Http\Context $Http,
        \FME\DeleteMyAccount\Helper\Data $Helper,
        \FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount $r_deletemyaccount,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->Http = $Http;
        $this->Helper = $Helper;
        $this->_messageManager = $messageManager;
        $this->r_deletemyaccount = $r_deletemyaccount;
        $this->_customerFactory = $customerFactory;
        $this->_objectManager = $objectmanager;
        return parent::__construct($context);
    }
    public function execute()
    {
        $postdata = $this->getRequest()->getParams();
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if(isset($postdata['conform_delete']) && $postdata['conform_delete']) {
            $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
            if ($customerSession->isLoggedIn()) {
                $customerdata=$this->_customerFactory->create()->load($this->Http->getValue(\FME\DeleteMyAccount\Model\Customer\Context::CONTEXT_CUSTOMER_ID));
                $data['first_name'] = $customerdata->getfirstname();
                $data['customer_id'] = $customerdata->getId();
                $data['last_name'] = $customerdata->getlastname();
                $data['email'] = $customerdata->getemail();
                $data['mobile_number'] = $customerdata->getPhoneNumber();
                foreach ($customerdata->getAddresses() as $address)
                {
                    $address = $address->toArray();
                    $data['address'] = $address['street'].",".$address['region'];
                    $data['telephone'] = $address['telephone'];
                    break;
                }
                $this->Helper->sendCustomerEmail($data);
                $this->Helper->sendAdminEmail($data);
                $reult=$this->r_deletemyaccount->insertCustomerData($data);
                if($reult) {
                    $this->_messageManager->addNoticeMessage("Request Added Already");
                } else {
                    $this->_messageManager->addSuccessMessage("Request Sended Successfully");
                }
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
