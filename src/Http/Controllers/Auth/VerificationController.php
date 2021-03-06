<?php

namespace Carpentree\Core\Http\Controllers\Auth;

use Carpentree\Core\DataAccess\User\UserDataAccess;
use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /** @var UserDataAccess */
    protected $dataAccess;

    public function __construct(UserDataAccess $dataAccess)
    {
        $this->dataAccess = $dataAccess;
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        /** @var User $user */
        $user = $this->dataAccess->findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            throw new HttpException(400, __("User email has already been verified"));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return view("carpentree-core::email-verification");
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            throw new HttpException(400, __("User email has already been verified"));
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json();
    }
}
