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
        'subject' => 'Company Profile Verified :status',
        'rejected' => [
            'line_1' => " ",
            'line_2' => "On behalf of Rhinosite’s Team, we would like to inform you that your application has been rejected.",
            'line_3' => "Please note that you may login to update your company profile and resubmit to us.",
            'line_4' => "Our team will take approximately 5-7 working days to process your application. Once your application is approved, you can log into your dashboard to proceed to subscription payment.",
            'line_5' => "If in any circumstances that you are still in doubt, we do have a support team to further assist you with your queries. Please drop us an email at info@rhinosite.com.my or call us at 016-3031808.",
        ],
        'approved' => [
            'line_1' => "Congratulations and Welcome onboard to Rhinosite!",
            'line_2' => "On behalf of Rhinosite’s Team, we would like to inform you that your application has been approved.",
            'line_3' => "Please note that you may log into your dashboard to proceed to subscription payment.",
            'line_4' => "Once payment has been processed, you can start creating projects!",
            'line_5' => " ",
        ]
    ],
];
