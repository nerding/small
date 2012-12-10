# Notes

## input

Always use `filter_input()` instead of plain `$_`. ALWAYS.

## security

While it's nice to have something working, it's *better* to have something
secure. Security is about as important as simplicity for end users.

Mostly, I don't want to have to continually maintain this, making security
fixes and the like, once it's finished. I want it to be done right the first
time, and not require extra work on my part.

Though, I suppose, the hope is that by the time there are enough people using
this to warrent somebody hacking it there will be some form of community
behind this helping make it work.

Look, I don't know, okay.

## includes and requires

All includes should use `__dir__` plus the relative path of the file.

For example, if I'm in `./aDir/sub/file.ext` and want to access the file
`./aDir/sub2/diffFile.ext`, I would use

```php
require_once(__dir__ . "/../sub2/diffFile.ext");
```

Why? Because it makes things work. Working things are *good*.