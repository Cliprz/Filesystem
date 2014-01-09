<?php

/**
 * This Test need PHPUnit
 * Call composer autoload to get PHPUnit or Call PHPUnit autoloader
 * @note Don't forget to require cliprz/filesystem on composer
 */
include('../vendor/autoload.php');

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

	public function testGuessMimeType () {
		$this->assertEquals('application/zip',$this->Filesystem->guessMimeType('zip'));
	}

	public function testProp () {
		var_dump($this->Filesystem->prop($this->thisPath.'FilesystemTest.php'));
	}

	public function testDelete () {
		$TestingDeleteDirectory = $this->thisPath.'TestingDeleteDirectory';
		if (!is_dir($TestingDeleteDirectory)) {
			mkdir($TestingDeleteDirectory,0777,true);
		}

		$TestingDeleteFile = $this->thisPath.'TestingDeleteFile.php';
		if (!is_file($TestingDeleteFile)) {
			file_put_contents($TestingDeleteFile,'');
		}

		$this->assertTrue($this->Filesystem->delete($TestingDeleteDirectory));
		$this->assertTrue($this->Filesystem->delete($TestingDeleteFile));
	}

	public function testFormat () {
		$TestingFormatDirectory = $this->thisPath.'TestingFormatDirectory';

		$files = [
			'Abc.php',
			'Xyz.php',
			'Foo.php',
			'Bar.php'
		];

		if (!is_dir($TestingFormatDirectory)) {
			mkdir($TestingFormatDirectory,0777,true);
			foreach ($files as $f) {
				if (!is_file($TestingFormatDirectory.DIRECTORY_SEPARATOR.$f)) {
					file_put_contents($TestingFormatDirectory.DIRECTORY_SEPARATOR.$f,'');
				}
			}
		}

		$this->assertTrue($this->Filesystem->format($TestingFormatDirectory));		
	}

	public function testCopyDirectory () {
		$TestingCopyDirectory = $this->thisPath.'TestingCopyDirectory';

		$files = [
			'Abc.php',
			'Xyz.php',
			'Foo.php',
			'Bar.php'
		];

		if (!is_dir($TestingCopyDirectory)) {
			mkdir($TestingCopyDirectory,0777,true);
			foreach ($files as $f) {
				if (!is_file($TestingCopyDirectory.DIRECTORY_SEPARATOR.$f)) {
					file_put_contents($TestingCopyDirectory.DIRECTORY_SEPARATOR.$f,'');
				}
			}
		}

		$this->assertTrue($this->Filesystem->copyDirectory(
			$TestingCopyDirectory,
			$this->thisPath.'TestingDestCopyDirectory'));

		$this->assertTrue($this->Filesystem->format($TestingCopyDirectory));
		$this->assertTrue($this->Filesystem->format($this->thisPath.'TestingDestCopyDirectory'));
	}

	public function testBytesToSize () {
		var_dump($this->Filesystem->bytesToSize(139230));
	}

	public function testDirectorySize () {
		$size = $this->Filesystem->directorySize(realpath('../'));
		var_dump($size,$this->Filesystem->bytesToSize($size));
	}

}

?>