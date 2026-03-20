<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    protected $redirectTo = '/customer/dashboard';

    public function showResetForm(\Illuminate\Http\Request $request, $token = null)
    {
        return view('customer.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(\Illuminate\Http\Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $status = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect($this->redirectTo)->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    protected function validationErrorMessages()
    {
        return [];
    }

    protected function credentials(\Illuminate\Http\Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        \Illuminate\Support\Facades\Auth::guard('customer')->login($user);
    }

    protected function broker()
    {
        return Password::broker('customers');
    }
}
