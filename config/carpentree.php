<?php


return [

    /**
     * Pagination: items per page
     */
    'items_per_page' => 10,

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
