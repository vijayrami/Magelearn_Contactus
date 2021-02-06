<?php

namespace Magelearn\Contactus\Plugin;

/**
 * Class ContactusPlugin
 * @package Magelearn\Contactus\Plugin
 */

class ContactusPlugin
{    
    /**
     * @var \Magelearn\Contactus\Model\Contactus
     */
    protected $contactus;
 
    /**
     * Plugin constructor.
     *
     * @param \Magelearn\Contactus\Model\Contactus $contactus
     */
    public function __construct(
        \Magelearn\Contactus\Model\Contactus $contactus       
    ) {
        $this->contactus = $contactus;        
    }

    public function aroundExecute(\Magento\Contact\Controller\Index\Post $subject, \Closure $proceed)
    {   
        $request = $subject->getRequest()->getPostValue();
        if ($this->validatedParams($request)) {
            $this->contactus->setData($request); 
            $this->contactus->save();
        }
        $returnValue = $proceed();
        return $returnValue;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function validatedParams($request)
    {
        if (trim($request['name']) === '') {
            throw new LocalizedException(__('Enter the Name and try again.'));
        }
        if (trim($request['comment']) === '') {
            throw new LocalizedException(__('Enter the comment and try again.'));
        }
        if (false === \strpos($request['email'], '@')) {
            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));
        }
        if (trim($request['hideit']) !== '') {
            throw new \Exception();
        }
        return $request;
    }
}