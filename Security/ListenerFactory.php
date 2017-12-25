<?php
/**
 * Copyright (c) 2017. Maxim Kapkaev
 */

namespace Makm\SocialUserBundle\Security;


use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ListenerFactory implements SecurityFactoryInterface
{
    const PROVIDER_ID = 'security.authentication.provider.social_user';
    const LISTENER_ID = 'security.authentication.listener.social_user';
    const SUCCESS_HANDLER_ID = 'security.authentication.success_handler.social_user';
    const FAILURE_HANDLER_ID = 'security.authentication.failure_handler.social_user';

    /**
     * Configures the container services required to use the authentication listener.
     *
     * @param ContainerBuilder $container
     * @param string           $id           The unique id of the firewall
     * @param array            $config       The options array for the listener
     * @param string           $userProvider The service id of the user provider
     * @param string           $defaultEntryPoint
     *
     * @return array containing three values:
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     *               - the provider id
     *               - the listener id
     *               - the entry point id
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        //set authentication provider
        $authProviderId = self::PROVIDER_ID . '.' . $id;
        $container
            ->setDefinition($authProviderId, new ChildDefinition(AuthenticationProvider::class))
            ->replaceArgument(0, new ChildDefinition($userProvider));

        //set success_handler
        $successHandlerId = self::SUCCESS_HANDLER_ID . $id;
        $successHandler = $container->setDefinition($successHandlerId,
            new ChildDefinition(SuccessHandler::class));
        $successHandler->replaceArgument(1, $config);


        //set failure_handler
        $failureHandlerId = self::FAILURE_HANDLER_ID . $id;
        $failureHandler = $container->setDefinition($failureHandlerId,
            new ChildDefinition(FailureHandler::class));
        $failureHandler->replaceArgument(1, $config);


        $listenerId = self::LISTENER_ID . '.' . $id;
        $container
            ->setDefinition($listenerId, new ChildDefinition(Listener::class))
            ->replaceArgument(3, $successHandler)
            ->replaceArgument(4, $failureHandler)
            ->replaceArgument(5, $config)
        //    ->addTag('security.remember_me_aware', array('id' => $id, 'provider' => $userProvider))
        ;


        return [$authProviderId, $listenerId, $defaultEntryPoint];
    }

    /**
     * Defines the position at which the provider is called.
     * Possible values: pre_auth, form, http, and remember_me.
     *
     * @return string
     */
    public function getPosition()
    {
        return 'http';
    }

    /**
     * Defines the configuration key used to reference the provider
     * in the firewall configuration.
     *
     * @return string
     */
    public function getKey()
    {
        return 'social-user'; // == social_user
    }

    /**
     * @param NodeDefinition $builder
     */
    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
            ->scalarNode('entry_path')->defaultValue('/user/social')->end()
            ->scalarNode('target_field')->defaultValue('email')->end()
            ->scalarNode('success_path')->defaultValue('/')->end()
            ->scalarNode('failure_path')->defaultValue('/user/social')->end()
            ->scalarNode('provider')->end()
            ->end();
    }
}