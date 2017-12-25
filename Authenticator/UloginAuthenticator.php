<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */

namespace Makm\SocialUserBundle\Authenticator;


use Makm\SocialUserBundle\Exception\RuntimeException;
use Makm\SocialUserBundle\Exception\SocialNetAuthenticationFailureException;
use Symfony\Component\HttpFoundation\Request;

class UloginAuthenticator implements AuthenticatorInterface
{
    const  ULOGIN_DATA_URL = 'http://ulogin.ru/token.php?token=%s&host=%s';

    /**
     * @param $token
     * @param $host
     * @return array
     */
    private function getUserData($token, $host): array
    {
        return json_decode(
            file_get_contents(
                sprintf(self::ULOGIN_DATA_URL, $token, $host)),
            true
        );
    }

    /**
     * @see http://ulogin.ru/help.php#fields
     *
     * @param $data
     * @return SocialUserData
     */
    private function createSocialUserData(array $data): SocialUserData
    {
        $socialUserData = new SocialUserData();

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'nickname':
                    $socialUserData->setNickname((string)$value);
                    break;
                case 'photo_big':
                    $socialUserData->setPhone((string)$value);
                    break;
                case 'last_name':
                    $socialUserData->setLastName((string)$value);
                    break;
                case 'first_name':
                    $socialUserData->setFirstName((string)$value);
                    break;
                case 'bdate':
                    $socialUserData->setBirthDate((string)$value);
                    break;
                case 'sex':
                    if ($value !== 0) {
                        $sexValues = ['1' => 'F', '2' => 'M'];
                        $socialUserData->setBirthDate($sexValues[$value]);
                    }
                    break;
                case 'identity':
                    $socialUserData->setUserNetIdentity((string)$value);
                    break;
                case 'city':
                    $socialUserData->setCityName((string)$value);
                    break;
                case 'profile':
                    $socialUserData->setProfileUrl((string)$value);
                    break;
                case 'email':
                    if ($data['verified_email'] === '1') {
                        $socialUserData->setEmail((string)$value);
                    }
                    break;
                case 'phone':
                    $socialUserData->setPhone((string)$value);
                    break;
                case 'network':
                    $socialUserData->setUserNetIdentity((string)$value);
                    break;
                case 'country':
                    $socialUserData->setCountryName((string)$value);
                    break;
                case 'uid':
                    $socialUserData->setNetUid((string)$value);
                    break;
            }
        }

        return $socialUserData;
    }


    /**
     * @param Request $request
     * @return SocialUserData
     * @throws \RuntimeException
     */
    public function socialNetAuthentication(Request $request): SocialUserData
    {
        $token = $request->request->get('token');
        $host = $request->server->get('HTTP_HOST');

        if ($token === null) {
            throw new RuntimeException('Can\'t extract token param');
        }

        if ($host === null) {
            throw new RuntimeException('Can\'t not extract host param');
        }

        $data = $this->getUserData($token, $host);

        if (!empty($data['error'])) {
            throw new SocialNetAuthenticationFailureException(
                \sprintf('Can\'t authenticate, has remote error: %s', $data['error'])
            );
        }

        $socialUserData = $this->createSocialUserData($data);
        if (null === $socialUserData) {
            throw new SocialNetAuthenticationFailureException(
                \sprintf('Mapped user data is empty')
            );
        }

        return $socialUserData;
    }
}