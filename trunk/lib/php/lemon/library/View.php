<?php
/**
 * Loads and displays Lemon view files. Can also handle output of some binary
 * files, such as image, Javascript, and CSS files.
 *
 * $Id: View.php 4072 2009-03-13 17:20:38Z jheathco $
 *
 * @package    Core
 * @author     Lemon Team
 * @copyright  (c) 2007-2008 Lemon Team
 * @license    http://lemonphp.com/license.html
 */
class View {

	// The view file name and type
	protected $lemon_filename = FALSE;
	protected $lemon_filetype = FALSE;

	// View variable storage
	protected $lemon_local_data = array();
	protected static $lemon_global_data = array();

	/**
	 * Creates a new View using the given parameters.
	 *
	 * @param   string  view name
	 * @param   array   pre-load data
	 * @param   string  type of file: html, css, js, etc.
	 * @return  object
	 */
	public static function factory($name = NULL, $data = NULL, $type = NULL)
	{
		return new View($name, $data, $type);
	}

	/**
	 * Attempts to load a view and pre-load view data.
	 *
	 * @throws  LemonRuntimeException  if the requested view cannot be found
	 * @param   string  view name
	 * @param   array   pre-load data
	 * @param   string  type of file: html, css, js, etc.
	 * @return  void
	 */
	public function __construct($name = NULL, $data = NULL, $type = NULL)
	{
		if (is_string($name) AND $name !== '')
		{
			// Set the filename
			$this->set_filename($name, $type);
		}

		if (is_array($data) AND ! empty($data))
		{
			// Preload data using array_merge, to allow user extensions
			$this->lemon_local_data = array_merge($this->lemon_local_data, $data);
		}
	}

	/**
	 * Magic method access to test for view property
	 *
	 * @param   string   View property to test for
	 * @return  boolean
	 */
	public function __isset($key = NULL)
	{
		return $this->is_set($key);
	}

	/**
	 * Sets the view filename.
	 *
	 * @chainable
	 * @param   string  view filename
	 * @param   string  view file type
	 * @return  object
	 */
	public function set_filename($name, $type = NULL)
	{
		if ($type == NULL)
		{
			// Load the filename and set the content type
			$this->lemon_filename = Lemon::find_file('view', $name, TRUE);
			$this->lemon_filetype = '.php';
		}
		else
		{
			// Check if the filetype is allowed by the configuration
			if ( ! in_array($type, Lemon::config('view.allowed_filetypes')))
				throw new LemonRuntimeException('core.invalid_filetype '.$type, 500);

			// Load the filename and set the content type
			$this->lemon_filename = Lemon::find_file('view', $name, TRUE, $type);
			$this->lemon_filetype = Lemon::config('mimes.'.$type);

			if ($this->lemon_filetype == NULL)
			{
				// Use the specified type
				$this->lemon_filetype = $type;
			}
		}

		return $this;
	}

	/**
	 * Sets a view variable.
	 *
	 * @param   string|array  name of variable or an array of variables
	 * @param   mixed         value when using a named variable
	 * @return  object
	 */
	public function set($name, $value = NULL)
	{
		if (is_array($name))
		{
			foreach ($name as $key => $value)
			{
				$this->__set($key, $value);
			}
		}
		else
		{
			$this->__set($name, $value);
		}

		return $this;
	}

	/**
	 * Checks for a property existence in the view locally or globally. Unlike the built in __isset(),
	 * this method can take an array of properties to test simultaneously.
	 *
	 * @param string $key property name to test for
	 * @param array $key array of property names to test for
	 * @return boolean property test result
	 * @return array associative array of keys and boolean test result
	 */
	public function is_set( $key = FALSE )
	{
		// Setup result;
		$result = FALSE;

		// If key is an array
		if (is_array($key))
		{
			// Set the result to an array
			$result = array();

			// Foreach key
			foreach ($key as $property)
			{
				// Set the result to an associative array
				$result[$property] = (array_key_exists($property, $this->lemon_local_data) OR array_key_exists($property, View::$lemon_global_data)) ? TRUE : FALSE;
			}
		}
		else
		{
			// Otherwise just check one property
			$result = (array_key_exists($key, $this->lemon_local_data) OR array_key_exists($key, View::$lemon_global_data)) ? TRUE : FALSE;
		}

		// Return the result
		return $result;
	}

	/**
	 * Sets a bound variable by reference.
	 *
	 * @param   string   name of variable
	 * @param   mixed    variable to assign by reference
	 * @return  object
	 */
	public function bind($name, & $var)
	{
		$this->lemon_local_data[$name] =& $var;

		return $this;
	}

	/**
	 * Sets a view global variable.
	 *
	 * @param   string|array  name of variable or an array of variables
	 * @param   mixed         value when using a named variable
	 * @return  void
	 */
	public static function set_global($name, $value = NULL)
	{
		if (is_array($name))
		{
			foreach ($name as $key => $value)
			{
				View::$lemon_global_data[$key] = $value;
			}
		}
		else
		{
			View::$lemon_global_data[$name] = $value;
		}
	}

	/**
	 * Magically sets a view variable.
	 *
	 * @param   string   variable key
	 * @param   string   variable value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->lemon_local_data[$key] = $value;
	}

	/**
	 * Magically gets a view variable.
	 *
	 * @param  string  variable key
	 * @return mixed   variable value if the key is found
	 * @return void    if the key is not found
	 */
	public function &__get($key)
	{
		if (isset($this->lemon_local_data[$key]))
			return $this->lemon_local_data[$key];

		if (isset(View::$lemon_global_data[$key]))
			return View::$lemon_global_data[$key];

		if (isset($this->$key))
			return $this->$key;
	}

	/**
	 * Magically converts view object to string.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			// Display the exception using its internal __toString method
			return (string) $e;
		}
	}

	/**
	 * Renders a view.
	 *
	 * @param   boolean   set to TRUE to echo the output instead of returning it
	 * @param   callback  special renderer to pass the output through
	 * @return  string    if print is FALSE
	 * @return  void      if print is TRUE
	 */
	public function render($print = FALSE, $renderer = FALSE)
	{
		if (empty($this->lemon_filename))
			throw new LemonRuntimeException('core.view_set_filename', 500);

		if (is_string($this->lemon_filetype))
		{
			// Merge global and local data, local overrides global with the same name
			$data = array_merge(View::$lemon_global_data, $this->lemon_local_data);

			// Load the view in the controller for access to $this
			$output = Lemon::$instance->_lemon_load_view($this->lemon_filename, $data);

			if ($renderer !== FALSE AND is_callable($renderer, TRUE))
			{
				// Pass the output through the user defined renderer
				$output = call_user_func($renderer, $output);
			}

			if ($print === TRUE)
			{
				// Display the output
				echo $output;
				return;
			}
		}
		else
		{
			// Set the content type and size
			header('Content-Type: '.$this->lemon_filetype[0]);

			if ($print === TRUE)
			{
				if ($file = fopen($this->lemon_filename, 'rb'))
				{
					// Display the output
					fpassthru($file);
					fclose($file);
				}
				return;
			}

			// Fetch the file contents
			$output = file_get_contents($this->lemon_filename);
		}

		return $output;
	}
} // End View