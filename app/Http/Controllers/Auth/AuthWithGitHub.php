<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class AuthWithGitHub extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = self::findOrCreateGitHubUser(Socialite::driver('github')->user());
        auth()->login($user);
        return redirect('/');
    }

    public static function findOrCreateGitHubUser($gitHubUser)
    {
        $user = User::firstOrNew(['github_id' => $gitHubUser->id]);
        if($user->exists) return $user;

        $user->fill([
            'username' => $gitHubUser->nickname,
            'email' => $gitHubUser->email,
            'avatar' => $gitHubUser->avatar
        ])->save();

        return $user;
    }
}
