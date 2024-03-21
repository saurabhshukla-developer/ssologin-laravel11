<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Models\User;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    /**
     * Obtain the user information from provider.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect(route('dashboard', absolute: false));
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('email', $user->email)->first();

        if($authUser){
            if(($provider == 'google' && $authUser->google_auth_id == $user->id) || ($provider == 'github' && $authUser->github_auth_id == $user->id)){
                return $authUser;
            } else {
                if($provider == 'google'){
                    $authUser->google_auth_id = $user->id;
                    $authUser->save();
                } else if ($provider == 'github'){
                    $authUser->github_auth_id = $user->id;
                    $authUser->save();
                }
                return $authUser;
            }
        }
        else {
            if($provider == 'google'){
                $columnName = 'google_auth_id';
            } else {
                $columnName = 'github_auth_id';
            }
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            $columnName => $user->id,
            'password' => Hash::make(rand(1000,10000)),
        ]);
    }
}
