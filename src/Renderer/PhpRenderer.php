<?php
/**
 * Part of formosa project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Renderer;

use Windwalker\Data\Data;

/**
 * Class PhpRenderer
 *
 * @since 1.0
 */
class PhpRenderer extends AbstractRenderer
{
	/**
	 * render
	 *
	 * @param string        $file
	 * @param array|object  $data
	 *
	 * @throws  \UnexpectedValueException
	 * @return  string
	 */
	public function render($file, $data = array())
	{
		$data = new Data($data);

		$filePath = $this->findFile($file);

		if (!$filePath)
		{
			throw new \UnexpectedValueException(sprintf('File: %s not found', $filePath));

			return '';
		}

		// Start an output buffer.
		ob_start();

		// Load the layout.
		include $filePath;

		// Get the layout contents.
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * finFile
	 *
	 * @param string $file
	 * @param string $ext
	 *
	 * @return  string
	 */
	public function findFile($file, $ext = 'php')
	{
		return parent::findFile($file, $ext);
	}
}
