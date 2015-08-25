<?php
/**
 * Lemon Controller class. The controller class must be extended to work
 * properly, so this class is defined as abstract.
 *
 * $Id: Controller.php 4365 2009-05-27 21:09:27Z samsoir $
 *
 * @package    Core
 * @author     Lemon Team
 * @copyright  (c) 2007-2008 Lemon Team
 * @license    http://lemonphp.com/license.html
 */
abstract class Controller {

	// Allow all controllers to run in production by default
	const ALLOW_PRODUCTION = TRUE;

	/**
	 * Loads URI, and Input into this controller.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		if (Lemon::$instance == NULL)
		{
			// Set the instance to the first controller loaded
			Lemon::$instance = $this;
		}

		// URI should always be available
		$this->uri = URI::instance();

		// Input should always be available
		$this->input = Input::instance();
	}

	/**
	 * Handles methods that do not exist.
	 *
	 * @param   string  method name
	 * @param   array   arguments
	 * @return  void
	 */
	public function __call($method, $args)
	{
		// Default to showing a 404 page
		Event::run('system.404');
	}

	/**
	 * Includes a View within the controller scope.
	 *
	 * @param   string  view filename
	 * @param   array   array of view variables
	 * @return  string
	 */
	public function _lemon_load_view($lemon_view_filename, $lemon_input_data)
	{
		if ($lemon_view_filename == '')
			return;

		// Buffering on
		ob_start();

		// Import the view variables to local namespace
		extract($lemon_input_data, EXTR_SKIP);

		// Views are straight HTML pages with embedded PHP, so importing them
		// this way insures that $this can be accessed as if the user was in
		// the controller, which gives the easiest access to libraries in views
		try
		{
			include $lemon_view_filename;
		}
		catch (Exception $e)
		{
			ob_end_clean();
			throw $e;
		}

		// Fetch the output and close the buffer
		return ob_get_clean();
	}

} // End Controller Class