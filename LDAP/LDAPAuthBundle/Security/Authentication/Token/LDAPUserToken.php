<?php 

namespace LDAP\LDAPAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class LDAPUserToken extends AbstractToken
{
    public function __construct($username, $password, array $roles = array())
    {
        parent::__construct($roles);
        
        $this->setUser($username);
        $this->password = $password;

        // if the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return $this->password;
    }
    
    public function serialize()
    {
        return serialize(array($this->password, parent::serialize()));
    }

    public function unserialize($str)
    {
        list($this->password, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}

