<?php
/**
 * APC-based Cache driver.
 *
 * $Id: Apc.php 4046 2009-03-05 19:23:29Z Shadowhand $
 *
 * @package    Cache
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Cache_Apc_Driver implements Cache_Driver {

	public function __construct()
	{
		if ( ! extension_loaded('apc'))
			throw new LemonRuntimeException('cache.extension_not_loaded',500);
	}

	public function get($id)
	{
		return (($return = apc_fetch($id)) === FALSE) ? NULL : $return;
	}

	public function set($id, $data, array $tags = NULL, $lifetime)
	{
		if ( ! empty($tags))
		{
		    throw new LemonRuntimeException('Cache: tags are unsupported by the APC driver',500);
		}

		return apc_store($id, $data, $lifetime);
	}

	public function find($tag)
	{
	    throw new LemonRuntimeException('Cache: tags are unsupported by the APC driver',500);

		return array();
	}

	public function delete($id, $tag = FALSE)
	{
		if ($tag === TRUE)
		{
			throw new LemonRuntimeException('Cache: tags are unsupported by the APC driver',500);
			return FALSE;
		}
		elseif ($id === TRUE)
		{
			return apc_clear_cache('user');
		}
		else
		{
			return apc_delete($id);
		}
	}

	public function delete_expired()
	{
		return TRUE;
	}

} // End Cache APC Driver