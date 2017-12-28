<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Collection;
use App\Deck;

class CollectionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdd()
    {
        $user = User::where('email', 'iwanbuss@gmail.com')->first();
        if (!$user) {
            $user = User::create(['name'=>'Ivan', 'email'=>'iwanbuss@gmail.com', 'password'=>bcrypt('150192')]);
        }

        $deck = Deck::create([
            'name' => 'Test collection',
            'user_id' => $user->id,
            'description' => 'Test deck for collection'
        ]);
        $collection = Collection::where('user_id', $user->id)->where('deck_id', $deck->id)->first();
        if ($collection) $collection->delete();

        $this->actingAs($user)
            ->json('POST', '/collection/add', ['deck_id' => $deck->id])
            ->seeJson([
                'success'=>TRUE,
                'action'=>'add',
                'deck_id'=>$deck->id
            ])->seeInDatabase('collections', ['user_id' => $user->id, 'deck_id'=>$deck->id]);

        $deck->delete();
    }
}
