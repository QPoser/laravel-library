<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('review');
            $table->string('status');
            $table->integer('stars');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('book_id')->references('id')->on('books')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books_reviews');
    }
}
