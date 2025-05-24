<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'recipe_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function isInappropriate(): bool
    {
        $badWords = ['shit', 'fuck', 'offensive', 'curse']; // add your list here

        foreach ($badWords as $word) {
            if (stripos($this->comment, $word) !== false) {
                return true;
            }
        }
        return false;
    }

}


