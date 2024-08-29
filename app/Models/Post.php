<?php

namespace App\Models;

use App\Core\Model;

class Post extends Model {
    protected int|null $id;
    protected int $user_id;
    protected string $title;

    public function user() {
        return $this->belongsTo(User::class);
    }
}