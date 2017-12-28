<?php

namespace App\Http\Controllers\P52;

use App\Classes\Old\Entity as OldEntity;
use App\Jobs\P52\ChunkedPushMigrateCollection;
use App\Jobs\P52\MigrateCollection;
use App\Jobs\P52\MigrateDeck;
use Illuminate\Http\Request;
use App\User;
use App\Jobs\P52\MigrateUser;
use Illuminate\Database\Eloquent\Model;
use App\Classes\Old\File as OldFile;
use App\Classes\Old\Artist as OldArtist;
use App\Models\Artist;
use App\Classes\Old\Brand as OldBrand;
use App\Models\Brand;
use App\Classes\Old\Manufacturer as OldManufacturer;
use App\Models\Manufacturer;
use App\Classes\Old\Manufacturer as OldCompany;
use App\Models\Company;
use App\Classes\Old\Deck as OldDeck;
use App\Deck;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\Old\User as OldUser;
use App\Classes\Old\Collection as OldCollection;

class MigrateController extends Controller {

    /**
     * Action for testing purpose only
     * @todo: delete logic before prod
     */
    public function test() {
        $entities = OldUser::getEntities(['limit' => 100]);
        $delta = rand(5, 100);
        $job = new MigrateUser($entities[$delta]);  
        $job->handle();

    }

    public function migrate() {
        return view('p52.migrate');
    }


    public function migratePost(Request $request) {
        $input = $request->input();
        if (isset($input['migrate_users'])) {
            $this->initUsersMigration();
        }
        if (isset($input['migrate_artists'])) {
            $this->initCommonNodeMigration(new OldArtist(), new Artist());
        }
        if (isset($input['migrate_brands'])) {
            $this->initCommonNodeMigration(new OldBrand(), new Brand());
        }        
        if (isset($input['migrate_manufacturers'])) {
            $this->initCommonNodeMigration(new OldManufacturer(), new Manufacturer());
        }
        if (isset($input['migrate_companies'])) {
            $this->initCommonNodeMigration(new OldCompany(), new Company());
        }
        if (isset($input['migrate_decks'])) {
            $this->initDecksMigration();
        }
        if (isset($input['migrate_collections'])) {
            $this->migrateCollections();
        }
        
        return redirect('/migrate');
    }

    protected function migrateCollections() {
        $amount = OldCollection::countEntities();
        set_time_limit(1000);
        $step = 100;
        $integer =  (int) floor($amount / $step);
        for ($i = 1; $i <= $integer; $i++) {
            if ($i == 1) {
                $offset = 0;
            }
            elseif ($i == $integer) {
                $offset = $step * $i;
            }
            else {
                $offset = $step * ($i - 1);
            }
            $params = [
                'offset' => $offset,
                'limit' => $step - 1,
            ];
//            foreach(OldCollection::getEntities($params) as $oldCollection) {
//                $this->dispatch(new MigrateCollection($oldCollection));
//            }
            $job = (new ChunkedPushMigrateCollection($params));
            $this->dispatch($job);
        }
    }

    /**
     * Push users to queued jobs
     */
    protected function initUsersMigration() {
        foreach (OldUser::getEntities() as $oldUser) {
            $this->dispatch(new MigrateUser($oldUser));
        }
    }

    /**
     * Migration method for common nodes which have name, description, uri and nid
     *
     * @param OldEntity $oldEntityObj
     * @param Model $modelObj
     */
    protected function initCommonNodeMigration(OldEntity $oldEntityObj, Model $modelObj) {
        foreach ($oldEntityObj::getEntities() as $oldEntity) {
            $model = $modelObj::where('nid', '=', $oldEntity->nid)->first();
            if ($model === null) {
                $modelObj::create([
                    'name' => $oldEntity->name,
                    'description' => $oldEntity->description,
                    'url' => $oldEntity->url,
                    'nid' => $oldEntity->nid,
                ]);
            }
        }
    }

    /**
     * Advanced deck migration
     */
    protected function initDecksMigration() {
        foreach (OldDeck::getEntities() as $oldDeck) {
            $job = (new MigrateDeck($oldDeck))->delay(10);
            $this->dispatch($job);
        }
    }
    
    public function migrateAll() {
        $this->initUsersMigration();
        $this->initCommonNodeMigration(new OldArtist(), new Artist());
        $this->initCommonNodeMigration(new OldBrand(), new Brand());
        $this->initCommonNodeMigration(new OldManufacturer(), new Manufacturer());
        $this->initCommonNodeMigration(new OldCompany(), new Company());
        $this->initDecksMigration();
        $this->migrateCollections();
    }
}
