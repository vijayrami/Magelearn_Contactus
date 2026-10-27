<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Controller\Adminhtml\Contactus;

use Magelearn\Contactus\Model\ContactusFactory;
use Magelearn\Contactus\Model\ResourceModel\Contactus as ContactusResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;

class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Magelearn_Contactus::delete';

    public function __construct(
        Context $context,
        private readonly ContactusFactory $contactusFactory,
        private readonly ContactusResource $contactusResource
    ) {
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int) $this->getRequest()->getParam('id');

        if ($id <= 0) {
            $this->messageManager->addErrorMessage(__('The contact submission could not be found.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $contact = $this->contactusFactory->create();
            $this->contactusResource->load($contact, $id);

            if (!$contact->getId()) {
                throw new \RuntimeException('Contact submission does not exist.');
            }

            $this->contactusResource->delete($contact);
            $this->messageManager->addSuccessMessage(__('The contact submission has been deleted.'));
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while deleting the submission.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
