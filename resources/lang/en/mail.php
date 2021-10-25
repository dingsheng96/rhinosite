<?php

return [

    'greeting' => 'Dear :name',

    'verify_account' => [
        'subject' => 'Verify Email Address',
        'action' => 'Verify Email Address',
        'merchant' => [
            'line_1' => 'Thank you for your application. You can now proceed to complete your profile by clicking the confirmation button/link and start filling in mandatory fields and upload all required documents to avoid any issues/delay in your applications.',
            'line_2' => 'Our team will take approximately 5-7 working days to process your application, this is a Rhinosite due-diligence process to ensure that our contractors are trustable and reliable for their potential customers.',
            'line_3' => 'Once your application is approved, you can log into your dashboard to proceed to subscription payment.',
            'line_4' => 'If in any circumstances that you are still in doubt, we do have a support team to further assist you with your queries. Please drop us an email at info@rhinosite.com.my or call us at 016-303 1808.'
        ],
        'member' => [
            'line_1' => 'Thank you for your application. You can now proceed to complete your profile by clicking the confirmation button/link and start filling in mandatory fields and upload all required documents to avoid any issues/delay in your applications.',
            'line_2' => 'Our team will take approximately 5-7 working days to process your application, this is a Rhinosite due-diligence process to ensure that our contractors are trustable and reliable for their potential customers.',
            'line_3' => 'Once your application is approved, you can log into your dashboard to proceed to subscription payment.',
            'line_4' => 'If in any circumstances that you are still in doubt, we do have a support team to further assist you with your queries. Please drop us an email at info@rhinosite.com.my or call us at 016-303 1808.'
        ]
    ],

    'company_verified' => [
        'subject' => 'Profile Verification :status',
        'rejected' => [
            'line_1' => " ",
            'line_2' => "On behalf of Rhinosite’s Team, we would like to inform you that your application has been rejected.",
            'line_3' => "Please note that you may login to update your company profile and resubmit the application to us.",
            'line_4' => "Our team will take approximately 5-7 working days to process your application. Once your application is approved, you can log into your dashboard to proceed to subscription payment.",
            'line_5' => "If in any circumstances that you are still in doubt, we do have a support team to further assist you with your queries. Please drop us an email at info@rhinosite.com.my or call us at 016-3031808.",
            'line_6' => " ",
            'line_7' => " ",
            'line_8' => " ",
            'line_9' => " ",
        ],
        'approved' => [
            'line_1' => "Congratulations and Welcome onboard to Rhinosite!",
            'line_2' => "On behalf of Rhinosite’s Team, we would like to inform you that your application has been approved.",
            'line_3' => "Please proceed to make the necessary bank transfer according to our Company details given below:-",
            'line_4' => "Bank Name: UOB BANK",
            'line_5' => "Account Number: 2273033620",
            'line_6' => "Swift Code: UOVBMYKL",
            'line_7' => "Company Name: En Vivo Sdn Bhd",
            'line_8' => "Country: Malaysia",
            'line_9' => "Kindly send us the proof of payment once the payment has been processed. Once payment has been processed, your account will be activated and you can start creating projects!",
        ]
    ],

    'free_trial' => [
        'subject'   =>  'Profile Verification :status',
        'action'    =>  'Create Project Now',
        'line_1'    =>  "Congratulations and Welcome onboard to Rhinosite!",
        'line_2'    =>  "On behalf of Rhinosite’s Team, we would like to inform you that your application has been approved.",
        'line_3'    =>  "Please note that your free trial is valid for 30 days upon signing up with Rhinosite. Rhinosite Team will send you a reminder to renew your subscription prior to your expiry date.",
        'line_4'    =>  "Thereafter, you may click the button below to start creating your projects.",
    ],

    'pre_expired' => [
        'subject'   =>  'Your Subscription Almost Expired',
        'line_1'    =>  "Please kindly be reminded that your subscription will be expiring in 3 days. There will be an automated email sent to you on the day of your subscription expiry as well.",
        'line_2'    =>  "On behalf of Rhinosite Team, we would like to express our gratitude towards your support. Hence, please share with us your feedbacks or comments (if any) so we can continue improving & serve you better."
    ],

    'expired' => [
        'subject'   =>  'Your Subscription Expired',
        'line_1'    =>  "We would like to remind you that your subscription on Rhinosite is expiring today. If you have the intention to renew your subscription, please click the button below to select your subscription plan and thereafter proceed to payment.",
        'line_2'    =>  "Based on our statistics over the past month, we are happy to inform that more than 50% of our contractors have received an average of 5 to 10 enquiries. We look forward to your continuous support so that Rhinosite is able to grow to its full potential in the next couple of months.",
        'action'    =>  "PROCEED SUBSCRIPTION"
    ],

    'three_days_after_expired' => [
        'subject'   =>  'Your Subscription Expired',
        'line_1'    =>  "We understand that you might be busy during this time. However, we would like to remind you that your subscription has already expired on Rhinosite.",
        'line_2'    =>  "Please click the button below to select your subscription plan and thereafter proceed to payment in order to renew your account. No worries, your account is still with us but unfortunately it will not be publicly available until you have renewed your subscription.",
        'line_3'    =>  "We would like to highlight that your account will be permanently removed from our system after 7 days of expiry.",
        'action'    =>  "PROCEED SUBSCRIPTION"
    ],

    'six_days_after_expired' => [
        'subject'   =>  'Your Subscription Expired',
        'line_1'    =>  "We would like to remind you that your subscription has already expired on Rhinosite. Please let us know if there is anything we can help to assist in speeding up your renewal process.",
        'line_2'    =>  "Please click the button below to select your subscription plan and thereafter proceed to payment in order to renew your account. We would like to highlight that your account will be permanently removed from our system after 7 days of expiry.",
        'action'    =>  "PROCEED SUBSCRIPTION"
    ],

    'account_deactivate' => [
        'subject'   =>  'Your Account Has Been Deactivated',
        'line_1'    =>  "We have not receive your intention to renew. Hence, we are sorry to inform that your account has been permanently removed from Rhinosite.",
        'line_2'    =>  "If you have require any further assistance, please do not hesitate to get in touch with us or visit [www.rhinosite.com.my](https://rhinosite.com.my) for more information."
    ],

    'free_tier' => [
        'subject'   =>  "Welcome to Rhinosite!",
        'line_1'    =>  "**Congratulations and Welcome onboard to Rhinosite!**",
        'line_2'    =>  "On behalf of Rhinosite’s Team, we would like to inform you that your company profile has been successfully uploaded onto our website.",
        'line_3'    =>  "Rhinosite is designed to help: - ",
        'line_4'    =>  "1) Increase your exposure and presence online",
        'line_5'    =>  "2) You liaise with your clients in a more efficient way",
        'line_6'    =>  "3) Build a long-term relationship with your clients",
        'line_7'    =>  "Click here to view and get started on our website: [www.rhinosite.com.my](https://rhinosite.com.my)",
        'line_8'    =>  "Should you have any further enquiries, please do not hesitate to contact our support team via info@rhinosite.com.my or 016-303 1808.",
        'line_9'    =>  "Thank you!"
    ]
];
