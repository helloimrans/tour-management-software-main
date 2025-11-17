<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

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
            'tour' => 'm,c,r,u,d,change_status',
            'settings' => 'm',
        ],
        'admin' => [
            'dashboard' => 'm',
            'user-management' => 'm',
            'admin-user' => 'm,c,r,u,d,change_status',
            'general-user' => 'm,change_status',
            'roles' => 'c,r,u,d,m,change_permission',
            'permissions' => 'c,r,u,d,m',
            'radio-stations' => 'm,c,r,u,d,change_status',
            'tour' => 'm,c,r,u,d,change_status',
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
