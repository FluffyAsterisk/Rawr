<?php

namespace App\Models;
use App\Core\Repository;

class UserPostRepository extends Repository
{
    protected string $table = "users";
    protected string $modelClass = User::class;
    protected array $usedModels = [Post::class, Comment::class];
}
