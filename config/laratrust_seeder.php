<?php


use App\Enums\CrudAction;
use App\Enums\Permission;
use App\Enums\Role;

return [
    // default laratrust crud permission map
    'roles_structure' => [
        Role::ADMIN->value => [
            'users' => 'i,c,r,u,d',
            'roles' => 'i,c,r,u,d',
            'permissions' => 'i,r,u',
            'channels' => 'i,r,d',
            'monitors' => 'i,c,r,u,d',
            'teams' => 'i,c,r,u,d',
            'monitor_events' => 'i,c,r,u,d',
            'monitor_uptime_event_counts' => 'i,c,r,u,d',
        ],
    ],
    'permissions_map' => [
        'i' => CrudAction::INDEX->value,
        'c' => CrudAction::CREATE->value,
        'r' => CrudAction::READ->value,
        'u' => CrudAction::UPDATE->value,
        'd' => CrudAction::DELETE->value,
    ],
    // non-crud related permissions
    'role_permissions' => [
        Role::ADMIN->value => [
            Permission::ACCESS_ADMINISTRATION_PANEL->value,
        ]
    ],
];
