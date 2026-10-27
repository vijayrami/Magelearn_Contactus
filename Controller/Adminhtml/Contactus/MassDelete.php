<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Controller\Adminhtml\Contactus;

use Magelearn\Contactus\Model\ResourceModel\Contactus\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Magelearn_Contactus::delete';

    public function __construct(
        Context $context,
        private readonly Filter $filter,
        private readonly CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Backend\Model\View\Result\Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        try {
            foreach ($collection as $contact) {
                $contact->delete();
            }
            $this->messageManager->addSuccessMessage(
                __('A total of %1 contact submission(s) have been deleted.', $collectionSize)
            );
        } catch (\Throwable $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while deleting submissions.'));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
