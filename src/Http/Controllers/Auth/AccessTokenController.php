<?php

namespace Carpentree\Core\Http\Controllers\Auth;

use Carpentree\Core\Exceptions\EmailNotVerified;
use Carpentree\Core\Models\User;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Controllers\AccessTokenController as ParentController;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends ParentController
{

    /**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(ServerRequestInterface $request)
    {

        $response = $this->withErrorHandling(function() use ($request) {

            $attributes = $request->getParsedBody();

            if (is_null($attributes['username']) || $attributes['username'] == "") {
                throw OAuthServerException::invalidRequest('username');
            }

            if (is_null($attributes['scope']) || $attributes['scope'] == "") {
                throw OAuthServerException::invalidRequest('scope');
            }

            $userModel = config('auth.providers.users.model');
            /** @var User $user */
            $user = $userModel::where('email', $attributes['username'])->first();

            if (!$user) {
                throw OAuthServerException::invalidCredentials();
            }

            if (!$user->hasVerifiedEmail()) {
                throw OAuthServerException::accessDenied(__("Your email is not verified"));
            }

            if (!$this->checkScopesAgainstRoles($user, $attributes['scope'])) {
                throw OAuthServerException::invalidCredentials();
            }

            return true;
        });

        if ($response instanceof Response) {
            return $response;
        }

        return parent::issueToken($request);
    }

    /**
     * Check if the user can access to the scopes requested.
     *
     * @param $username
     * @param $scope
     * @return bool
     */
    protected function checkScopesAgainstRoles($user, $scope)
    {
        /** @var User $user */

        if (!is_array($scope)) {
            $scope = $this->convertScopesQueryStringToArray($scope);
        }

        if (in_array('admin', $scope)) {
            return $user->hasBackendAccess();
        }

        return true;
    }

    /**
     * Converts a scopes query string to an array to easily iterate for validation.
     *
     * @param string $scopes
     *
     * @return array
     */
    private function convertScopesQueryStringToArray($scopes)
    {
        return array_filter(explode(' ', trim($scopes)), function ($scope) {
            return !empty($scope);
        });
    }

}
