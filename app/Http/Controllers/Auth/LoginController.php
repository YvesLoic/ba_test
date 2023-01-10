<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Ici on définit l'attribut qui sera prit pour login des utilisateurs.
     *
     * @return string username/matricule d'un user
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Function de validation des informations de connection
     *
     * @param \Illuminate\Http\Request $request objet de la requette
     *
     * @return void ne renvoi rien
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                $this->username() => [
                    'required',
                    'string',
                    'email',
                ],
                'password' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->mixedCase()
                        ->symbols(),
                ],
            ]
        );
    }
}
