<?php 

namespace LDAP\LDAPAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class LDAPUserToken extends AbstractToken
{
    public $password;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // if the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}

