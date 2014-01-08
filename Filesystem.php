<?php namespace Cliprz\Filesystem;

/*
 * This file is part of the Cliprz package.
 *
 * (c) Yousef Ismaeil <cliprz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use FilesystemIterator;
use RecursiveDirectoryIterator;
use Cliprz\Filesystem\MimeTypes\MimeTypesGuesser;

class Filesystem {

	/**
	 * Kilobyte
	 *
	 * @const integer
	 */
	const KILOBYTE = 1024;

	/**
	 * Megabyte
	 *
	 * @const integer
	 */
	const MEGABYTE = 1048576;

	/**
	 * Gigabyte
	 *
	 * @const integer
	 */
	const GIGABYTE = 1073741824;

	/**
	 * Terabyte
	 *
	 * @const integer
	 */
	const TERABYTE = 1099511627776;

	/**
	 * Time format
	 * 
	 * @var string
	 * @access private
	 */
	private $timeFormat = 'l d/F/Y h:m:s\s A e';

	/**
	 * Handle Cliprz\Filesystem\MimeTypes\MimeTypesGuesser
	 *
	 * @var object
	 * @access private
	 * @static
	 * @singleton
	 */
	private static $MimeTypesGuesser;

    /**
     * __CLASS__ Constructor
     *
     * @access public
     */
	public function __construct () {
		if (!static::$MimeTypesGuesser instanceof MimeTypesGuesser) {
			static::$MimeTypesGuesser = new MimeTypesGuesser();
		}
	}

	/**
	 * Guess mime type
	 *
	 * @param string file extension
	 * @access public
	 */
	public function guessMimeType ($extension) {
		return static::$MimeTypesGuesser->guess($extension);
	}

	/**
	 * Get file or directory properties
	 *
	 * @param string Path to the file
	 * @access public
	 * @return array file properties, or empty array otherwise
	 */
	public function prop ($file) {
		$data = [];
		if (file_exists($file)) {
			$data['dirname'] = pathinfo($file,PATHINFO_DIRNAME);
			$data['basename'] = pathinfo($file,PATHINFO_BASENAME);
			if (is_file($file)) {
				$data['extension'] = pathinfo($file,PATHINFO_EXTENSION);
				if (false !== ($mimetype = $this->guessMimeType($data['extension']))) {
					$data['mimetype'] = $mimetype;
				}
			}
			$data['filename'] = pathinfo($file,PATHINFO_FILENAME);
			$data['fullpath'] = realpath(dirname($file)).DIRECTORY_SEPARATOR.$data['basename'];
			$data['filetype'] = filetype($file);
			if (is_file($file)) {
				$data['bytesize'] = filesize($file);
				$data['readablesize'] = $this->bytesToSize($data['bytesize']);
			} else {
				$data['bytesize'] = $this->directorySize($file);
				$data['readablesize'] = $this->bytesToSize($data['bytesize']);
			}
			$data['isreadable'] = is_readable($file);
			$data['iswritable'] = is_writable($file);
			$data['lastaccesstime'] = fileatime($file);
			$data['lastaccesshumn'] = date($this->timeFormat,$data['lastaccesstime']);
			$data['modificationtime'] = filemtime($file);
			$data['modificationhumn'] = date($this->timeFormat,$data['modificationtime']);
			return (array) ($data);
		}
		return $data;	
	}

	/**
	 * Delete a single file or direcotry
	 *
	 * @param string
	 * @access public
	 * @return boolean true if the file or directory exists and deleted, false otherwise
	 * @note use Filesystem::format() method to delete all directory files and subdirectories  
	 */
	public function delete ($file) {
		if (is_file($file)) {
			return (boolean) (@unlink($file));
		} else if (is_dir($file)) {
			return ((@rmdir($file) === true) ? true : false);
		}
		return false;
	}

	/**
	 * Remove all path files and directories
	 *
	 * @param string  Path to the directory
	 * @param boolean Removal en masse, by default false
	 * @access public
	 * @return boolean true on success or false on failure
	 */
	public function format ($directory,$force=false) {
		if (is_dir($directory)) {
			$FilesystemIterator = new FilesystemIterator($directory);
			foreach ($FilesystemIterator as $Iterator) {
				if ($Iterator->isDir()) {
					if (false === $this->format($Iterator->getRealPath()))
						return false;
				} else {
					if (false === $this->delete($Iterator->getRealPath()))
						return false;
				}
			}
			if (!$force) {
				if (false === $this->delete($directory))
					return false;
			}
			return true;
		}
		return false;	
	}

	/**
	 * Copy a full directory path to another destination
	 *
	 * @param string The directory path
	 * @param string The destination path
	 * @return boolean true on success or false on failure
	 */
	public function copyDirectory ($directory,$destination) {
		// If no directory to copy return false
		if (!is_dir($directory)) {
			return false;
		} else {
			// Create a FilesystemIterator instance 
			$FilesystemIterator = new FilesystemIterator($directory,FilesystemIterator::SKIP_DOTS);
			// If destination directory not exists
			if (!is_dir($destination)) {
				// Create a destination directory
				mkdir($destination,0777,true);
			}
			// Loop files and directories
			foreach ($FilesystemIterator as $Iterator) {
				// If directory
				if ($Iterator->isDir()) {
					// Copy directory or return false
					if (false === $this->copyDirectory($Iterator->getPathname(),
						$destination.DIRECTORY_SEPARATOR.$Iterator->getBasename()))
							return false;
				} else { // Else if file
					// Copy file or return false
					if (false === copy($Iterator->getPathname(),
						$destination.DIRECTORY_SEPARATOR.$Iterator->getBasename()))
							return false;
				}
			}
			// If copying finished without any failure return true
			return true;
		}
	}

	/**
	 * Read all path files and directories
	 *
	 * @param string The directory path
	 * @access public
	 * @return array
	 */
	public function readDirectory ($directory) {
		static $files = [];
		// directory checking
		if (!empty($directory) && is_dir($directory)) {
			// Create a FilesystemIterator instance 
			$FilesystemIterator = new FilesystemIterator($directory,FilesystemIterator::SKIP_DOTS);
			// Loop files and directories
			foreach ($FilesystemIterator as $Iterator) {
				// If directory
				if ($Iterator->isDir()) {
					$files[] = $Iterator->getRealPath();
					$this->readDirectory($Iterator);
				} else { // If file
					$files[] = $Iterator->getRealPath();
				}
			}
			// Sort files before return
			sort($files);
		}
		return (array) ($files);
	}

	/**
	 * Get file extension
	 *
	 * @param string The file path
	 * @access public
	 * @return string file extension or false on failure
	 */
	public function extension ($file) {
		if (preg_match('`\.[a-z0-9]+$`',$file)) {
			$slice = explode('.',$file);
			return array_pop($slice);
		}
		return false;
	}

	/**
	 * Convert bytes to human readable format
	 *
	 * @param integer bytes Size in bytes to convert
	 * @access public
	 * @return string
	 */
	public function bytesToSize ($bytes,$precision=2) {  
		if (($bytes >= 0) && ($bytes < self::KILOBYTE)) {
			return sprintf('%s B',$bytes);
		} elseif (($bytes >= self::KILOBYTE) && ($bytes < self::MEGABYTE)) {
			return sprintf('%s KB',round($bytes / self::KILOBYTE, $precision));
		} elseif (($bytes >= self::MEGABYTE) && ($bytes < self::GIGABYTE)) {
			return sprintf('%s  MB',round($bytes / self::MEGABYTE, $precision));
		} elseif (($bytes >= self::GIGABYTE) && ($bytes < self::TERABYTE)) {
			return sprintf('%s GB',round($bytes / self::GIGABYTE, $precision));
		} elseif ($bytes >= self::TERABYTE) {
			return sprintf('%s TB',round($bytes / self::TERABYTE, $precision));
		}
		return sprintf('%s B',$bytes);
	}

	/**
	 * Get direcotry size
	 *
	 * @param string The directory path
	 * @access public
	 * @return integer
	 */
	public function directorySize ($directory) {
		// Static size varaibale to make sure get the currect size
		static $size = 0;
		// Create a FilesystemIterator instance
		$FilesystemIterator = new RecursiveDirectoryIterator($directory,FilesystemIterator::SKIP_DOTS);
		// Loop files and directories
		foreach($FilesystemIterator as $Iterator) {
			// If is directory
			if ($Iterator->isDir()) {
				// return again to get target directory size
				$this->directorySize($Iterator);
			} else { // else if file
				// Add the size to a static size
				$size = $size + $Iterator->getSize();
			}
		}
		return $size;
	}

}

?>