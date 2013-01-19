## Symfony2 LDAP Authentication Provider

This budle provides a custom authentication provider for authenticating users with directory services (ex. Active Directory) via LDAP. It will 
connect to directory services and attempt a bind operation with the provided username and password. This bundle will **not** act as an 
authorization provider or a user provider. It will only provide authentication. The rest is up to you. 

#### Prerequisites

- [PHP LDAP library](http://php.net/manual/en/book.ldap.php)

#### Configuration

Getting this up and running should only steal about 3 minutes of your life. 

###### Step #1

Copy the `LDAP` directory from this repo to `src/`.

###### Step #2

Install the bundle by adding a reference in your `app/AppKernel.php` file like so:

```php
class AppKernel extends Kernel
{
   public function registerBundles()
   {
        $bundles = array(
            ...
            new LDAP\LDAPAuthBundle\LDAPAuthBundle(),
            ...
        );
    );
    ...
}
```

###### Step #3

Set up your security firewall. 

For example:

```yaml
// app/config/security.yml

security:
    firewalls:
        ...
        ldap_secured:
            ldap: { server: 'my.directoryservices.server' }
            pattern: ^/admin
            form_login:
                login_path: /login
                check_path: /login_check
            logout:
                path:   /logout
                target: /login
        ...
```

###### You're done.


#### Extending

If you want to do a little more than a bind operation to authenticate the user, you can add some custom code to 
`src/LDAP/LDAPAuthBundle/Security/Authentication/Provider/LDAPProvider.php` (see the LDAPValidate function and 
the link to the PHP LDAP libraries above).
