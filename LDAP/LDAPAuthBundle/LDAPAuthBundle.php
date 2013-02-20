<?php

namespace LDAP\LDAPAuthBundle;

use LDAP\LDAPAuthBundle\DependencyInjection\Security\Factory\LDAPLoginFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LDAPAuthBundle extends Bundle
{
    public function build(ContainerBuilder $container) 
    {
        parent::build($container);
        
        // add the ldap security factory
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new LDAPLoginFactory());
    }
}
