<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->integer('front_img_id')->nullable();
            $table->integer('back_img_id')->nullable();
            $table->text('description');
            $table->integer('company_id')->nullable();
            $table->string('edition');
            $table->integer('collection_id')->nullable();
            $table->integer('release_year')->nullable();
            $table->string('prod_run');
            $table->integer('printer_id')->nullable();
            $table->integer('artist_id')->nullable();
            $table->string('finish');
            $table->string('customization');
            $table->integer('nid')->comment('Old node id from drupal site');
            $table->timestamp('launch_date')->nullable();
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
        Schema::drop('decks');
    }
}
