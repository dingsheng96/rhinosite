<?php

return [

    'merchant_code' => env('IPAY88_MERCHANT_CODE', 'M31863'),
    'merchant_key' => env('IPAY88_MERCHANT_KEY', 'ClIAPsX5de'),

    'payment_url' => env('IPAY88_PAYMENT_URL', 'https://payment.ipay88.com.my/epayment/entry.asp'),
    'payment_requery_url' => env('IPAY88_PAYMENT_REQUERY_URL', 'https://payment.ipay88.com.my/epayment/enquiry.asp'),
    'recurring_payment_url' => env('IPAY88_RECURRING_PAYMENT_URL', 'https://payment.ipay88.com.my/recurringpayment2.0/subscription.asp'),
    'terminate_recurring_payment_url' => env('IPAY88_TERMINATE_RECURRING_PAYMENT_URL', 'https://payment.ipay88.com.my/recurringpayment2.0/webservice/RecurringPayment.asmx?op=Termination'),

];
