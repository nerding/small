# Passwords

Passwords should be secure...

That's about all I know.

The system will probably follow the guidelines from
http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/.

Engage.

-----

Passwords are stored in the database as 128 character strings, the first 64
characters are the salt hash, the second set of 64 characters is the 
super-hashed password.

When attempting to validate a password from an unhashed string:

```php
/*
  Variables:

    $inputPassword - the password being tested
    $databasePassword - the password from the database (users.password)
*/

// get the user's unique salt
$salt = substr($databasePassword, 0, 64);

// generate the password hash - must be in User class.
//
// gimmmieHash is a private function inside of Users, it just
// takes ($salt . $inputPassword) and hashes it together 100000 times
$hash = self::gimmieHash($salt, $inputPassword);

// if the hash is equal to $databasePassword, $inputPassword is the
// user's password
return $hash == $databasePassword;
```

Fun, right?