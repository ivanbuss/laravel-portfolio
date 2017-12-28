<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 12.06.16
 * Time: 10:52
 */

namespace App;

use App\Services\ImageProcessor;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService {

    protected $imageProcessor;

    public function __construct() {
        $this->imageProcessor = new ImageProcessor();
    }

    public function createOrGetUser(ProviderUser $providerUser) {

        $account = SocialAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        }
        else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
                //$avatarPath = $providerUser->getAvatar();
                $avatarPath = "https://graph.facebook.com/{$providerUser->id}/picture?width=500&height=500";

                $profile = $user->profile;

                // Get avatar
                if ($profile instanceof Profile && $avatarPath) {
                    $file = $this->imageProcessor->copyProfileImageByUrl($profile, $avatarPath, 'avatar_img');
                    if ($file) {
                        $profile->avatar_img = $file->id;
                    }
                }

                $profile->save();


            }

            $account->user()->associate($user);
            $account->save();

            return $user;

        }

    }
}
