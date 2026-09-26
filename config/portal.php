<?php

return [

    'public_site_url' => env('PUBLIC_SITE_URL', 'https://www.standforhumanityfoundation.org/'),

    'contact_email' => env('FOUNDATION_CONTACT_EMAIL', 'info@standforhumanityfoundation.org'),

    'admin' => [
        'name' => env('PORTAL_ADMIN_NAME', 'Portal Administrator'),
        'email' => env('PORTAL_ADMIN_EMAIL', 'info@standforhumanityfoundation.org'),
        'password' => env('PORTAL_ADMIN_PASSWORD'),
    ],

    'login_visuals' => [
        ['wash' => '#0020B0', 'soft' => '#3d5cff', 'accent' => '#FFAC00', 'image' => 'images/portal/stand2.jpg'],
        ['wash' => '#8A5A00', 'soft' => '#FFAC00', 'accent' => '#FFE08A', 'image' => 'images/portal/stand4.jpg'],
        ['wash' => '#4A148C', 'soft' => '#9C4DFF', 'accent' => '#E1BEE7', 'image' => 'images/portal/stand5.jpg'],
        ['wash' => '#004D40', 'soft' => '#1DB8A6', 'accent' => '#80CBC4', 'image' => 'images/portal/stand1.jpg'],
        ['wash' => '#880E4F', 'soft' => '#E91E63', 'accent' => '#F8BBD0', 'image' => 'images/portal/stand3.jpg'],
        ['wash' => '#BF360C', 'soft' => '#FF7043', 'accent' => '#FFCCBC', 'image' => 'images/portal/carousel-1.jpg'],
        ['wash' => '#1B5E20', 'soft' => '#43A047', 'accent' => '#A5D6A7', 'image' => 'images/portal/about.jpg'],
        ['wash' => '#0A1628', 'soft' => '#1565C0', 'accent' => '#90CAF9', 'image' => 'images/portal/pic4.jpg'],
    ],

];
