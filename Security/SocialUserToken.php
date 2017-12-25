<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */

namespace Makm\SocialUserBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

class SocialUserToken extends AbstractToken
{

    /**
     * SocialNetUserToken constructor.z
     * @param string|UserInterface $user
     * @param array                $roles
     * @throws \InvalidArgumentException
     */
    public function __construct($user, array $roles = [])
    {
        $this->setUser($user);
        parent::__construct($roles);

        if ($roles) {
            $this->setAuthenticated(true);
        }
    }


    /**
     * Returns the user credentials.
     *
     * @return mixed The user credentials
     */
    public function getCredentials()
    {
        return '';
    }
}