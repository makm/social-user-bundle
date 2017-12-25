<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */

namespace Makm\SocialUserBundle\Authenticator;


use Symfony\Component\HttpFoundation\Request;

interface AuthenticatorInterface
{
    /**
     * @param Request $request
     * @return SocialUserData
     */
    public function socialNetAuthentication(Request $request): SocialUserData;

}