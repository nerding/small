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
//
// input password is sha'd once beforehand because the client sends the
// password pre-sha'd.
//
// this assumes that we haven't received it from the client
$hash = self::gimmieHash($salt, hash('sha256', $inputPassword));

// if the hash is equal to $databasePassword, $inputPassword is the
// user's password
return $hash == $databasePassword;
```

Fun, right?

----

## loging in with ajax

A [Nonce](http://en.wikipedia.org/wiki/Cryptographic_nonce) should be used any
time something secure needs to be sent over the network (specifically 
passwords).

The way Branch is going to go about doing this in the future (as in, when I
get to it) is as follows:

1.    The user attempts to login (using a login form).

2.    JavaScript hijacks that action, and instead of performing a standard
      form submit it requests a nonce from the server.

      If a nonce can't be received, we stop here and display a message to the
      user along the lines of some really bad error has occured...

3.    The server accepts the request, and generates a nonce. It stores the 
      nonce in `$_SESSION['nonce']`, and gives the client the sha256 of the
      nonce (which could technically be the nonce, but lets be extra safe
      here).

4.    The client sends the username and password (as post data), but the
      password is sent as `sha256(password) + sha256(nonce)`, instead of 
      plaintext. The reason for them being separate is so that the server can
      validate them individually.

5.    The server checks if the hashes are correct and unsets the 
      `$_SERVER['nonce']` variable. Everything else occurs as it should.

Right now, only the `ajax.php` page cares and acts upon this. That's because 
it's the only page that accepts ajax requests...

What this means is that anything that isn't ajax-ed in needs to `sha256` the
password before testing anywhere. Seriously.