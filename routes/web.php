<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;

// Login & Signup Routes
Route::get('login', [UserController::class,'showLogin'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::get('register', [UserController::class, 'showRegister'])->name('register');
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');

// Blog Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', [RecipeController::class, 'index'])->name('home');

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


// Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');

