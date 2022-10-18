<?php
namespace FME\DeleteMyAccount\Block;
class RenderHeaderLink extends \Magento\Framework\View\Element\Template
{
    protected $Http;
    public $helper;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $Http,
        \FME\DeleteMyAccount\Helper\Data $helper,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->Http=$Http;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }
    public function getCustomerId()
    {
       return $this->Http->getValue(\FME\DeleteMyAccount\Model\Customer\Context::CONTEXT_CUSTOMER_ID);
    }
}
