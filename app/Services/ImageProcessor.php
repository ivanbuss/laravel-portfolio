<?php

namespace App\Services;


use App\Deck;
use App\GalleryItem;
use App\Review;
use App\User;
use App\FileModel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use App\Profile;

class ImageProcessor {

    public $presets_directory;
    public $presets_public_directory;
    public $deck_img_path;
    public $profile_img_path;
    public $temp_img_path;
    public $default_images_path;
    public $gallery_img_path;
    public $review_img_path;

    public function __construct() {
        $this->presets_directory = public_path() . '/files';
        $this->presets_public_directory = '/files';
        $this->deck_img_path = base_path() . '/storage/app/decks_original';
        $this->profile_img_path = base_path() . '/storage/app/profile_originals';
        $this->temp_img_path = public_path() . '/files/temp';
        $this->temp_img_src = '/files/temp';
        $this->default_images_path = '/default-files';
        $this->gallery_img_path = base_path() . '/storage/app/galleries_original';
        $this->review_img_path = base_path() . '/storage/app/review_original';
    }

    public function uploadDeckImage(Request $request, Deck $deck, $key) {
        if ($request->hasFile($key) && $request->file($key)->isValid()) {
            $request->file($key)->move($this->deck_img_path, 'deck_' . $deck->id . '_' . $key . '.jpg');
            $file = $this->saveFile('deck_' . $deck->id . '_' . $key . '.jpg', 'deck_img', Auth::user());
            $this->resize($file, '350x250', 'resize');
            $this->resize($file, '700x500', 'resize');

            return $file;
        }
        return FALSE;
    }

    public function copyDeckImage(Deck $deck, $path, $key, User $user) {
        if ($path && File::exists($path)) {
            $newFilename = 'deck_' . $deck->id . '_' . $key . '.jpg';
            File::copy($path, $this->deck_img_path . '/' . $newFilename);
            $file = $this->saveFile($newFilename, 'deck_img', $user);
            $this->resize($file, '350x250', 'resize');
            $this->resize($file, '700x500', 'resize');

            return $file;
        }
        return FALSE;
    }

    public function copyProfileImageByUrl(Profile $profile, $url, $key) {
        $fileName = 'profile_' . $profile->id . '_' . $key . '.jpg';
        $file = file_get_contents($url);
        $save = file_put_contents($this->profile_img_path . '/' . $fileName, $file);
        if ($save) {
            $file = $this->saveFile($fileName, 'profile_image', $profile->user);
            $this->resize($file, '300x300', 'resize');
            $this->resize($file, '150x150', 'resize');

            return $file;
        }

        return FALSE;
    }
    
    public function copyProfileImage(Profile $profile, $path, $key) {
        if ($path && File::exists($path)) {
            $newFilename = 'profile_' . $profile->id . '_' . $key . '.jpg';
            File::copy($path, $this->profile_img_path . '/' . $newFilename);
            $file = $this->saveFile($newFilename, 'profile_image', $profile->user);
            if ($key === 'avatar_img') {
                $this->resize($file, '300x300', 'resize');
                $this->resize($file, '150x150', 'resize');
            }
            elseif ($key === 'background_img') {
                $this->resize($file, '450x1350', 'crop');
            }

            return $file;
        }

        return FALSE;
    }

    public function uploadProfileImage(Request $request, Profile $profile, $key) {
        if ($request->hasFile($key) && $request->file($key)->isValid()) {
            $request->file($key)->move($this->profile_img_path, 'profile_' . $profile->id . '_' . $key . '.jpg');
            $file = $this->saveFile('profile_' . $profile->id . '_' . $key . '.jpg', 'profile_image', $profile->user);
            if ($key === 'avatar_img') {
                $this->resize($file, '300x300');
                $this->resize($file, '150x150');
            }
            elseif ($key === 'background_img') {
                $this->resize($file, '450x1350', 'crop');
            }


            return $file;
        }
        return FALSE;
    }

    public function uploadGalleryImage(Request $request, GalleryItem $galleryItem, $key) {
        if ($request->hasFile($key) && $request->file($key)->isValid()) {
            $request->file($key)->move($this->gallery_img_path, 'gallery_item_' . $galleryItem->id . '.jpg');
            $file = $this->saveFile('gallery_item_' . $galleryItem->id . '.jpg', 'gallery_img', Auth::user());
            $this->resize($file, '350x250');
            $this->resize($file, '700x500');
            $this->resize($file, 'original');
            return $file;
        }
        return FALSE;
    }

    public function uploadReviewImage(Request $request, Review $review, $key) {
        if ($request->hasFile($key) && $request->file($key)->isValid()) {
            $request->file($key)->move($this->review_img_path, 'review_' . $review->id . '.jpg');
            $file = $this->saveFile('review_' . $review->id . '.jpg', 'review_img', Auth::user());
            $this->resize($file, '700x500');
            return $file;
        }
        return FALSE;
    }
    
    public function copyGalleryImage(GalleryItem $galleryItem, $path, User $user) {
        if ($path && File::exists($path)) {
            $newFilename = 'gallery_item_' . $galleryItem->id . '.jpg';
            File::copy($path, $this->gallery_img_path . '/' . $newFilename);
            $file = $this->saveFile($newFilename, 'gallery_img', $user);
            $this->resize($file, '350x250');
            $this->resize($file, '700x500');
            $this->resize($file, 'original');

            return $file;
        }
        return FALSE;
    }

    public function uploadTempImage(Request $request, $key) {
        if ($request->hasFile($key) && $request->file($key)->isValid()) {
            $temp_filename = 'file_' . Auth::user()->id . '_' . time() . '.jpg';
            $request->file($key)->move($this->temp_img_path, $temp_filename);
            return $this->temp_img_src . '/' . $temp_filename;
        }
        return FALSE;
    }

    protected function resize(FileModel $file, $preset, $method = 'crop') {
        $path = $file->getOriginalFilePath(); $original = FALSE;

        $height = 0;
        $weight = 0;
        switch ($preset) {
            case '450x1350':
                $height = 450;
                $weight = 1350;
                break;
            case '700x500':
                $height = 700;
                $weight = 500;
                break;
            case '350x250':
                $height = 350;
                $weight = 250;
                break;
            case '300x300':
                $height = 300;
                $weight = 300;
                break;
            case '150x150':
                $height = 150;
                $weight = 150;
                break;
            case 'original':
                $original = TRUE;
                break;
        }
        $presets_file_path = $this->presets_directory . '/' . $preset . '/' . $file->name;
        $this->checkDirectory($this->presets_directory . '/' . $preset);

        if ($height && $weight) {
            if ($method === 'fit') {
                return Image::make($path)->fit($weight, $height, function ($constraint) {
                    $constraint->upsize();
                })->save($presets_file_path);
            }
            elseif ($method === 'resize') {
                return Image::make($path)->resize($weight, $height)->save($presets_file_path);
            }
            else {
                return Image::make($path)->crop($weight, $height)->save($presets_file_path);
            }

        } else if ($original) {
            return Image::make($path)->save($presets_file_path);
        } else {
            return FALSE;
        }
    }

    protected function saveFile($filename, $type, User $user) {
        return FileModel::create([
            'user_id' => $user->id,
            'type' => $type,
            'name' => $filename,
        ]);
    }

    protected function checkDirectory($directory) {
        if (!File::isDirectory($directory)) {
            return File::makeDirectory($directory, 0775, true);
        }
        return false;
    }

}