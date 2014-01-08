<?php namespace Cliprz\Filesystem\MimeTypes;

/*
 * This file is part of the Cliprz package.
 *
 * (c) Yousef Ismaeil <cliprz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class MimeTypesGuesser {

	/**
	 * Mime types map
	 *
	 * @var array
	 * @access private
	 */
	private $map;

    /**
     * __CLASS__ Constructor
     *
     * @access public
     */
	public function __construct () {
		$this->map = include(__DIR__.DIRECTORY_SEPARATOR.'MimeTypesMap.php');
	}

	/**
	 * Guess mime type
	 *
	 * @param string File extension
	 * @access public
	 * @return string Mime type or false in failure
	 */
	public function guess ($extension) {
		if (array_key_exists(strtolower($extension),$this->map)) {
			return $this->map[strtolower($extension)];
		}
		return false;
	}

}

?>