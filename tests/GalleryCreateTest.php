<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class GalleryCreateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $user = User::where('email', 'iwanbuss@gmail.com')->first();
        if (!$user) {
            $user = User::create(['name'=>'Ivan', 'email'=>'iwanbuss@gmail.com', 'password'=>bcrypt('150192')]);
        }

        $pathToGalleryFile = 'public/files/test_img1.jpg';
        $this->actingAs($user)->visit('/deck/gallery/add')
            ->attach($pathToGalleryFile, 'gallery_image')
            ->select('Box', 'gallery_tag')
            ->select('Front', 'gallery_tag_box')
            ->select('Left', 'gallery_tag_box_side')
            ->select('Back Design', 'gallery_tag_card')
            ->select('Spade', 'gallery_tag_card_type')
            ->select('King', 'gallery_tag_card_court')
            ->select('2', 'gallery_tag_card_pip')
            ->select('A', 'gallery_tag_card_joker')
            ->press('Upload')
            ->seeInDatabase('gallery_items', [
                'user_id' => $user->id,
                'deck_id' => 0,
            ]);
    }
}
