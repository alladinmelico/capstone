<?php

return [
    'departments' => [
        1 => 'Bachelor of Engineering and Allied Department',
        2 => 'Basic Arts and Sciences Department',
        3 => 'Civil and Allied Department',
        4 => 'Electrical and Allied Department',
        5 => 'Mechanical and Allied Department',
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
