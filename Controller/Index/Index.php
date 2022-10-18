<?php
namespace FME\DeleteMyAccount\Controller\Index;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Controller\ResultFactory;

        

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_storeManager = $storeManager;
        return parent::__construct($context);
    }
    public function execute()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');
        if($customerSession->isLoggedIn()) {

            return $this->_pageFactory->create();
          
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);


            $resultRedirect->setUrl($this->_storeManager->getStore()->getUrl('customer/account/login'));
            return $resultRedirect;
        }

        
    }
}
