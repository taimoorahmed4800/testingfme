<?php

namespace FME\DeleteMyAccount\Model\Api;
use FME\DeleteMyAccount\Api\DeleteMyAccountInterface;
class DeleteMyAccount implements DeleteMyAccountInterface
{
	protected $request;
	protected $resourceModel;
	public function __construct(
		\FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount $resourceModel,
		\Magento\Framework\Webapi\Rest\Request $request
	) {
		$this->request = $request;
		$this->resourceModel = $resourceModel;
	}
	public function getAllAccountsRequests() {
		return $this->resourceModel->getAllAccountsRequests();
	}
	public function setAccountForDelete() {
		$params = $this->request->getBodyParams();
		return $this->resourceModel->setAccountForDelete($params);
	}
	public function getAccountbyEmail() {
		$params = $this->request->getBodyParams();
		return $this->resourceModel->getAccountbyEmail($params);
	}
}
