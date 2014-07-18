<?php
/**
 * Part of formosa project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Renderer;

use Formosa\Utilities\Queue\Priority;

/**
 * Class AbstractRenderer
 *
 * @since 1.0
 */
abstract class AbstractRenderer
{
	/**
	 * Property paths.
	 *
	 * @var  \SplPriorityQueue
	 */
	protected $paths = null;

	/**
	 * Class init.
	 *
	 * @param array $paths
	 */
	public function __construct($paths = null)
	{
		$this->paths = ($paths instanceof \SplPriorityQueue) ? $paths : Priority::createQueue($paths, Priority::NORMAL);
	}

	/**
	 * render
	 *
	 * @param string $file
	 * @param array  $data
	 *
	 * @return  string
	 */
	abstract public function render($file, $data = array());

	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @see     ViewInterface::escape()
	 * @since   1.0
	 */
	public function escape($output)
	{
		// Escape the output.
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * finFile
	 *
	 * @param string $file
	 * @param string $ext
	 *
	 * @return  string
	 */
	public function findFile($file, $ext = '')
	{
		$paths = clone $this->paths;

		$file = str_replace('.', '/', $file);

		$ext = $ext ? '.' . trim($ext, '.') : '';

		foreach ($paths as $path)
		{
			$filePath = $path . '/' . $file . $ext;

			if (is_file($filePath))
			{
				return realpath($filePath);
			}
		}

		return null;
	}

	/**
	 * getPaths
	 *
	 * @return  \SplPriorityQueue
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * setPaths
	 *
	 * @param   \SplPriorityQueue $paths
	 *
	 * @return  AbstractRenderer  Return self to support chaining.
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;

		return $this;
	}
}
 