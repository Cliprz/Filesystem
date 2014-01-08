<?php

$classPath = realpath('../Filesystem.php');

// Call composer to get PHPUnit or you can call your PHPUnit autoloader
include ('../vendor/autoload.php');
include($classPath);

$Filesystem = new Cliprz\Filesystem\Filesystem();
var_dump($Filesystem->guessMimeType('jpeg'));

?>