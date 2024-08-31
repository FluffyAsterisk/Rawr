<?php

namespace App\Models;
use App\Core\Repository;

class UserRepository extends Repository
{
    protected string $table = "users";
}