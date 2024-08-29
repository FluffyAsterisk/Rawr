<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Post;

class User extends Model {
    protected $tableName = 'users';
    protected int|null $id = null;
    protected string $login;
    protected string $password;
    protected int $role;

    public function posts() {
        return $this->hasMany(Post::class);
    }
}