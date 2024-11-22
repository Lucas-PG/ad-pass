# AD Password

Very simple PHP application to change a user password using an admin account.

Not very useful, since someone that knows an admin account may change an user's password in other ways. However, this application may be more intuitive.

## Configuration

1. Set a `.env` file at the root of the application with the variables:

   - `AD_HOST`: The Active Directory host.
   - `AD_USER_DN`: The user group DN.

2. Install the dependencies with `composer install`.
3. [Optional] You may need to deactivate certificate validation. To do so, add the line `TLS_REQCERT never` to the file `/etc/ldap/ldap.conf` in your local machine.
4. Run the application with `php -S localhost:3000` and access it at [ `http://localhost:3000` ](http://localhost:3000).

## Application

[!alt img](https://github.com/Lucas-PG/ad-password/blob/main/public/images/app-png.png)
