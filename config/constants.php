<?php

return [
    'departments' => [
        1 => 'Electrical and Allied',
        2 => 'Civil and Allied',
        3 => 'Mechanical and Allied',
    ],
    'buildings' => [
        1 => 'Building 1',
        2 => 'Building 2',
        3 => 'Building 3',
    ],
    'facilities' => [
        'types' => [
            1 => 'Classroom',
            2 => 'Office',
            3 => 'Others',
        ],
    ],
    'allow_new_rfid' => env('ALLOW_NEW_RFID'),
    'admins' => env('ADMINS'),
    'raspberries' => env('RASP_IDS'),
    'google_client_id' => env('GOOGLE_CLIENT_ID'),
    'google_secret' => env('GOOGLE_CLIENT_SECRET'),
    'google_redirect' => env('GOOGLE_REDIRECT'),
];