<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'difficulty', 'duration', 'rating', 'category', 'prep_time', 'cook_time', 'intruction', 'ingredients', 'nutrition', 'servings', 'chef_name', 'image'];
}
