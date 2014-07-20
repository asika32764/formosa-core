<?php
/**
 * Part of formosa project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Application;

use Formosa\Factory;
use Formosa\Provider\DatabaseProvider;
use Joomla\Application\AbstractWebApplication;
use Windwalker\DI\Container;
use Windwalker\Router\RestRouter;

/**
 * Class Application
 *
 * @since 1.0
 */
class WebApplication extends AbstractWebApplication
{
	/**
	 * Property router.
	 *
	 * @var  \Joomla\Router\Router
	 */
	protected $router = null;

	/**
	 * Property container.
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->loadConfiguration($this->config);

		if ($this->config->get('system.debug'))
		{
			\Formosa\Error\SimpleErrorHandler::registerErrorHandler();
		}

		Factory::$app = $this;

		$this->container = Factory::getContainer();

		// Debug system
		define('FORMOSA_DEBUG', $this->config->get('system.debug'));

		$this->registerProviders($this->container);
	}

	/**
	 * registerProviders
	 *
	 * @param Container $container
	 *
	 * @return  void
	 */
	protected function registerProviders(Container $container)
	{
		$container->registerServiceProvider(new DatabaseProvider);
	}

	/**
	 * loadConfiguration
	 *
	 * @param \Joomla\Registry\Registry $config
	 *
	 * @return  void
	 */
	protected function loadConfiguration($config)
	{
	}

	/**
	 * Method to run the application routines.  Most likely you will want to instantiate a controller
	 * and execute it, or perform some sort of task directly.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		$content = $this->getController()->execute();

		$this->setBody($content);
	}

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		// @event onBeforeExecute

		// Perform application routines.
		$this->doExecute();

		// @event onAfterExecute

		// If gzip compression is enabled in configuration and the server is compliant, compress the output.
		if ($this->get('gzip') && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler'))
		{
			$this->compress();
		}

		// @event onBeforeRespond

		// Send the application response.
		$this->respond();

		// @event onAfterRespond
	}

	/**
	 * getRouter
	 *
	 * @return  \Joomla\Router\Router
	 */
	public function getRouter()
	{
		if (!$this->router)
		{
			$router = new RestRouter($this->input);

			$routing = $this->loadRoutingConfiguration();

			$router->setDefaultController($routing['_default'])
				->addMaps($routing)
				->setMethodInPostRequest(true);

			$this->router = $router;
		}

		return $this->router;
	}

	/**
	 * getController
	 *
	 * @param string $route
	 *
	 * @return  mixed
	 */
	public function getController($route = null)
	{
		$route = $route ? : $this->get('uri.route');

		$router = $this->getRouter();

		$class = $router->getController($route);

		return new $class($this->input, $this);
	}

	/**
	 * loadRoutingConfiguration
	 *
	 * @return  mixed
	 */
	protected function loadRoutingConfiguration()
	{
		return json_decode(file_get_contents(FORMOSA_ETC . '/routing.json'), true);
	}

	/**
	 * getContainer
	 *
	 * @return  \Joomla\DI\Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * setContainer
	 *
	 * @param   \Joomla\DI\Container $container
	 *
	 * @return  Application  Return self to support chaining.
	 */
	public function setContainer(Container $container)
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * addFlash
	 *
	 * @param string $message
	 * @param string $type
	 *
	 * @return  Application
	 */
	public function addFlash($message, $type = 'info')
	{
		$session = Factory::getSession();

		$session->getFlashBag()->add($type, $message);

		return $this;
	}

	/**
	 * output
	 *
	 * @return  void
	 */
	public function output()
	{
		parent::respond();
	}

	/**
	 * toString
	 *
	 * @return  string
	 */
	public function __toString()
	{
		// Start an output buffer.
		ob_start();

		// Load the layout.
		parent::respond();

		// Get the layout contents.
		$output = ob_get_clean();

		return $output;
	}
}
 