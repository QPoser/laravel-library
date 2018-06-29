<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWritersSubscribesTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->smallInteger('is_writer');
        });

        Schema::create('writers_subscribers', function (Blueprint $table) {
            $table->integer('writer_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->integer('subscriber_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->primary(['writer_id', 'subscriber_id']);
        });
    }

    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
           $table->dropColumn('is_writer');
        });
        Schema::dropIfExists('writers_subscribes');
    }
}
