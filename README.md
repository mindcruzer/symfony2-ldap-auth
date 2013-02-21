## Symfony2 LDAP Authentication Provider

**NOTE**: This is for Symfony >= `2.1.x`. There is a `2.0.x` branch if you need it. 

This bundle contains a custom authentication provider for authenticating users with directory services (ex. Active Directory) via LDAP. It will 
connect to directory services and attempt a bind operation with the provided username and password. This bundle will **not** act as an 
authorization provider or a user provider. It will only provide authentication. The rest is up to you. 

#### Prerequisites

- [PHP LDAP library](http://php.net/manual/en/book.ldap.php)

#### Configuration

Getting this up and running should only steal a minute of your life. 

###### Step #1

Clone and then copy the `LDAP` directory from this repo to `src/`.

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
        secured_area:
            ldap_login: 
                login_path: /login
                check_path: /login_check
            pattern: ^/admin
            logout:
                path:   /logout
                target: /login
        ...
```

###### Step #4
You'll need to go to `src/LDAP/LDAPAuthBundle/Security/Authentication/Provider/LDAPAuthenticationProvider.php` and 
enter your server's domain name in this line `$ldap_conn = ldap_connect(/*'your.server.here'*/);`. This is clearly an 
undesirable place to do this, so I'm working on getting this put into `security.yml`.

###### You're done.


#### Extending

If you want to do a little more than a bind operation to authenticate the user, you can add some custom code to 
`src/LDAP/LDAPAuthBundle/Security/Authentication/Provider/LDAPAuthenticationProvider.php`.
