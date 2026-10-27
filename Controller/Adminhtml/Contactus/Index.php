<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Controller\Adminhtml\Contactus;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Magelearn_Contactus::manage';

    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magelearn_Contactus::manage');
        $resultPage->getConfig()->getTitle()->prepend(__('Contact Us Submissions'));

        return $resultPage;
    }
}
