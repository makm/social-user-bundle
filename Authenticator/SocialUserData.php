<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */

namespace Makm\SocialUserBundle\Authenticator;


use Makm\SocialUserBundle\Exception\RuntimeException;

class SocialUserData
{
    const BIRTH_DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $netUid;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string|null
     */
    private $photo;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $birthDate;

    /**
     * @var string|null
     */
    private $sex;

    /**
     * @var string
     */
    private $userNetIdentity;

    /**
     * @var string|null
     */
    private $countryName;

    /**
     * @var string|null
     */
    private $cityName;

    /**
     * @var string|null
     */
    private $profileUrl;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $socialNetName;

    /**
     * @return string
     */
    public function getNetUid(): string
    {
        return $this->netUid;
    }

    /**
     * @param string $netUid
     * @return SocialUserData
     */
    public function setNetUid(string $netUid): SocialUserData
    {
        $this->netUid = $netUid;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return SocialUserData
     */
    public function setNickname(string $nickname): SocialUserData
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param null|string $photo
     * @return SocialUserData
     */
    public function setPhoto($photo): SocialUserData
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     * @return SocialUserData
     */
    public function setLastName($lastName): SocialUserData
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     * @return SocialUserData
     */
    public function setFirstName($firstName): SocialUserData
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    /**
     * @param null|string $birthDate
     * @return SocialUserData
     */
    public function setBirthDate(string $birthDate): SocialUserData
    {
        $this->birthDate = date(self::BIRTH_DATE_FORMAT, strtotime($birthDate));
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * @param null|string $sex
     * @return SocialUserData
     */
    public function setSex($sex): SocialUserData
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserNetIdentity(): string
    {
        return $this->userNetIdentity;
    }

    /**
     * @param string $userNetIdentity
     * @return SocialUserData
     */
    public function setUserNetIdentity(string $userNetIdentity): SocialUserData
    {
        $this->userNetIdentity = $userNetIdentity;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /**
     * @param null|string $countryName
     * @return SocialUserData
     */
    public function setCountryName($countryName): SocialUserData
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /**
     * @param null|string $cityName
     * @return SocialUserData
     */
    public function setCityName($cityName): SocialUserData
    {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getProfileUrl(): ?string
    {
        return $this->profileUrl;
    }

    /**
     * @param null|string $profileUrl
     * @return SocialUserData
     */
    public function setProfileUrl($profileUrl): SocialUserData
    {
        $this->profileUrl = $profileUrl;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     * @return SocialUserData
     */
    public function setEmail($email): SocialUserData
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param null|string $phone
     * @return SocialUserData
     */
    public function setPhone($phone): SocialUserData
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSocialNetName(): ?string
    {
        return $this->socialNetName;
    }

    /**
     * @param string $socialNetName
     * @return SocialUserData
     */
    public function setSocialNetName(string $socialNetName): SocialUserData
    {
        $this->socialNetName = $socialNetName;
        return $this;
    }

    /**
     * @param $string
     * @throws \Makm\SocialUserBundle\Exception\RuntimeException
     */
    public function extractFieldByName($string)
    {
        if (!isset($this->{$string})) {
            throw  new RuntimeException("Unknown field '{$string}' for use");
        }
        return $this->{$string};
    }

}