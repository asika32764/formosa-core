<?php
/**
 * Part of formosa project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Renderer;

use Formosa\Renderer\Twig\FormosaExtension;

/**
 * Class PhpRenderer
 *
 * @since 1.0
 */
class TwigRenderer extends AbstractRenderer
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
		$loader = new \Twig_Loader_Filesystem(iterator_to_array($this->getPaths()));

		$loader->addPath(FORMOSA_TEMPLATE . '/_global');

		$config = array(
			'debug' => FORMOSA_DEBUG
		);

		$twig = new \Twig_Environment($loader, $config);

		$twig->addExtension(new FormosaExtension);

		if (FORMOSA_DEBUG)
		{
			$twig->addExtension(new \Twig_Extension_Debug);
		}

		return $twig->render($file, $data);
	}
}
