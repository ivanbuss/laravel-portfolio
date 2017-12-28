<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Deck;
use App\GalleryItem;
use App\Role;

class DeckCreateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $user = User::where('email', 'iwanbuss@gmail.com')->first();
        if (!$user) {
            $user = User::create(['name'=>'Ivan', 'email'=>'iwanbuss@gmail.com', 'password'=>bcrypt('150192')]);
            $role = Role::where('name', Role::ADMIN)->first();
            $user->attachRole($role);
        }

        $data = [];
        $data[] = [
            'name' => 'Test Deck 1',
            'front_img' => 'public/files/test_img1.jpg',
            'back_img' => 'public/files/test_img2.jpg',
            'description' => 'Deck description for test deck 1',
            'company' => 'Company 1',
            'edition' => 'Edition 1',
            'collection' => 'Collection 1',
            'release_year' => '2016',
            'prod_run' => '2500',
            'printer' => 'Printer 1',
            'artist' => 'Artist 1',
            'card_stock' => 'Aristocrat',
            'finish' => 'Magic finish',
            'court' => 'Standard',
            'features' => 'Color Foil, Emboss, Deboss, Holoram, Diecut',
            'colors' => 'White, Black, Bronze',
            'style' => 'Border',
            'themes' => 'Nautical, War, Animal, Luxury',
            'tags' => 'Tag1, Tag2, Tag3',
        ];
        $data[] = [
            'name' => 'Test Deck 2',
            'front_img' => 'public/files/test_img1.jpg',
            'back_img' => 'public/files/test_img2.jpg',
            'description' => 'Deck description for test deck 2',
            'company' => 'Company 2',
            'edition' => 'Edition 2',
            'collection' => 'Collection 2',
            'release_year' => '2016',
            'prod_run' => '2500',
            'printer' => 'Printer 2',
            'artist' => 'Artist 2',
            'card_stock' => 'Aristocrat',
            'finish' => 'Magic finish',
            'court' => 'Standard',
            'features' => 'Color Foil, Emboss, Holoram',
            'colors' => 'White, Bronze',
            'style' => 'Border',
            'themes' => 'Nautical, Animal, Luxury',
            'tags' => 'Tag2, Tag3, Tag4',
        ];
        $data[] = [
            'name' => 'Test Deck 3',
            'front_img' => 'public/files/test_img1.jpg',
            'back_img' => 'public/files/test_img2.jpg',
            'description' => 'Deck description for test deck 3',
            'company' => 'Company 3',
            'edition' => 'Edition 3',
            'collection' => 'Collection 3',
            'release_year' => '2016',
            'prod_run' => '2500',
            'printer' => 'Printer 3',
            'artist' => 'Artist 3',
            'card_stock' => 'Aristocrat',
            'finish' => 'Magic finish',
            'court' => 'Standard',
            'features' => 'Emboss, Holoram',
            'colors' => 'White, Black',
            'style' => 'Border',
            'themes' => 'Luxury',
            'tags' => 'Tag3, Tag4, Tag5',
        ];
        $data[] = [
            'name' => 'Test Deck 4',
            'front_img' => 'public/files/test_img1.jpg',
            'back_img' => 'public/files/test_img2.jpg',
            'description' => 'Deck description for test deck 4',
            'company' => 'Company 4',
            'edition' => 'Edition 4',
            'collection' => 'Collection 4',
            'release_year' => '2016',
            'prod_run' => '2500',
            'printer' => 'Printer 4',
            'artist' => 'Artist 4',
            'card_stock' => 'Aristocrat',
            'finish' => 'Magic finish',
            'court' => 'Standard',
            'features' => 'Color Foil, Holoram, Diecut',
            'colors' => 'Bronze',
            'style' => 'Border',
            'themes' => 'War, Animal',
            'tags' => 'Tag4, Tag5, Tag6',
        ];
        $data[] = [
            'name' => 'Test Deck 5',
            'front_img' => 'public/files/test_img1.jpg',
            'back_img' => 'public/files/test_img2.jpg',
            'description' => 'Deck description for test deck 5',
            'company' => 'Company 5',
            'edition' => 'Edition 5',
            'collection' => 'Collection 5',
            'release_year' => '2016',
            'prod_run' => '2500',
            'printer' => 'Printer 5',
            'artist' => 'Artist 5',
            'card_stock' => 'Aristocrat',
            'finish' => 'Magic finish',
            'court' => 'Standard',
            'features' => 'Color Foil, Deboss, Diecut',
            'colors' => 'Red, Yellow',
            'style' => 'Border',
            'themes' => 'War, Luxury',
            'tags' => 'Tag5, Tag6, Tag7',
        ];

        foreach($data as $item) {
            $deck = Deck::where('name', $item['name'])->first();
            if ($deck) {
                $deck->delete();
            }

            $gallery_item = GalleryItem::where('user_id', $user->id)->where('deck_id', 0)->first();
            if (!$gallery_item) $gallery_item = GalleryItem::create([
                'user_id' => $user->id,
                'deck_id' => 0,
            ]);

            $this->actingAs($user)->visit('/deck/add')
                ->type($item['name'], 'name')
                ->attach($item['front_img'], 'front_img')
                ->attach($item['back_img'], 'back_img')
                ->type($item['description'], 'description')
                ->type($item['company'], 'company')
                ->type($item['edition'], 'edition')
                ->type($item['collection'], 'collection')
                ->type($item['release_year'], 'release_year')
                ->type($item['prod_run'], 'prod_run')
                ->type($item['printer'], 'printer')
                ->type($item['artist'], 'artist')
                ->type($item['card_stock'], 'card_stock')
                ->type($item['finish'], 'finish')
                ->select($item['court'], 'court')
                ->type($item['features'], 'features')
                ->type($item['colors'], 'colors')
                ->type($item['style'], 'style')
                ->type($item['themes'], 'themes')
                ->type($item['tags'], 'tags')
                ->type($gallery_item->id, 'gallery[0]')
                ->press('Create')
                ->seeInDatabase('decks', ['name' => $item['name']]);
        }
    }
}
