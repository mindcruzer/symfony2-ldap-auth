<?php 

namespace LDAP\LDAPAuthBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use LDAP\LDAPAuthBundle\Security\Authentication\Token\LDAPUserToken;

class LDAPProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $server;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, $server)
    {   
        $this->userProvider = $userProvider;
        $this->cacheDir = $cacheDir;
        $this->server = $server;
    }

    public function authenticate(TokenInterface $token)
    {  
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user && $this->LDAPValidate($token->getUsername(), $token->getCredentials())) {
            
            $authenticatedToken = new LDAPUserToken($token->getUsername(), $token->getCredentials(), $user->getRoles());

            return $authenticatedToken;
        }

        throw new AuthenticationException('Authentication failed.');
    }

    protected function LDAPValidate($username, $password)
    {   
        // connect to directory services
        $ldap_conn = ldap_connect($this->server);
        
        if ($ldap_conn) {
            
            // attempt binding
            $binding = @ldap_bind($ldap_conn, $username, $password);

            if ($binding) {
                // authenticated
                return true;
            }
        }
        
        ldap_close($ldap_conn);

        // not authenticated
        return false;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof LDAPUserToken;
    }
}
