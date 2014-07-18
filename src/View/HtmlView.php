<?php
/**
 * Part of auth project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\View;

use Formosa\Model\DatabaseModel;
use Formosa\Renderer\AbstractRenderer;
use Formosa\Renderer\PhpRenderer;
use Formosa\Utilities\Queue\Priority;
use Joomla\Filesystem\Path;
use Joomla\Model\ModelInterface;
use Joomla\View\AbstractHtmlView;
use Windwalker\Data\Data;

/**
 * Class HtmlView
 *
 * @since 1.0
 */
class HtmlView extends AbstractHtmlView
{
	/**
	 * Property data.
	 *
	 * @var  \Windwalker\Data\Data
	 */
	protected $data = null;

	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = null;

	/**
	 * Property renderer.
	 *
	 * @var  \Formosa\Renderer\AbstractRenderer
	 */
	protected $renderer;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   ModelInterface     $model    The model object.
	 * @param   \SplPriorityQueue  $paths    The paths queue.
	 * @param   AbstractRenderer   $renderer The renderer engine.
	 */
	public function __construct(ModelInterface $model = null, \SplPriorityQueue $paths = null, AbstractRenderer $renderer = null)
	{
		$model = $model ? : new DatabaseModel;

		$this->renderer = $renderer ? : new PhpRenderer;

		parent::__construct($model, $paths);
	}

	/**
	 * getData
	 *
	 * @return  \Windwalker\Data\Data
	 */
	public function getData()
	{
		if (!$this->data)
		{
			$this->data = new Data;
		}

		return $this->data;
	}

	/**
	 * setData
	 *
	 * @param   \Windwalker\Data\Data $data
	 *
	 * @return  TwigHtmlView  Return self to support chaining.
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * render
	 *
	 * @return  string
	 *
	 * @throws \RuntimeException
	 */
	public function render()
	{
		$this->getName();

		$data = $this->getData();

		$this->prepareData($data);

		$data->view = new Data;

		$data->view->name = $this->getName();
		$data->view->layout = $this->getLayout();

		$this->renderer->setPaths($this->paths);

		return $this->renderer->render($this->getLayout(), (array) $data);
	}

	/**
	 * getName
	 *
	 * @return  string
	 */
	public function getName()
	{
		if (!$this->name)
		{
			$name = get_called_class();

			// If we are using this class as default view, return default name.
			if ($name == __CLASS__)
			{
				return $this->name = 'default';
			}

			$name = explode('\\', $name);

			array_pop($name);

			$name = array_pop($name);

			$this->name = strtolower($name);
		}

		return $this->name;
	}

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
	}

	/**
	 * getPath
	 *
	 * @param string $layout
	 * @param string $ext
	 *
	 * @return  mixed
	 */
	public function getPath($layout, $ext = 'php')
	{
		$this->paths->insert(FORMOSA_TEMPLATE . '/_global', Priority::NORMAL);

		return parent::getPath($layout, $ext);
	}

	/**
	 * Method to load the paths queue.
	 *
	 * @return  \SplPriorityQueue  The paths queue.
	 *
	 * @since   1.0
	 */
	protected function loadPaths()
	{
		$paths = parent::loadPaths();

		$paths->insert(FORMOSA_TEMPLATE . '/' . $this->getName(), Priority::NORMAL);

		return $paths;
	}
}
 