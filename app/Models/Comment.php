<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'blog_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function isInappropriate(): bool
    {
        $badWords = ['shit', 'fuck', 'offensive', 'curse']; // add your list here

        foreach ($badWords as $word) {
            if (stripos($this->content, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
