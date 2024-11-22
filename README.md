# AD Password

Very simple PHP application to change a user password using an admin account.

Not very useful, since someone that knows an admin account may change an user's password in other ways. However, this application may be more intuitive.

## Configuration

1. Set a `.env` file at the root of the application with the variables:

   - `AD_HOST`: The Active Directory host.
   - `AD_USER_DN`: The user group DN.

2. Install the dependencies with `composer install`.
3. Run the application with `php -S localhost:3000` and access it at [ `http://localhost:3000` ](http://localhost:3000).
