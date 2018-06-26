<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    public function up()
    {
        Schema::create('books_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('books_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('file_path');
            $table->string('status');
            $table->integer('genre_id')->references('id')->on('books_genres')->onDelete('RESTRICT');
            $table->integer('author_id')->references('id')->on('books_authors')->onDelete('RESTRICT');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
        Schema::dropIfExists('books_genres');
        Schema::dropIfExists('books_authors');
    }
}
