<?php

namespace App\Models;

use App\Core\DataMapper;
use App\Core\Model;
use App\Models\User;

class UserMapper extends DataMapper {
    protected function getMappedClass(): Model {
        return new User;
    }
}