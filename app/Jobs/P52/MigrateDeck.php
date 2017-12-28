<?php

namespace App\Jobs\P52;

use App\Models\Artist;
use App\Jobs\Job;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Manufacturer;
use App\Services\Vocabulary;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\ImageProcessor;
use App\Deck;
use App\User;
use App\Classes\Old\File as OldFile;
use File;
use App\GalleryItem;

class MigrateDeck extends Job implements ShouldQueue {

    use InteractsWithQueue, SerializesModels;

    protected $oldDeck;

    protected $imageProcessor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldDeck) {
        $this->oldDeck = $oldDeck;
        $this->imageProcessor = new ImageProcessor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $deck = Deck::where('nid', '=', $this->oldDeck->nid)->first();
        if ($deck === null) {
            // Get user
            $user = User::where('uid', '=', $this->oldDeck->uid)->first();
            if (!$user) {
                $user = User::where('uid', '=', 0)->first();
            }
            // Get artist
            $artist = Artist::where('nid', '=', $this->oldDeck->artist_nid)->first();
            $brand = Brand::where('nid', '=', $this->oldDeck->brand_nid)->first();
            $company = Company::where('nid', '=', $this->oldDeck->company_nid)->first();
            $manufacturer = Manufacturer::where('nid', '=', $this->oldDeck->manufacturer_nid)->first();

            $deck = Deck::create([
                'nid' => $this->oldDeck->nid ? (int) $this->oldDeck->nid : NULL,
                'user_id' => $user->id,
                'name' => $this->oldDeck->name,
                'description' => $this->oldDeck->description ? $this->oldDeck->description : '',
                'release_year' => $this->oldDeck->year ? (int) $this->oldDeck->year : NULL,
                'artist_id' => $artist ? $artist->id : NULL,
                'collection_id' => $brand ? $brand->id : NULL,
                'company_id' => $company ? $company->id : NULL,
                'printer_id' => $manufacturer ? $manufacturer->id : NULL,
            ]);

            // Front image
            if (!is_null($this->oldDeck->fronimage_uri)) {
                $frontImagePath = OldFile::getRealPath($this->oldDeck->fronimage_uri);
                if ($frontImagePath && File::exists($frontImagePath)) {
                    $frontImageFile = $this->imageProcessor->copyDeckImage($deck, $frontImagePath, 'front_img', $user);
                    $deck->front_img_id = $frontImageFile->id;
                }
            }

            // Back image
            if (!is_null($this->oldDeck->backimage_uri)) {
                $backImagePath = OldFile::getRealPath($this->oldDeck->backimage_uri);
                if ($backImagePath && File::exists($backImagePath)) {
                    $backImageFile = $this->imageProcessor->copyDeckImage($deck, $backImagePath, 'back_img', $user);
                    $deck->back_img_id = $backImageFile->id;
                }
            }

            if ($this->oldDeck->gallery_uris) {
                $this->createGallery($this->oldDeck->gallery_uris, $user, $deck);
            }

            $deck->save();
        }
    }

    private function createGallery($galleryUris, User $user, Deck $deck) {

        $vocabulary = new Vocabulary();
        $tags = [];
        $tags[] = $vocabulary->getTerm('gallery_tags', 'Photo')->term_id;
        foreach ($galleryUris as $uri) {
            // create gallery item
            $gallery_item = GalleryItem::create([
                'user_id' => $user->id,
                'deck_id' => $deck->id,
            ]);

            $path = OldFile::getRealPath($uri);
            if ($path && File::exists($path)) {
                // This should create file.
                $file = $this->imageProcessor->copyGalleryImage($gallery_item, $path, $user);
            }

            // attach tags
            $gallery_item->tags()->attach($tags);
            if ($file) {
                // attach file
                $gallery_item->image_id = $file->id;
                $gallery_item->save();
            }
            else {
                $gallery_item->delete();
            }

        }
    }
}
