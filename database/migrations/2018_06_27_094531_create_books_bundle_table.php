<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksBundleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books_bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
        });

        Schema::create('books_bundles_assignment', function (Blueprint $table) {
            $table->integer('bundle_id')->references('id')->on('books_bundle')->onDelete('CASCADE');
            $table->integer('book_id')->references('id')->on('books')->onDelete('CASCADE');
            $table->primary(['bundle_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books_bundle');
        Schema::dropIfExists('books_bundle_assignment');
    }
}
