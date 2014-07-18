<?php
/**
 * Part of formosa project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Renderer\Helper;

use Formosa\Factory;
use Formosa\Helper\Set\HelperSet;
use Joomla\Date\Date;

/**
 * Class RendererHelper
 *
 * @since 1.0
 */
class RendererHelper
{
	/**
	 * getGlobalVariables
	 *
	 * @return  array
	 */
	public static function getGlobalVariables()
	{
		$app = Factory::getApplication();

		return array(
			'uri' => $app->get('uri'),
			'app' => $app,
			'container' => Factory::getContainer(),
			'helper' => new HelperSet,
			'flash' => Factory::getSession()->getFlashBag()->all(),
			'datetime' => new Date('now', new \DateTimeZone($app->get('system.timezone', 'UTC')))
		);
	}
}
 