<?php

namespace LDAP\LDAPAuthBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use LDAP\LDAPAuthBundle\Security\Authentication\Token\LDAPUserToken;

class LDAPListener extends AbstractAuthenticationListener
{
    public function attemptAuthentication(Request $request)
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        
        $token = new LDAPUserToken($username, $password);
        
        $request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);
        
        return $this->authenticationManager->authenticate($token);
    }
}
