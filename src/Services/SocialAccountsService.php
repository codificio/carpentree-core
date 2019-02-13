<?php

namespace Carpentree\Core\Services;

use Carpentree\Core\Models\User;
use Carpentree\Core\Repositories\LinkedSocialAccountRepository;
use Carpentree\Core\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialAccountsService
{
    /** @var UserRepository */
    protected $userRepository;

    /** @var LinkedSocialAccountRepository */
    protected $linkedSocialAccountRepository;

    public function __construct(UserRepository $userRepository, LinkedSocialAccountRepository $linkedSocialAccountRepository)
    {
        $this->linkedSocialAccountRepository = $linkedSocialAccountRepository;
        $this->userRepository = $userRepository;
        $this->userRepository->skipCache(true);
    }

    /**
     * Find or create user instance by provider user instance and provider name.
     *
     * @param ProviderUser $providerUser
     * @param string $provider
     *
     * @return User
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        $linkedSocialAccount = $this->linkedSocialAccountRepository->findWhere([
            'provider_name' => $provider,
            'provider_id' => $providerUser->getId()
        ]);

        if ($linkedSocialAccount) {

            return $linkedSocialAccount->user;

        } else {

            $user = null;

            if ($email = $providerUser->getEmail()) {
                $user = $this->userRepository->findWhere(['email', $email]);

                if (!$user) {
                    $user = $this->userRepository->create([
                        'first_name' => $providerUser->getName(),
                        'last_name' => $providerUser->getName(),
                        'email' => $providerUser->getEmail(),
                    ]);

                    event(new Registered($user));
                }

                $this->linkedSocialAccountRepository->create([
                    'provider_id' => $providerUser->getId(),
                    'provider_name' => $provider,
                    'user_id' => $user->id
                ]);
            }

            return $user;

        }
    }
}
