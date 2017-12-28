<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Services\Vocabulary;

class CreateTermsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('term_id');
            $table->integer('dictionary_id');
            $table->string('value');
            $table->timestamps();
        });

        $vocabulary = new Vocabulary();

        // Card stock
        $termsStock = ['Embossed', 'Smooth', 'Thick', 'Thin'];
        foreach ($termsStock as $term) {
            $vocabulary->getTerm('card_stock', $term);
        }

        // Colors
        $termsColors = ['Red', 'Orange', 'Yellow', 'Green', 'Blue', 'Indigo', 'Violet', 'Black', 'Gold', 'Silver', 'Bronze', 'White'];
        foreach ($termsColors as $term) {
            $vocabulary->getTerm('colors', $term);
        }

        // Styles
        $termsStyles = ['Border', 'Bleed', 'Pattern', 'Marked', 'Gilded'];
        foreach ($termsStyles as $term) {
            $vocabulary->getTerm('styles', $term);
        }

        // Box features
        $termsFeatures = ['Foiled', 'Embossed', 'Hologram', 'Diecut', 'Debossed', 'Interactive'];
        foreach ($termsFeatures as $term) {
            $vocabulary->getTerm('features', $term);
        }

        // Themes
        $termsThemes = ['Nautical', 'War', 'Animal', 'Luxury'];
        foreach ($termsThemes as $term) {
            $vocabulary->getTerm('themes', $term);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('terms');
    }
}
