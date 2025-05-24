<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;


// Login & Signup Routes
Route::get('login', [UserController::class,'showLogin'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::get('register', [UserController::class, 'showRegister'])->name('register');
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');

// Blog Routes
Route::middleware(['auth', 'checkrole:user'])->group(function () {

    Route::get('/', [RecipeController::class, 'index'])->name('home');

    // Review Routes
    Route::post('/recipes/{recipe}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Recipe Routes
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');  
    Route::get('/recipes/{id}/edit', [RecipeController::class,'edit'])->name('recipes.edit');
    Route::put('/recipes/{id}', [RecipeController::class,'update'])->name('recipes.update');
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    Route::get('/recipes/my/{id}', [RecipeController::class, 'myRecipes'])->name('recipes.my');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');

});

Route::middleware(['auth', 'checkrole:admin'] )->group(function () {
    Route::get('/admin', [AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/recipe-logs', [AdminController::class, 'recipe_logs'])->name('admin.recipe.log');
    Route::post('/admin/recipes/{recipe}/undo', [AdminController::class, 'undoLastChange'])->name('admin.recipes.undo');
});



// Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');

