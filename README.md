Cliprz\Filesystem
=================
A more options to handle your files and directories with PHP.

Download and install
--------------------
This package written under PHP 5.4 or newer. its simple to install only [Download](https://github.com/Cliprz/Filesystem/archive/master.zip) it and use it.
Don't forget to include ``` Cliprz\Filesystem\MimeTypes\MimeTypesGuesser ``` class before use ``` Cliprz\Filesystem\Filesystem ``` class see :

``` php
include ('Path/To/Cliprz/Filesystem/MimeTypes/MimeTypesGuesser.php');
include ('Path/To/Cliprz/Filesystem/Filesystem.php');
use Cliprz\Filesystem\Filesystem as Filesystem;

// Create instance
$Filesystem = new Filesystem();
```

Or just let Composer do that for you, Call Cliprz\Filesystem from Composer

```json
{
    "require": {
        "php": ">=5.4",
		"cliprz/filesystem": "1.0.*"
    }
}
```

How to use it
-------------
You can read [Manual](https://github.com/Cliprz/Filesystem/tree/master/MANUAL.md) , And you can test Cliprz\Filesystem from [Tests](https://github.com/Cliprz/Filesystem/tree/master/Tests) directory require PHPUnit.

## Author
* [Yousef Ismaeil](https://github.com/Cliprz/)

## Copyright and license
Part of Cliprz framework, Copyright 2013 Cliprz, under [MIT License](LICENSE).
