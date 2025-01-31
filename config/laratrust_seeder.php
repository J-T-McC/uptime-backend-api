<?php


use App\Models\Enums\Role;

return [
    'role_crud_permissions' => [
        Role::ADMIN->value => [
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'channels' => 'c,r,u,d',
            'monitors' => 'c,r,u,d',
            'teams' => 'c,r,u,d',
            'monitor_events' => 'c,r,u,d',
            'monitor_uptime_event_counts' => 'c,r,u,d',
        ],
    ],

    'role_permissions' => [
        // non-crud related permissions
        Role::ADMIN->value => []
    ],

    'crud_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
