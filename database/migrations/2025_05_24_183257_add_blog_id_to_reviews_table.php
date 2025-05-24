<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('reviews', function (Blueprint $table) {
        $table->unsignedBigInteger('blog_id')->after('id');

        $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
    });
}

};
