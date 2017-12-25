<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */


namespace Makm\SocialUserBundle\Security;


use Makm\SocialUserBundle\Exception\RuntimeException;
use Makm\SocialUserBundle\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class AuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * AuthenticationProvider constructor.
     * @param UserProviderInterface $userProvider
     */
    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * Attempts to authenticate a TokenInterface object.
     *
     * @param TokenInterface $token The TokenInterface instance to authenticate
     * @return TokenInterface An authenticated TokenInterface instance, never null
     * @throws \Exception
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        $username = $token->getUsername();

        if (empty($username)) {
            throw new RuntimeException('Can\'t use empty username for authenticate');
        }


        //retrieving user
        try {
            $user = $this->userProvider->loadUserByUsername($username);
            if (!$user instanceof UserInterface) {
                throw new RuntimeException('The user provider must return a UserInterface object.');
            }
        } catch (UsernameNotFoundException $e) {
            $e->setUsername($username);
            throw $e;
        }

        return new SocialUserToken($user, $user->getRoles());
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return bool true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof SocialUserToken;
    }
}