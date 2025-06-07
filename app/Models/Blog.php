<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Blog extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name', 'description', 'author_name', 'image_url', 'author_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'description', 'image_url'
            ])
            ->useLogName('blog')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Blog Post has been {$eventName}");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function hasInappropriateComment(): bool
    {
        foreach ($this->comments as $comment) {
            if ($comment->isInappropriate()) {
                return true;
            }
        }
        return false;
    }
}
