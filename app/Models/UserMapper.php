<?php

namespace App\Models;

use App\Core\DataMapper;
use App\Models\User;

class UserMapper extends DataMapper {
    protected string $tableName = "users";
    protected string $class = User::class;
}