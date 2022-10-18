<?php

namespace FME\DeleteMyAccount\Observer\Customer;

class RemoveContact implements \Magento\Framework\Event\ObserverInterface
{

	protected $r_deletemyaccount;
	protected $authSession;
	protected $Helper;
	const TTL = 86400;

	public function __construct(
		\Magento\Backend\Model\Auth\Session $authSession, 
		\FME\DeleteMyAccount\Helper\Data $Helper,
		\FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount $r_deletemyaccount
	) {
		$this->r_deletemyaccount = $r_deletemyaccount;
		$this->Helper = $Helper;
		$this->authSession = $authSession;
	}
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$customer = $observer->getEvent()->getCustomer();
		$bool = $this->r_deletemyaccount->checkRequestToDelete($customer->getId());

		
		if($bool) {
			$data['action_admin_id'] = $this->authSession->getUser()->getId();
			$data['action_admin_name'] = $this->authSession->getUser()->getName();
			$data['customer_id'] = $customer->getId();
			$data['deletion_date'] = date('Y-m-d H:i:s', time() + self::TTL);

			$this->r_deletemyaccount->updateAccountDetails($data);
			$data = $this->r_deletemyaccount->getAccountbyId($customer->getId());

			
			$this->Helper->sendCustomerEmailOnDelete($data);


		}
		return ;
	}
}