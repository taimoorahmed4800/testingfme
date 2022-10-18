<?php

namespace FME\DeleteMyAccount\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
// use FME\QayadatSms\Helper\Data as SmsHelper;


class Data extends AbstractHelper
{

    protected $_scopeConfig;
    const XML_DELETEMYACCOUNT='deletemyaccount/fmeaccount/enable';
    const XML_DESCRIPTION='deletemyaccount/fmeaccount/description';
    const XML_LINKTEXT = 'deletemyaccount/fmeaccount/link_text';
    protected $_transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;

    public function __construct(
        \FME\DeleteMyAccount\Model\Config\Source\MainAdmin $mainAdmin,
        \Magento\Backend\Model\Auth\Session $authSession,
        // SmsHelper $smsHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->authSession = $authSession;
        $this->storeManager = $storeManager;
        // $this->_smsHelper = $smsHelper;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->mainAdmin = $mainAdmin;
        parent::__construct($context);
    }
    
    public function isEnabledInFrontend()
    {
        $isEnabled = true;
        $enabled = $this->scopeConfig->getValue(self::XML_DELETEMYACCOUNT, ScopeInterface::SCOPE_STORE);
        if ($enabled == null || $enabled == '0') {
            $isEnabled = false;
        }
        return $isEnabled;
    }
    public function getDescription()
    {
        return $this->scopeConfig->getValue(self::XML_DESCRIPTION);
    }
    public function getUser()
    {
        return $this->authSession->getUser();
    }
    public function isMainAdmin()
    {
        $mainAdmins= $this->mainAdmin->toOptionArray();
        if(in_array($this->getUser()->getId(),array_column($mainAdmins,'value'))){
            return true;
        } elseif(in_array($this->getUser()->getId(),$this->getSeniorAdmin())){
            return true;
        } else {
            return false;
        }
    }
    public function getSeniorAdmin()
    {
        return explode(",",$this->getConfigValue('age_verification/senior/admin'));
    }
    public function getLinkText()
    {
     return $this->scopeConfig->getValue(self::XML_LINKTEXT);
 }
 public function sendCustomerEmail($data)
 {

    if($this->scopeConfig->getValue('deletemyaccount/customer_email/enable')) {

        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($data);
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->scopeConfig->getValue('deletemyaccount/customer_email/template'))
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->scopeConfig->getValue('deletemyaccount/customer_email/sender'))
            ->addTo($data['email'])
            ->setReplyTo($this->scopeConfig->getValue('deletemyaccount/customer_email/recipient'))
            ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            if($this->scopeConfig->getValue('deletemyaccount/sms/sms_enabled')) {
                $smsbody = $this->scopeConfig->getValue('deletemyaccount/sms/sms_body');
                $smsbody = str_replace("%1",$data['first_name']." ".$data['last_name'],$smsbody);
                // $this->sendSms($data['mobile_number'],$smsbody);
            }
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
        }

    }


}

// public function sendSms($number, $message)
// {
//     try 
//     {
//         if($number!=null && $message!=null) {
//             $this->_smsHelper->sendMessage($number,$message);
//         }
//     }catch (\Exception $ex) {
//     }
// }



public function sendAdminEmail($data)
{

    if($this->scopeConfig->getValue('deletemyaccount/admin_email_configuration/enable')) {

        try {
            $postObject = new \Magento\Framework\DataObject();
            $data['subject'] = $this->scopeConfig->getValue('deletemyaccount/admin_email_configuration/subject');
            $postObject->setData($data);
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->scopeConfig->getValue('deletemyaccount/admin_email_configuration/template'))
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->scopeConfig->getValue('deletemyaccount/admin_email_configuration/sender'))
            ->addTo($this->scopeConfig->getValue('deletemyaccount/admin_email_configuration/admin_email'))
            ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
        }

    }

}

public function sendCustomerEmailOnDelete($data)
{
    if($this->scopeConfig->getValue('deletemyaccount/customer_email_on_delete/enable')) {
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($data);
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->scopeConfig->getValue('deletemyaccount/customer_email_on_delete/template'))
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->scopeConfig->getValue('deletemyaccount/customer_email_on_delete/sender'))
            ->addTo($data['email'])
            ->setReplyTo($this->scopeConfig->getValue('deletemyaccount/customer_email_on_delete/recipient'))
            ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            if($this->scopeConfig->getValue('deletemyaccount/on_delete_sms/sms_enabled')) {
                $smsbody = $this->scopeConfig->getValue('deletemyaccount/on_delete_sms/sms_body');
                $smsbody = str_replace("%1",$data['first_name']." ".$data['last_name'],$smsbody);
                // $this->sendSms($data['mobile_number'],$smsbody);
            }
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
        }

    }


}
}
