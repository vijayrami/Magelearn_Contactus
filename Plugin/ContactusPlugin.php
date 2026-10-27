<?php

declare(strict_types=1);

namespace Magelearn\Contactus\Plugin;

use Magelearn\Contactus\Model\ContactusFactory;
use Magelearn\Contactus\Model\ResourceModel\Contactus as ContactusResource;
use Magento\Contact\Controller\Index\Post;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ContactusPlugin
{
    public function __construct(
        private readonly ContactusFactory $contactusFactory,
        private readonly ContactusResource $contactusResource,
        private readonly StoreManagerInterface $storeManager,
        private readonly LoggerInterface $logger
    ) {
    }

    public function afterExecute(Post $subject, mixed $result): mixed
    {
        if (!$subject->getRequest()->isPost()) {
            return $result;
        }

        $request = $subject->getRequest();

        $data = [
            'name' => trim((string) $request->getParam('name', '')),
            'email' => trim((string) $request->getParam('email', '')),
            'telephone' => trim((string) $request->getParam('telephone', '')),
            'comment' => trim((string) $request->getParam('comment', '')),
            'store_id' => (int) $this->storeManager->getStore()->getId(),
        ];

        if (
            $data['name'] === ''
            || $data['email'] === ''
            || $data['comment'] === ''
        ) {
            return $result;
        }

        try {
            $contact = $this->contactusFactory->create();
            $contact->setData($data);

            $this->contactusResource->save($contact);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Unable to save Magento contact form submission.',
                ['exception' => $exception]
            );
        }

        return $result;
    }
}