<?php

namespace LDAP\LDAPAuthBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use LDAP\LDAPAuthBundle\Security\Authentication\Token\LDAPUserToken;

class LDAPListener extends AbstractAuthenticationListener
{
    public function attemptAuthentication(Request $request)
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
            
        $token = new LDAPUserToken();
        $token->setUser($username);
        $token->password = $password;

        try {
            return $this->authenticationManager->authenticate($token);
        } catch (AuthenticationException $failed) {
            return null;
        }
    }
}
