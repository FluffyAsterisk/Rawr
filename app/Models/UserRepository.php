<?php

namespace App\Models;
use App\Core\Repository;
use App\Models\Post;
use App\Models\User;

class UserRepository extends Repository
{
    protected string $table = "users";
}