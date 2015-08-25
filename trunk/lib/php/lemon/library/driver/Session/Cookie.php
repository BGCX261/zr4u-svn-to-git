<?php
/**
 * Session cookie driver.
 *
 * $Id: Cookie.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Lemon Team
 * @copyright  (c) 2007-2008 Lemon Team
 * @license    http://lemonphp.com/license.html
 */
class Session_Cookie_Driver implements Session_Driver {

	protected $cookie_name;
	protected $encrypt; // Library

	public function __construct()
	{
		$this->cookie_name = Lemon::config('session.name').'_data';

		if (Lemon::config('session.encryption'))
		{
			$this->encrypt = Encrypt::instance();
		}

	}

	public function open($path, $name)
	{
		return TRUE;
	}

	public function close()
	{
		return TRUE;
	}

	public function read($id)
	{
		$data = (string) cookie::get($this->cookie_name);

		if ($data == '')
			return $data;

		return empty($this->encrypt) ? base64_decode($data) : $this->encrypt->decode($data);
	}

	public function write($id, $data)
	{
		$data = empty($this->encrypt) ? base64_encode($data) : $this->encrypt->encode($data);

		if (strlen($data) > 4048)
		{
			throw new LemonRuntimeException('Session ('.$id.') data exceeds the 4KB limit, ignoring write.',500);
			return FALSE;
		}

		return cookie::set($this->cookie_name, $data, Lemon::config('session.expiration'));
	}

	public function destroy($id)
	{
		return cookie::delete($this->cookie_name);
	}

	public function regenerate()
	{
		session_regenerate_id(TRUE);

		// Return new id
		return session_id();
	}

	public function gc($maxlifetime)
	{
		return TRUE;
	}

} // End Session Cookie Driver Class