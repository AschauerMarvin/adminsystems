<?php
return [
    'Settings' => [
        'AvailableSettings' => ['Branding'],
        'BooleanSettings' => ['Branding.licence', 'Branding.usedSoftware'],
        'Descriptions' => [
            'Branding' => [
                'name' => __('The displayed short name of the system'),
                'longname' => __('The displayed long name of the system, used in the about page and title'),
                'logo' => __('The name of the logo, based on /webroot/img'),
                'slogan' => __('Catchy phrase to describe the application'),
                'url' => __('Webpage of the company / product'),
                'licence' => __('Display the licence in the about page, loads LICENCE.txt'),
                'usedSoftware' => __('Honorable mentions of other Open Source products in the about page'),
                'version' => __('Additional version number, e.g. for your own product'),
            ],
        ],
    ],
]
?>
