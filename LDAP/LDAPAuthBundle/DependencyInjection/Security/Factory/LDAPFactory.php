<?php

namespace LDAP\LDAPAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

class LDAPFactory extends AbstractFactory 
{
    public function addConfiguration(NodeDefinition $node)
    {
        $node->children()
             ->scalarNode('server')->end()
             ->scalarNode('check_path')->defaultValue('/login_check')->end()
             ->scalarNode('login_path')->defaultValue('/login')->end();

        parent::addConfiguration($node);
    }
    
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {   
        $providerId = 'security.authentication.provider.ldap.'.$id;
        $container->setDefinition($providerId, new DefinitionDecorator('ldap.security.authentication.provider'))
                  ->replaceArgument(0, new Reference($userProviderId))
                  ->replaceArgument(2, $config['server']);

        return $providerId;
    }

    protected function getListenerId() 
    {   
        return 'ldap.security.authentication.listener';
    }
    
    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'ldap';
    }
}
