<?php
/**
 * Copyright ©2023 GiantGiant, LLC All rights reserved.
 * See LICENSE.txt for license details (https://mit-license.org).
 */

namespace GiantGiantLLC\AdvancedEmailValidation\Plugin;

use GiantGiantLLC\AdvancedEmailValidation\Helper\Data;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Phrase;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\Webapi\Rest\Request as RestRequest;

class ValidateCustomerEmail
{
    /**
     * @var Data
     */
    protected Data $validationHelper;

    /**
     * @param RestRequest $request
     * @param UserContextInterface|null $userContext
     */
    public function __construct(
        RestRequest $request,
        Data $validationHelper,
        ?UserContextInterface $userContext = null)
    {
        $this->request = $request;
        $this->validationHelper = $validationHelper;
        $this->userContext = $userContext ?? ObjectManager::getInstance()->get(UserContextInterface::class);
    }

    /**
     * Check Customer email
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInterface $customer
     * @param string|null $passwordHash
     * @return mixed
     */
    public function beforeSave(
        CustomerRepositoryInterface $customerRepository,
        CustomerInterface $customer,
        ?string $passwordHash = null
    ): array {

        if (!$this->validationHelper->getRegistrationCheck()) {
            return [$customer, $passwordHash];
        }

        $email = $this->request->getParam('email');
        if (
            $email &&
            $this->validationHelper->validateEmail($email)
        ) {
            return [$customer, $passwordHash];
        }

        throw new ValidationException(__('Unable to save customer. Email address is not valid.'));

    }

}
