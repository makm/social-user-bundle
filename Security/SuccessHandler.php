<?php

namespace Makm\SocialUserBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class SuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var HttpUtils
     */
    private $httpUtils;

    /**
     * @var array
     */
    private $options;

    public function __construct(HttpUtils $httpUtils, array $options = [])
    {
        $this->httpUtils = $httpUtils;
        $this->options = $options;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new RedirectResponse(
            $this->httpUtils->generateUri($request, $this->options['success_path'])
        );
    }
}