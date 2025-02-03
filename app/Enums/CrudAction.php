<?php

namespace App\Enums;

enum CrudAction: string
{
    case INDEX = 'index';
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
