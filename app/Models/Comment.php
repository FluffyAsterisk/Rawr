<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model {
    protected int|null $id;
    protected int $user_id;
    protected string $comment;
}
