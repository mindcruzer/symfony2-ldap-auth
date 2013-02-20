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
    public function getKey()
    {
        return 'ldap-login';
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
}
