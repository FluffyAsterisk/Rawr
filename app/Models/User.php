<?php

namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected int|null $id = null;
    protected string $login;
    protected string $password;
    protected int $role;
}