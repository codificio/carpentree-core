<?php


return [

    'pagination' => [
        'per_page' => 15
    ],

    /**
     * Permissions
     */
    'permissions' => [
        'users' => [
            'create',
            'read',
            'update',
            'delete',
            'manage-permissions',
            'manage-roles'
        ],

        'permissions' => [
            'read'
        ],

        'roles' => [
            'read'
        ]

        // ...
    ]
];
