<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
    'superadmin' => [
            'dashboard' => 'm',
            'user-management' => 'm',
            'admin-user' => 'm,c,r,u,d,change_status',
            'general-user' => 'm,change_status',
            'roles' => 'c,r,u,d,m,change_permission',
            'permissions' => 'c,r,u,d,m',
            'radio-stations' => 'm,c,r,u,d,change_status',
            'music-categories' => 'm,c,r,u,d,f,change_status',
            'music' => 'm,c,r,u,d,f,change_status',
            'live-comments' => 'm,change_status,f',
            'service-management' => 'm',
            'service-categories' => 'm,c,r,u,d,change_status',
            'services' => 'm,c,r,u,d,change_status',
            'service-providers' => 'm,c,r,u,d,change_status',
            'viewers' => 'm',
            'radio-sliders' => 'c,r,u,d,m',
            'point-withdraw-request' => 'm,r,approve,reject',
            'settings' => 'm',
            'mail-management' => 'm',
        ],
        'admin' => [
            'radio-stations' => 'm,c,r,u,d,change_status',
            'music-categories' => 'm,c,r,u,d,f,change_status',
            'music' => 'm,c,r,u,d,f,change_status',
            'live-comments' => 'm,change_status',
            'service-management' => 'm',
            'service-categories' => 'm,c,r,u,d,change_status',
            'services' => 'm,c,r,u,d,change_status',
            'service-providers' => 'm,c,r,u,d,change_status',
            'viewers' => 'm',
            'mail-management' => 'm',
            'settings' => 'm',
        ],
        'user' => [

        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'a' => 'attach',
        'm' => 'menu',
        'f' => 'filter',
        'approve' => 'approve',
        'reject' => 'reject',
        'change_permission' => 'change-permission',
        'change_status' => 'change-status',
    ],
];
