<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('difficulty', ['Easy', 'Medium', 'Hard']);
            $table->integer('duration');  // Duration in minutes
            $table->decimal('rating', 3, 2);
            $table->string('category');
            $table->integer('prep_time');
            $table->integer('cook_time');
            $table->text('instruction');
            $table->text('ingredients');
            $table->text('nutrition');
            $table->integer('servings');
            // Foreign key column for the chef (user)
            $table->unsignedBigInteger('chef_id');

            // Set up foreign key constraint
            $table->foreign('chef_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('image_url');
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
