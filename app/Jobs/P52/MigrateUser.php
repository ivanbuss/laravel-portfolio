<?php

namespace App\Jobs\P52;

use App\Profile;
use File;
use App\Jobs\Job;
use App\Classes\Old\File as OldFile;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\ImageProcessor;
use App\Role;

class MigrateUser extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $oldUser;

    protected $ImageProcessor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldUser) {
        $this->oldUser = $oldUser;
        $this->ImageProcessor =  new ImageProcessor();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $user = User::where('email', '=', $this->oldUser->mail)->first();
        if ($user === null && !is_null($this->oldUser->mail)) {

            // Migrate user and create profile
            $user = User::create([
                'email' => $this->oldUser->mail,
                'name' => $this->oldUser->name ? $this->oldUser->name : 'User',
                'bio' => $this->oldUser->bio,
                'points' => $this->oldUser->points,
                'uid' => $this->oldUser->uid
            ]);

            if (!is_null($this->oldUser->role) && (
                    $this->oldUser->role == 'contributor' ||
                    $this->oldUser->role == 'editor'
                )) {
                $user->attachRole(Role::where('name', '=', Role::CONTRIBUTOR)->first());
            }

            // Get user profile
            $profile = $user->profile;

            // Migrate profile pictures
            if (!is_null($this->oldUser->background_uri)) {
                $bgPath = OldFile::getRealPath($this->oldUser->background_uri);
                if ($bgPath && File::exists($bgPath)) {
                    $bgFile = $this->ImageProcessor->copyProfileImage($profile, $bgPath, 'background_img');
                    $profile->background_img = $bgFile->id;
                }
            }
            if (!is_null($this->oldUser->avatar_uri)) {
                $avatarPath = OldFile::getRealPath($this->oldUser->avatar_uri);
                if ($avatarPath && File::exists($avatarPath)) {
                    $avatarFile = $this->ImageProcessor->copyProfileImage($profile, $avatarPath, 'avatar_img');
                    $profile->avatar_img = $avatarFile->id;
                }
            }

            $profile->save();
        }

    }
}
