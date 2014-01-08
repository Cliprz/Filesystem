<?php namespace Cliprz\Filesystem\Filesystem\Tests;

$classPath = realpath('../Filesystem.php');

// Call composer to get PHPUnit or you can call your PHPUnit autoloader
include ('../vendor/autoload.php');
include($classPath);

use Cliprz\Filesystem\Filesystem as Filesystem;
use PHPUnit_Framework_TestCase as TestCase;

class FilesystemTest extends TestCase {

	public $Filesystem;
	public $thisPath;

	public function setUp () {
		$this->Filesystem = new Filesystem();
		$this->thisPath   = __DIR__.DIRECTORY_SEPARATOR;
	}

	public function testReadDirectory () {
		$list = $this->Filesystem->readDirectory(realpath('../'));
		$this->assertContains(realpath('../Filesystem.php'),$list);
		var_dump($list);
	}

	public function testExtension () {
		$this->assertEquals('php',$this->Filesystem->extension(realpath('../Filesystem.php')));
		$this->assertEquals('json',$this->Filesystem->extension(realpath('../composer.json')));
	}

}

?>