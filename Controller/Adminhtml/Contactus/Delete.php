<?php
 
namespace Magelearn\Contactus\Controller\Adminhtml\Contactus;

use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Magelearn\Contactus\Controller\Adminhtml\Contactus
 */

class Delete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Magelearn_Contactus::contactus_delete';
    /**
     * @var \Magelearn\Contactus\Model\Contactus
     */
    protected $model;
    public function __construct(
        Action\Context $context,
        \Magelearn\Contactus\Model\Contactus $model
    ) {
        $this->model = $model;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                $this->model->load($id);
                $title = $this->model->getTitle();
                $this->model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The record has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_contactuspage_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_contactuspage_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a record to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}