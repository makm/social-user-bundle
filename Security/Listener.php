<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */


namespace Makm\SocialUserBundle\Security;


use Makm\SocialUserBundle\Authenticator\UloginAuthenticator;
use Makm\SocialUserBundle\Exception\RuntimeException;
use Makm\SocialUserBundle\Exception\SocialNetAuthenticationFailureException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class Listener implements ListenerInterface
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var HttpUtils
     */
    private $httpUtils;

    /**
     * @var array
     */
    private $options;

    /**
     * @var AuthenticationSuccessHandlerInterface
     */
    private $successHandler;

    /**
     * @var AuthenticationFailureHandlerInterface
     */
    private $failureHandler;

    /**
     * Listener constructor.
     * @param TokenStorage                          $tokenStorage
     * @param AuthenticationManagerInterface        $authenticationManager
     * @param HttpUtils                             $httpUtils
     * @param AuthenticationSuccessHandlerInterface $successHandler
     * @param AuthenticationFailureHandlerInterface $failureHandler
     * @param array                                 $options
     */
    public function __construct(
        TokenStorage $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        HttpUtils $httpUtils,
        AuthenticationSuccessHandlerInterface $successHandler,
        AuthenticationFailureHandlerInterface $failureHandler,
        array $options = []
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->httpUtils = $httpUtils;
        $this->successHandler = $successHandler;
        $this->failureHandler = $failureHandler;
        $this->options = $options;
    }


    /**
     * This interface must be implemented by firewall listeners.
     *
     * @param GetResponseEvent $event
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Makm\SocialUserBundle\Exception\RuntimeException
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasSession()) {
            throw new RuntimeException('This authentication method requires a session.');
        }

        if (!$request->isMethod('POST') || !$this->httpUtils->checkRequestPath($request,
                $this->options['entry_path'])) {
            return;
        }

        try {
            $socialNetAuthenticator = new UloginAuthenticator();
            $userData = $socialNetAuthenticator->socialNetAuthentication($request);
            $token = new SocialUserToken($userData->extractFieldByName($this->options['target_field']));
        } catch (SocialNetAuthenticationFailureException $e) {
            $response = $this->failureHandler->onAuthenticationFailure($request, $e);
            $event->setResponse($response);
            return;
        }

        try {
            $authToken = $this->authenticationManager->authenticate($token);

            if (null === $authToken) {
                return;
            }

            $this->tokenStorage->setToken($authToken);
            $response = $this->successHandler->onAuthenticationSuccess($request, $authToken);

        } catch (AuthenticationException $e) {
            $response = $this->failureHandler->onAuthenticationFailure($request, $e);
        }

        $event->setResponse($response);

    }
}
