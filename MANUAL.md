Cliprz\Filesystem
=================
A more options to handle your files and directories with PHP.

Note
----
Read our [Tests](https://github.com/Cliprz/Filesystem/tree/master/Tests) to understand or loader require PHPUnit.

Constants
---------
* Cliprz\Filesystem\Filesystem::KILOBYTE - get Kilobyte size
* Cliprz\Filesystem\Filesystem::MEGABYTE - get Megabyte size
* Cliprz\Filesystem\Filesystem::GIGABYTE - get Gigabyte size
* Cliprz\Filesystem\Filesystem::TERABYTE - get Terabyte size

Methods
-------
* [Cliprz\Filesystem\Filesystem::guessMimeType(string $extension);](#guessMimeType)
* [Cliprz\Filesystem\Filesystem::prop(string $file);](#prop)
* [Cliprz\Filesystem\Filesystem::delete(string $file);](#delete)
* [Cliprz\Filesystem\Filesystem::format(string $directory,boolean $force = false);](#format)
* [Cliprz\Filesystem\Filesystem::copyDirectory(string $directory,string $destination);](#copyDirectory)
* [Cliprz\Filesystem\Filesystem::readDirectory(string $directory);](#readDirectory)
* [Cliprz\Filesystem\Filesystem::extension(string $file);](#extension)
* [Cliprz\Filesystem\Filesystem::bytesToSize(integer $bytes,integer $precision = 2);](#bytesToSize)
* [Cliprz\Filesystem\Filesystem::directorySize(string $directory);](#directorySize)

Create instance
---------------
```php
$Filesystem = new Cliprz\Filesystem\Filesystem();
```

<a name="guessMimeType"></a> Cliprz\Filesystem\Filesystem::guessMimeType();
---------------------------------------------------------------------------
This method handle Cliprz\Filesystem\MimeTypes\MimeTypesGuesser class, to guess the mime type from given extension.

``` php
echo $Filesystem->guessMimeType('php'); // Output = application/x-httpd-php
```

To update Mime types map you can update ```Path/To/Cliprz/Filesystem/MimeTypes/MimeTypesMap.php```

<a name="prop"></a> Cliprz\Filesystem\Filesystem::prop();
---------------------------------------------------------
To Get file or directory properties

``` php
var_dump($Filesystem->prop('Filename.php'));
var_dump($Filesystem->prop('DirectoryName'));
```

This method return to array.

<a name="delete"></a> Cliprz\Filesystem\Filesystem::delete();
-------------------------------------------------------------
To Delete a single file or direcotry.

``` php
var_dump($Filesystem->delete('Path/to/File.php'));
```

This method delete a file and empty direcotry only if you want to delete a directory with his files
you must use [Cliprz\Filesystem\Filesystem::format();](#format) method.

this method return boolean true on success or false on failure.

<a name="format"></a> Cliprz\Filesystem\Filesystem::format();
-------------------------------------------------------------
Remove all path files and directories

``` php
var_dump($Filesystem->format('Path/to/Directory'));
```

This method dose not delete parent path if you want to do that set second parameter to true

``` php
var_dump($Filesystem->format('Path/to/Directory',true));
```

in example above you will delete Path/to/Directory after you delete all files and directories inside this path.

<a name="copyDirectory"></a> Cliprz\Filesystem\Filesystem::copyDirectory();
-------------------------------------------------------------
To copy a full directory path to another destination

``` php
$Filesystem->copyDirectory('My/Classes/Direcotry','To/Includes/Classes');
```
in examole above you will copy ``` My/Classes/Direcotry ``` files and directories to ``` To/Includes/Classes ```

Note : If ``` To/Includes/Classes ``` not exists this method will created.

this method return boolean true on success or false on failure

<a name="readDirectory"></a> Cliprz\Filesystem\Filesystem::readDirectory();
-------------------------------------------------------------
Read all path files and directories

``` php
$Filesystem->copyDirectory('My/Classes/Direcotry');
```
return to files and directories array

<a name="extension"></a> Cliprz\Filesystem\Filesystem::extension();
-------------------------------------------------------------
Get file extension

``` php
$Filesystem->extension('My/Classes/SimpleClass.php'); // Output = php
```

this method return string file extension or false on failure

<a name="bytesToSize"></a> Cliprz\Filesystem\Filesystem::bytesToSize();
-------------------------------------------------------------
Convert bytes to human readable format

``` php
$Filesystem->bytes(30239);
```

The second parameter use for precision, by default 2 that mean 2.32 if 3 you will get as in example 2.32.32

<a name="directorySize"></a> Cliprz\Filesystem\Filesystem::directorySize();
-------------------------------------------------------------
Get direcotry size

``` php
$Filesystem->directorySize('Path/To/Direcotry');
```

This will get bytes number if you want to use human readable format use [Cliprz\Filesystem\Filesystem::bytesToSize();](#bytesToSize) method

``` php
$Filesystem->bytesToSize($Filesystem->directorySize('Path/To/Direcotry'));
```