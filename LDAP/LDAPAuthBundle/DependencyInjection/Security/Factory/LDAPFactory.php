<?php

namespace LDAP\LDAPAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class LDAPFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPointId)
    {
        // make authentication provider
        $providerId = $this->createProvider($container, $id, $config, $userProvider);

        // make authentication listener
        $listenerId = $this->createListener($container, $id, $config, $userProvider);

        return array($providerId, $listenerId, $defaultEntryPointId);
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node->children()
             ->scalarNode('server')
             ->end();
    }

    protected function createProvider($container, $id, $config, $userProvider)
    {   
        $providerId = 'security.authentication.provider.ldap.'.$id;
        $container->setDefinition($providerId, new DefinitionDecorator('ldap.security.authentication.provider'))
                  ->replaceArgument(0, new Reference($userProvider))
                  ->replaceArgument(2, $config['server']);

        return $providerId;
    }

    protected function createListener($container, $id, $config, $userProvider)
    {
        $options = array(
            'check_path' => '/login_check',
            'use_forward' => false,
        );
        
        $listenerId = 'security.authentication.listener.ldap.'.$id;
        $container->setDefinition($listenerId, new DefinitionDecorator('ldap.security.authentication.listener'))
                  ->replaceArgument(4, $id)
                  ->replaceArgument(5, new Reference($this->createAuthenticationSuccessHandler($container, $id, $config)))
                  ->replaceArgument(6, new Reference($this->createAuthenticationFailureHandler($container, $id, $config)))
                  ->replaceArgument(7, array_intersect_key($config, $options));

        return $listenerId;
    }

    protected function createAuthenticationSuccessHandler($container, $id, $config)
    {
        $options = array(
            'always_use_default_target_path' => false,
            'default_target_path'            => '/',
            'login_path'                     => '/login',
            'target_path_parameter'          => '_target_path',
            'use_referer'                    => false,
        );

        $successHandlerId = 'security.authentication.success_handler.'.$id.'.'.str_replace('-', '_', $this->getKey());

        $successHandler = $container->setDefinition($successHandlerId, new DefinitionDecorator('security.authentication.success_handler'));
        $successHandler->replaceArgument(1, array_intersect_key($config, $options));
        $successHandler->addMethodCall('setProviderKey', array($id));

        return $successHandlerId;
    }

    protected function createAuthenticationFailureHandler($container, $id, $config)
    {
        $options = array(
            'failure_path' => null,
            'failure_forward' => false,
            'login_path' => '/login',
            'failure_path_parameter' => '_failure_path',
        );

        $id = 'security.authentication.failure_handler.'.$id.'.'.str_replace('-', '_', $this->getKey());

        $failureHandler = $container->setDefinition($id, new DefinitionDecorator('security.authentication.failure_handler'));
        $failureHandler->replaceArgument(2, array_intersect_key($config, $options));

        return $id;
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'ldap';
    }
}
