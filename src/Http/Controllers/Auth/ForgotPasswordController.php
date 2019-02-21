<?php

namespace Carpentree\Core\Http\Controllers\Auth;

use Carpentree\Core\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Get the response for a successful password reset link.
     *
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'status' => trans($response)
        ]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'email' => trans($response)
        ], 400);
    }
}
