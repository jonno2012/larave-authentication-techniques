<?php

namespace App;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginToken;
use \Illuminate\Support\Facades\Auth;

class AuthenticatesUser
{
    use ValidatesRequests;

    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login(LoginToken $token): void
    {
        Auth::login($token->user);
        $token->delete();
    }


    public function invite(): void
    {
        $this->validateRequest()
            ->createToken()
            ->send();
    }

    private function validateRequest(): AuthenticatesUser
    {
        $this->validate($this->request, [
            'email' => 'required|email|exists:users'
        ]);

        return $this;
    }

    protected function createToken(): object
    {
        $user = User::byEmail($this->request->email);
        return LoginToken::generateFor($user);
    }
}
