<?php
/**
 * Copyright ©2023 GiantGiant, LLC All rights reserved.
 * See LICENSE.txt for license details (https://mit-license.org).
 */

namespace GiantGiantLLC\AdvancedEmailValidation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Validator\EmailAddress;

class Data extends AbstractHelper
{
    /**
     * Config paths
     */
    const BLOCKED_DOMAINS_ENABLED = 'giantgiant_advanced_email_validation/dns_domain/enable';
    const BLOCKED_DOMAINS = 'giantgiant_advanced_email_validation/dns_domain/domains_to_block';
    const CUSTOMER_REGISTRATION_VALIDATION_ENABLED = 'giantgiant_advanced_email_validation/validate_customer/enable';
    const GUEST_API_VALIDATION_ENABLED = 'giantgiant_advanced_email_validation/validate_guest_api/enable';

    public function __construct(
        Context $context,
        EmailAddress $emailValidator
    )
    {
        parent::__construct($context);

        $emailValidator->setOptions([
            'domain' => 1,
            'mx' => 1,
            'deep' => 1
        ]);

        $this->emailValidator = $emailValidator;
    }

    /**
     * Check to see if registration validation is enabled on a specific store
     *
     * @param null $storeId
     * @return bool
     */
    public function getRegistrationCheck($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::CUSTOMER_REGISTRATION_VALIDATION_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    /**
     * Check to see if validation is enabled for the Guest API on a specific store
     *
     * @param null $storeId
     * @return bool
     */
    public function getGuestApiIsEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::GUEST_API_VALIDATION_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Check if domain blocking is enabled
     *
     * @param null $storeId
     * @return bool
     */
    public function blockedDomainsEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::BLOCKED_DOMAINS_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Basic validation using Zend_Validate_EmailAddress with
     * extra options
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email): bool
    {
        if (!$this->emailValidator->isValid($email)) {
            return false;
        }

        if ($this->blockedDomainsEnabled() && $this->isDomainBlocked($email)) {
            return false;
        }

        return true;

    }

    /**
     * Check if domain is blocked
     *
     * @param string $email
     * @return bool
     */
    public function isDomainBlocked(string $email): bool
    {
        $emailDomain = substr($email, strpos($email,'@')+1);
        return in_array($emailDomain, $this->getBlockedDomains());
    }

    /**
     * Get array of domains to block
     *
     * @param null $storeId
     * @return string[]
     */
    public function getBlockedDomains($storeId = null): array
    {
        $blockedDomains = $this->scopeConfig->getValue(self::BLOCKED_DOMAINS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        return explode(',', $blockedDomains);
    }
}
