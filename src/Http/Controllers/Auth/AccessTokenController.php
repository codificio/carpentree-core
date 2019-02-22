<?php

namespace Carpentree\Core\Http\Controllers\Auth;

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
            // Check for backend access
            $attributes = $request->getParsedBody();
            if (!$this->checkScopesAgainstRoles($attributes['username'], $attributes['scope'])) {
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
    protected function checkScopesAgainstRoles($username, $scope)
    {
        if (is_null($username) || $username == "") {
            throw OAuthServerException::invalidRequest('username');
        }

        if (is_null($scope) || $scope == "") {
            throw OAuthServerException::invalidRequest('scope');
        }

        if (!is_array($scope)) {
            $scope = $this->convertScopesQueryStringToArray($scope);
        }

        /** @var User $user */
        $user = User::where('email', $username)->first();

        if (!$user) {
            throw OAuthServerException::invalidCredentials();
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
