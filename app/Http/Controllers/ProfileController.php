<?php
namespace App\Http\Controllers;

use App\Collection;
use App\Role;
use App\Tradelist;
use App\User;
use App\Profile;
use App\UsersDeck;
use App\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Services\ImageProcessor;
use Validator;
use Entrust;
use Auth;

class ProfileController extends Controller {

    protected $imageProcessor;

    /**
     * Create a new controller instance.
     *
     * ProfileController constructor.
     * @param ImageProcessor $imageProcessor
     */
    public function __construct(ImageProcessor $imageProcessor)
    {
        $this->middleware('auth')->except('show');
        $this->imageProcessor = $imageProcessor;
    }

    public function show(Request $request, User $user) {
        if (!$user->exists) $user = $request->user();
        return view('profile.show', [
            'page_title' => $user->name . " user profile.",
            'rank' => $user->profile->userRank(),
            'user' => $user,
            'profile' => $user->profile,
            'collection_list' => UsersDeck::where('user_id', '=', $user->id)->where('in_collection', '>', 0)->orderBy('created_at', 'desc')->get(),
            'collected_items_amount' => $user->collectionList()->count(),
            'wishlist' => UsersDeck::where('user_id', '=', $user->id)->where('in_wishlist', '>', 0)->orderBy('created_at', 'desc')->get(),
            'wishlist_items_amount' =>$user->wishList()->count(),
            'tradelist' => UsersDeck::where('user_id', '=', $user->id)->where('in_tradelist', '>', 0)->orderBy('created_at', 'desc')->get(),
            'tradelist_items_amount' => $user->tradeList()->count(),
        ]);
    }

    /**
     * Show the application profile edit page.
     * @param User $user
     *   User model object
     *
     * @return \Illuminate\Http\Response
     */
    public function getEdit(User $user) {
        if (Entrust::hasRole('admin') || Auth::user()->id === $user->id) {
            $rolesArray = Role::getRolesArray();
            $defaultRoles = [];
            foreach ($user->roles()->get()->all() as $role) {
                $defaultRoles[] = $role->id;
            }
            $countries = \CountryState::getCountries();

            return view('profile.edit', [
                'user' => $user,
                'profile' => $user->profile,
                'roles' => $rolesArray,
                'defaultRoles' => $defaultRoles,
                'countries'=> $countries,
            ]);
        };

        return redirect()->route('profile.show', $user);
    }

    /**
     * Get a validator for an incoming edit profile request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        $countries = \CountryState::getCountries();
        $countries_keys = array_keys($countries);
        return Validator::make($data, [
            'name' => 'required|max:255',
            'avatar_img' => 'image|max:5120',
            'background_img' => 'image|max:5120',
            'gender' => 'in:m,f',
            'birthday' => 'date_format:d/m/Y',
            'country' => 'in:'.implode(',', $countries_keys),
            'bio' => '', // TODO: find out how to validate bio text field
        ]);
    }

    public function postEdit(Request $request, User $user) {
        return $this->editProfile($request, $user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return string
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function editProfile(Request $request, User $user) {
        $data = $request->all();
        $data['birthday'] = $request->get('birthday_day') .'/'. $request->get('birthday_month') .'/'. $request->get('birthday_year');
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $this->save($request, $user->profile);
        return redirect()->route('profile.show', $user);

    }

    /**
     * @param Request $request
     * @param Profile $profile
     */
    public function save(Request $request, Profile $profile) {
        $data = $request->all();
        $profile->name = $data['name'];
        $profile->bio = $request->bio ? $request->bio : $profile->bio;
        if ($request->has('birthday_year') && $request->has('birthday_month') && $request->has('birthday_day')) {
            $profile->birthday = Carbon::create($request->get('birthday_year'), $request->get('birthday_month'), $request->get('birthday_day'));
        }
        $profile->gender = $request->gender ? $request->gender : $profile->gender;
        // Roles
        if ($request->roles) {
            $profile->user->updateRolesById($request->roles);
        }

        $profile->country = $request->get('country');

        // Save images
        $avatarImage = $this->imageProcessor->uploadProfileImage($request, $profile, 'avatar_img');
        if ($avatarImage) $profile->avatar_img = $avatarImage->id;
        $backgroundImage = $this->imageProcessor->uploadProfileImage($request, $profile, 'background_img');
        if ($backgroundImage) $profile->background_img = $backgroundImage->id;
        $profile->save();

        $request->session()->flash('status', 'Profile has been updated');
    }

}
