<?php

namespace FME\DeleteMyAccount\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('delete_account_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('FME\DeleteMyAccount\Model\DeleteMyAccount');
                $model->load($id);
                $contact_name = $model->getFirstName();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The record has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_deletemyaccount_on_delete',
                    ['contact_name' => $contact_name, 'status' => 'success']
                );
                return $resultRedirect->setPath('deletemyaccount/index/report');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_deletemyaccount_on_delete',
                    ['contact_name' => $contact_name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('deletemyaccount/index/edit', ['delete_account_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a record to delete.'));
        // go to grid
        return $resultRedirect->setPath('deletemyaccount/index/report');
    }
}
