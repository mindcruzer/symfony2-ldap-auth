<?php 

namespace LDAP\LDAPAuthBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LDAPAuthenticationProvider extends DaoAuthenticationProvider
{
    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {   
        // connect to directory services
        $ldap_conn = ldap_connect(/*'your.server.here'*/);
        
        if ($ldap_conn) {
            
            // attempt binding
            $binding = @ldap_bind($ldap_conn, $token->getUsername(), $token->getCredentials());

            if ($binding) {
                // authenticated
                return true;
            }
        }
        
        ldap_close($ldap_conn);

        // not authenticated
        throw new BadCredentialsException("Incorrect username or password.");
    }
}
