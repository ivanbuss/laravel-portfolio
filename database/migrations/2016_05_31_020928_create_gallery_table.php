<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Services\Vocabulary;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('deck_id')->unsigned();
            $table->integer('image_id');
            $table->timestamps();
        });
        $vocabulary = new Vocabulary();
        $terms = ['Box', 'Front', 'Back', 'Interior', 'Side', 'Left', 'Right', 'Card', 'Back Design', 'Ace', 'Court',
            'Pip', 'Joker', 'Misc', 'Spade', 'Heart', 'Diamond', 'Club', 'Other', 'King', 'Queen', 'Jack', '2', '3',
            '4', '5', '6', '7', '8', '9', 'A', 'B', 'Photo'];
        foreach ($terms as $term) {
            $vocabulary->getTerm('gallery_tags', $term);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('gallery_items');
    }
}
