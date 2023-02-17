<?php
/**
 * Copyright ©2023 GiantGiant, LLC All rights reserved.
 * See LICENSE.txt for license details (https://mit-license.org).
 */

namespace GiantGiantLLC\AdvancedEmailValidation\Plugin;

use GiantGiantLLC\AdvancedEmailValidation\Helper\Data;
use Magento\Framework\Webapi\Exception;
use Magento\Quote\Api\Data\AddressInterface;

class ValidateGuestEmailViaApi
{
    /**
     * @var Data
     */
    protected Data $validationHelper;

    /**
     * Grab the Helper
     *
     * @param Data $securityHelper
     */
    public function __construct(
        Data $validationHelper
    )
    {
        $this->validationHelper = $securityHelper;
    }


    /**
     * Block attempts to send bad email address data through the Guest Cart API
     *
     * @param \Magento\Checkout\Api\GuestPaymentInformationManagementInterface $subject
     * @param $cartId
     * @param $email
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return null
     * @throws Exception
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Api\GuestPaymentInformationManagementInterface $subject,
                                                                         $cartId,
                                                                         $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    )
    {
        if (!$this->validationHelper->validateEmail($email)) {
            throw new Exception(
                __('Unable to place order. Invalid Email Address.')
            );
        }

    }
}
