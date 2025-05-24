<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Recipe extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name', 'description', 'difficulty', 'duration', 'rating', 'category',
        'prep_time', 'cook_time', 'instruction', 'ingredients', 'nutrition',
        'servings', 'chef_name', 'image_url'
    ];

    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'description', 'difficulty', 'duration', 'rating', 'category',
                'prep_time', 'cook_time', 'instruction', 'ingredients', 'nutrition',
                'servings', 'image_url'
            ])
            ->useLogName('recipe')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Recipe has been {$eventName}");
    }

        public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hasInappropriateReview(): bool
    {
        return $this->reviews->contains(function ($review) {
            return $review->isInappropriate();
        });
    }
}
