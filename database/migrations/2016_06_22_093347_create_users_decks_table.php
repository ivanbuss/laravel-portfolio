<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_decks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('deck_id')->unsigned();
            $table->text('notes');
            $table->integer('weight_collection');
            $table->integer('weight_wishlist');
            $table->integer('weight_tradelist');
            $table->integer('in_collection')->nullable();
            $table->integer('in_wishlist')->nullable();
            $table->integer('in_tradelist')->nullable();
            $table->timestamp('added_at_collection')->nullable();
            $table->timestamp('added_at_wishlist')->nullable();
            $table->timestamp('added_at_tradelist')->nullable();
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
        Schema::drop('users_decks');
    }
}
