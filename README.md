# giantgiant-llc/advanced-email-validation


Advanced Email Validation module: 
 1. Blocks customer record creation by running extended validation on submitted email addresses
 2. Blocks order creation from the /rest/[store]/V1/guest-carts API by validating email address
 
# Requirements

 1. Magento >= 2.4.5
 2. PHP >= 8.1

# Installation
  `composer require giantgiant-llc/advanced-email-validation`
  
  `bin/magento setup:upgrade`
  
 # Configuration
   1. Go to Admin > Stores > GiantGiant 
   2. Add domains to block as needed (example.com, test.com, etc.)
   3. Customer Registration validation, enable / disable as needed.
   4. Order email validation, enable / disable as needed.
   
   Settings include 'example.com' and 'test.com', and validation is enabled by default.
