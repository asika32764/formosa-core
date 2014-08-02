<?php
/**
 * Part of auth project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Formosa\Model;

use Formosa\Factory;
use Windwalker\Database\Driver\DatabaseAwareTrait;
use Windwalker\Database\Driver\DatabaseDriver;
use Windwalker\Model\AbstractModel;
use Windwalker\Model\DatabaseModelInterface;
use Windwalker\Registry\Registry;

/**
 * Class DatabaseModel
 *
 * @since 1.0
 */
class Model extends AbstractModel implements DatabaseModelInterface, \ArrayAccess
{
	use DatabaseAwareTrait;

	/**
	 * Property cache.
	 *
	 * @var  array
	 */
	protected $cache = array();

	/**
	 * Property magicMethodPrefix.
	 *
	 * @var  array
	 */
	protected $magicMethodPrefix = array(
		'get',
		'load'
	);

	/**
	 * Instantiate the model.
	 *
	 * @param   Registry        $state  The model state.
	 * @param   DatabaseDriver  $db     The database adapter.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $state = null, DatabaseDriver $db = null)
	{
		$this->db = $db ? : Factory::getDbo();

		parent::__construct($state);
	}

	/**
	 * __call
	 *
	 * @param string $name
	 * @param array  $args
	 *
	 * @return  mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __call($name, $args = array())
	{
		$allow = false;

		foreach ($this->magicMethodPrefix as $prefix)
		{
			if (substr($name, 0, $prefix) == $prefix)
			{
				$allow = true;

				break;
			}
		}

		if (!$allow)
		{
			throw new \InvalidArgumentException(sprintf("Method %s::%s not found.", get_called_class(), $name));
		}

		return null;
	}

	/**
	 * getStoredId
	 *
	 * @param string $id
	 *
	 * @return  string
	 */
	public function getCacheId($id = null)
	{
		$id = $id . json_encode($this->state->toArray());

		return md5($id);
	}

	/**
	 * getCache
	 *
	 * @param string $id
	 *
	 * @return  mixed
	 */
	protected function getCache($id = null)
	{
		$id = $this->getCacheId($id);

		if (empty($this->cache[$id]))
		{
			return null;
		}

		return $this->cache[$id];
	}

	/**
	 * setCache
	 *
	 * @param string $id
	 * @param mixed  $item
	 *
	 * @return  mixed
	 */
	protected function setCache($id = null, $item = null)
	{
		$id = $this->getCacheId($id);

		$this->cache[$id] = $item;

		return $item;
	}

	/**
	 * hasCache
	 *
	 * @param string $id
	 *
	 * @return  bool
	 */
	protected function hasCache($id = null)
	{
		$id = $this->getCacheId($id);

		return !empty($this->cache[$id]);
	}

	/**
	 * Whether a offset exists
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return boolean True on success or false on failure.
	 *                 The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return (boolean) ($this->state->get($offset) !== null);
	}

	/**
	 * Offset to retrieve
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		return $this->state->get($offset);
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->state->set($offset, $value);
	}

	/**
	 * Offset to unset
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		$this->state->set($offset, null);
	}
}
 