<?php

namespace FME\DeleteMyAccount\Block\Adminhtml;
class Overview extends \Magento\Framework\View\Element\Template 
{
    protected $_authorization;
    public function __construct(
        \Magento\Framework\AuthorizationInterface $authorization,
        \FME\DeleteMyAccount\Helper\Data $helper,
        \FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount $resourcemodel,
        // \FME\DeleteMyAccount\Model\Config\Source\Admins $adminData,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Model\Auth\Session $authSession, 
        \Magento\Framework\View\Element\Template\Context $context, 
        array $data = []
    )
    {
        $this->_authorization   = $authorization;
        $this->backendSession   = $backendSession;
        // $this->adminData        = $adminData;
        $this->helper        = $helper;
        $this->resourcemodel        = $resourcemodel;
        $this->authSession       = $authSession;
        parent::__construct($context, $data);
    }
    // public function toHtml() {
    //     return $this->_isAllowed() ? parent::toHtml() : '';
    // }
    public function showSelector()
    {
        return $this->helper->isMainAdmin();
    }
    public function getPreSelectedAdmin()
    {
        if($this->backendSession->getContactAdmin() !== NULL){
            return $this->backendSession->getContactAdmin();
        }else{
            return __("all");
        }
    }
    // public function getAllAdmins()
    // {
    //     return $this->adminData->toOptionArray();
    // }
    public function getTotalNoOfSubscription()
    {
        return $this->resourcemodel->getTotalNoOfSubscription();
    }
    public function getTotalAwatingNotifications()
    {
        return $this->resourcemodel->getTotalAwatingNotifications();
    }
    
}
