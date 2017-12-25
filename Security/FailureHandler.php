<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 08.10.17 22:03
 */

namespace Makm\SocialUserBundle\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class FailureHandler implements AuthenticationFailureHandlerInterface
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
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $path = $this->options['entry_path'] ?? '/';
        return new RedirectResponse(
            $this->httpUtils->generateUri($request, $path)
        );
    }
}