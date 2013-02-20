<?php

namespace LDAP\LDAPAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


/**
 * LDAPLoginFactory creates services for LDAP login authentication.
 *
 */
class LDAPLoginFactory extends FormLoginFactory
{
    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'ldap-login';
    }

    protected function getListenerId()
    {
        return 'ldap.security.authentication.listener';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication.provider.ldap.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('ldap.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(2, $id);

        return $provider;
    }

    protected function createListener($container, $id, $config, $userProvider)
    {
        $listenerId = parent::createListener($container, $id, $config, $userProvider);

        if (isset($config['csrf_provider'])) {
            $container
                ->getDefinition($listenerId)
                ->addArgument(new Reference($config['csrf_provider']))
            ;
        }

        return $listenerId;
    }
}