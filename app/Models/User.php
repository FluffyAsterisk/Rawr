<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Post;

class User {
    protected int|null $id = null;
    protected string $login;
    protected string $password;
    protected int $role;
}