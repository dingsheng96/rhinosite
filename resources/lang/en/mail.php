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
];
