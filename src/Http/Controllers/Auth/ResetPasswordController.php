<?php

namespace Carpentree\Core\Http\Controllers\Auth;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param User  $user
     * @param string    $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        // $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json([
            'status' => trans($response)
        ]);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'email' => trans($response)
        ], 400);
    }

}
