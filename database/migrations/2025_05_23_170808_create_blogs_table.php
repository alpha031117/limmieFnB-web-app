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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            // Foreign key column for the chef (user)
            $table->unsignedBigInteger('author_id');
            $table->string('category')->nullable();

            // Set up foreign key constraint
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('image_url');
            $table->integer('rating')->default(0);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
