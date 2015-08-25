<?php
/**
 * Session cache driver.
 *
 * Cache library config goes in the session.storage config entry:
 * $config['storage'] = array(
 *     'driver' => 'apc',
 *     'requests' => 10000
 * );
 * Lifetime does not need to be set as it is
 * overridden by the session expiration setting.
 *
 * $Id: Cache.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Lemon Team
 * @license    http://kohanaphp.com/license.html
 */
class Session_Cache_Driver implements Session_Driver {

	protected $cache;
	protected $encrypt;

	public function __construct()
	{
		// Load Encrypt library
		if (Lemon::config('session.encryption'))
		{
			$this->encrypt = new Encrypt;
		}

	}

	public function open($path, $name)
	{
		$config = Lemon::config('session.storage');

		if (empty($config))
		{
			// Load the default group
			$config = Lemon::config('cache.default');
		}
		elseif (is_string($config))
		{
			$name = $config;

			// Test the config group name
			if (($config = Lemon::config('cache.'.$config)) === NULL)
				throw new LemonRuntimeException('cache.undefined_group '.$name, 500);
		}

		$config['lifetime'] = (Lemon::config('session.expiration') == 0) ? 86400 : Lemon::config('session.expiration');
		$this->cache = new Cache($config);

		return is_object($this->cache);
	}

	public function close()
	{
		return TRUE;
	}

	public function read($id)
	{
		$id = 'session_'.$id;
		if ($data = $this->cache->get($id))
		{
			return Lemon::config('session.encryption') ? $this->encrypt->decode($data) : $data;
		}

		// Return value must be string, NOT a boolean
		return '';
	}

	public function write($id, $data)
	{
		$id = 'session_'.$id;
		$data = Lemon::config('session.encryption') ? $this->encrypt->encode($data) : $data;

		return $this->cache->set($id, $data);
	}

	public function destroy($id)
	{
		$id = 'session_'.$id;
		return $this->cache->delete($id);
	}

	public function regenerate()
	{
		session_regenerate_id(TRUE);

		// Return new session id
		return session_id();
	}

	public function gc($maxlifetime)
	{
		// Just return, caches are automatically cleaned up
		return TRUE;
	}

} // End Session Cache Driver
