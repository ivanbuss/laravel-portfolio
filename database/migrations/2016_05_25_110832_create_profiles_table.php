<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('country')->nullable();
            $table->integer('avatar_img')->nullable();
            $table->integer('background_img')->nullable();
            $table->integer('points')->nullable();
            $table->text('bio')->nullable();
            $table->tinyInteger('rank');
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
        Schema::drop('profiles');
    }
}
