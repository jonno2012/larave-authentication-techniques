<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use Illuminate\Http\Client\Request;
use Illuminate\View\View;
use App\AuthenticatesUser;
class EmailOnly extends Controller
{
    protected AuthenticatesUser $authenticatesUser;

    /**
     * @param AuthenticatesUser $authenticatesUser
     */
    public function __construct(AuthenticatesUser $authenticatesUser)
    {
        $this->authenticatesUser = $authenticatesUser;
    }

    public function login(): View
    {
        return view('auth.email-only-login');
    }

    public function store(): string
    {
        // validate request
        // create a token
        // send it to them
        $this->authenticatesUser->invite();
        return 'go check email';
    }

    public function authenticate(LoginToken $token)
    {
        $this->authenticatesUser->login($token);
        return redirect('dashboard');
    }

}
