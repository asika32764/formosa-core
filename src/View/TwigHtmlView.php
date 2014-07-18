<?php
/**
 * Part of formosa project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\View;

use Formosa\Renderer\TwigRenderer;
use Joomla\Model\ModelInterface;
use Windwalker\Data\Data;

/**
 * Class TwigHtmlView
 *
 * @since 1.0
 */
class TwigHtmlView extends HtmlView
{
	/**
	 * Method to instantiate the view.
	 *
	 * @param   ModelInterface     $model  The model object.
	 * @param   \SplPriorityQueue  $paths  The paths queue.
	 */
	public function __construct(ModelInterface $model = null, \SplPriorityQueue $paths = null)
	{
		parent::__construct($model, $paths, new TwigRenderer);
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

		return $this->renderer->render($this->getLayout() . '.twig', (array) $data);
	}
}
 