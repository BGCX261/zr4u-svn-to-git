<?php
/**
 * Provides Kohana-specific helper functions. This is where the magic happens!
 *
 * $Id: Kohana.php 4372 2009-05-28 17:00:34Z ixmatus $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
/* 自定义的运行时间异常 */
if(!class_exists('LemonException')){
    class LemonException extends Exception {
        // 重定义构造器使 message 变为必须被指定的属性
        public function __construct($message, $code = 0) {
            // 自定义的代码
            // 确保所有变量都被正确赋值
            parent::__construct ( $message, $code );
        }
        // 自定义字符串输出的样式 */
        public function __toString() {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }
    class LemonRuntimeException extends LemonException {}
}

final class Lemon {

    // The singleton instance of the controller
    public static $instance;

    // Configuration
    private static $configuration;

    // Include paths
    private static $include_paths;

    // The current user agent
    public static $user_agent;

    // The final output that will displayed by Kohana
    public static $output = '';

    // Output buffering level
    private static $buffer_level;

    // Internal caches and write status
    private static $internal_cache = array();
    private static $write_cache;
    // Cache lifetime
    private static $cache_lifetime;

    public static function setup(){
        static $run;

        // This function can only be run once
        if ($run === TRUE){ return; }

        // Start the environment setup benchmark
        DEBUG==1 && Benchmark::start(SYSTEM_BENCHMARK.'_environment_setup');
        // Enable Kohana controller initialization
        //Event::add('system.set_timezone', array('Lemon', 'set_timezone_default'));
        //Event::run('system.set_timezone');
        self::set_timezone_default();
        // web应用相关设定
        if(RUNTIME_UI=='WEB'){
            // Set the user agent
            self::$user_agent = ( ! empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '');
            // Start output buffering
            ob_start(array(__CLASS__, 'output_buffer'));
            // Save buffering level
            self::$buffer_level = ob_get_level();
        }
        
        if(AUTOLOAD==1){
            // Set autoloader
            spl_autoload_register(array('Lemon', 'auto_load'));
        }
        
        // Enable Kohana routing
        if(RUNTIME_UI=='WEB'){
            // Send default text/html UTF-8 header
            header('Content-Type: text/html; charset=UTF-8');
            
            Event::add('system.routing', array('Router', 'find_uri'));
            Event::add('system.routing', array('Router', 'setup'));
            // Enable Kohana 404 pages
            Event::add('system.404', array('Lemon', 'show_404'));
            // Enable Kohana controller initialization
            Event::add('system.execute', array('Lemon', 'instance'));
            // Enable Kohana output handling
            Event::add('system.shutdown', array('Lemon', 'shutdown'));
            if (self::config('core.enable_hooks') === TRUE)
            {
                // Find all the hook files
                $hooks = self::list_files('hooks', TRUE);
                foreach ($hooks as $file)
                {
                    // Load the hook
                    include $file;
                }
            }
        }
        // Setup is complete, prevent it from being run again
        $run = TRUE;
        // Stop the environment setup routine
        DEBUG==1 && Benchmark::stop(SYSTEM_BENCHMARK.'_environment_setup');
    }

    /**
     * Loads the controller and initializes it. Runs the pre_controller,
     * post_controller_constructor, and post_controller events. Triggers
     * a system.404 event when the route cannot be mapped to a controller.
     *
     * This method is benchmarked as controller_setup and controller_execution.
     *
     * @return  object  instance of controller
     */
    public static function & instance()
    {
        if (self::$instance === NULL)
        {
            DEBUG==1 && Benchmark::start(SYSTEM_BENCHMARK.'_controller_setup');

            // Include the Controller file
            require Router::$controller_path;

            try
            {
                // Start validation of the controller
                $class = new ReflectionClass(ucfirst(Router::$controller).'_Controller');
            }
            catch (ReflectionException $e)
            {
                // Controller does not exist
                Event::run('system.404');
            }

            if ($class->isAbstract() OR (DEBUG==0 AND $class->getConstant('ALLOW_PRODUCTION') == FALSE))
            {
                // Controller is not allowed to run in production
                Event::run('system.404');
            }

            // Run system.pre_controller
            Event::run('system.pre_controller');

            // Create a new controller instance
            $controller = $class->newInstance();

            // Controller constructor has been executed
            Event::run('system.post_controller_constructor');

            try
            {
                // Load the controller method
                $method = $class->getMethod(Router::$method);

                // Method exists
                if (Router::$method[0] === '_')
                {
                    // Do not allow access to hidden methods
                    Event::run('system.404');
                }

                if ($method->isProtected() or $method->isPrivate())
                {
                    // Do not attempt to invoke protected methods
                    throw new ReflectionException('protected controller method');
                }

                // Default arguments
                $arguments = Router::$arguments;
            }
            catch (ReflectionException $e)
            {
                // Use __call instead
                $method = $class->getMethod('__call');

                // Use arguments in __call format
                $arguments = array(Router::$method, Router::$arguments);
            }

            // Stop the controller setup benchmark
            DEBUG==1 && Benchmark::stop(SYSTEM_BENCHMARK.'_controller_setup');

            // Start the controller execution benchmark
            DEBUG==1 && Benchmark::start(SYSTEM_BENCHMARK.'_controller_execution');

            // Execute the controller method
            $method->invokeArgs($controller, $arguments);

            // Controller method has been executed
            Event::run('system.post_controller');

            // Stop the controller execution benchmark
            DEBUG==1 && Benchmark::stop(SYSTEM_BENCHMARK.'_controller_execution');
        }

        return self::$instance;
    }


    public static function set_timezone_default($timezone=NULL){
        // Disable notices and "strict" errors
        $ER = error_reporting(~E_NOTICE & ~E_STRICT);
        if (function_exists('date_default_timezone_set'))
        {
            $set_timezone = NULL;
            if(!empty($timezone)){
                $set_timezone = $timezone;
            }else{
                $conf_timezone =self::config('locale.timezone');
                if(!empty($conf_timezone)){
                    $set_timezone = $conf_timezone;
                }else{
                    $set_timezone = date_default_timezone_get();
                }
            }
            date_default_timezone_set($set_timezone);
        }
        // Restore error reporting
        error_reporting($ER);
    }
    /**
     * Displays a 404 page.
     *
     * @throws  Kohana_404_Exception
     * @param   string  URI of page
     * @param   string  custom template
     * @return  void
     */
    public static function show_404()
    {
        throw new LemonRuntimeException('Not Found',404);
    }
    
    /**
     * Kohana output handler. Called during ob_clean, ob_flush, and their variants.
     *
     * @param   string  current output buffer
     * @return  string
     */
    public static function output_buffer($output)
    {
        // Could be flushing, so send headers first
        if ( ! Event::has_run('system.send_headers'))
        {
            // Run the send_headers event
            Event::run('system.send_headers');
        }
        
        self::$output   = $output;
        
        // Set and return the final output
        return self::$output;
    }

    /**
     * Closes all open output buffers, either by flushing or cleaning, and stores the Kohana
     * output buffer for display during shutdown.
     *
     * @param   boolean  disable to clear buffers, rather than flushing
     * @return  void
     */
    public static function close_buffers($flush = TRUE)
    {
        if (ob_get_level() >= self::$buffer_level)
        {
            // Set the close function
            $close = ($flush === TRUE) ? 'ob_end_flush' : 'ob_end_clean';

            while (ob_get_level() > self::$buffer_level)
            {
                // Flush or clean the buffer
                $close();
            }

            // Store the Kohana output buffer
            ob_end_clean();
        }
    }

    /**
     * Triggers the shutdown of Kohana by closing the output buffer, runs the system.display event.
     *
     * @return  void
     */
    public static function shutdown()
    {
        if(RUNTIME_UI=='WEB'){
            // Close output buffers
            self::close_buffers(TRUE);
            // Run the output event
            Event::run('system.display', self::$output);
    
            // Render the final output
            self::render(self::$output);
        }
    }

    /**
     * Inserts global Kohana variables into the generated output and prints it.
     *
     * @param   string  final output that will displayed
     * @return  void
     */
    public static function render($output)
    {
        if(DEBUG == 1){
            if (self::config('core.render_stats') === TRUE)
            {
                // Fetch memory usage in MB
                $memory = function_exists('memory_get_usage') ? (memory_get_usage() / 1024 / 1024) : 0;
    
                // Fetch benchmark for page execution time
                $benchmark = Benchmark::get(SYSTEM_BENCHMARK.'_total_execution');
    
                // Replace the global template variables
                $output = str_replace(
                    array
                    (
                        '{execution_time}',
                        '{memory_usage}',
                        '{included_files}',
                    ),
                    array
                    (
                        $benchmark['time'],
                        number_format($memory, 2).'MB',
                        count(get_included_files()),
                    ),
                    $output
                );
            }
        }


        if ($level = self::config('core.output_compression') AND ini_get('output_handler') !== 'ob_gzhandler' AND (int) ini_get('zlib.output_compression') === 0)
        {
            if ($level < 1 OR $level > 9)
            {
                // Normalize the level to be an integer between 1 and 9. This
                // step must be done to prevent gzencode from triggering an error
                $level = max(1, min($level, 9));
            }

            if (stripos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
            {
                $compress = 'gzip';
            }
            elseif (stripos(@$_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== FALSE)
            {
                $compress = 'deflate';
            }
        }

        if (isset($compress) AND $level > 0)
        {
            switch ($compress)
            {
                case 'gzip':
                    // Compress output using gzip
                    $output = gzencode($output, $level);
                break;
                case 'deflate':
                    // Compress output using zlib (HTTP deflate)
                    $output = gzdeflate($output, $level);
                break;
            }

            // This header must be sent with compressed content to prevent
            // browser caches from breaking
            header('Vary: Accept-Encoding');

            // Send the content encoding header
            header('Content-Encoding: '.$compress);

            // Sending Content-Length in CGI can result in unexpected behavior
            if (stripos(PHP_SAPI, 'cgi') === FALSE)
            {
                header('Content-Length: '.strlen($output));
            }
        }
        echo $output;
    }
    

    /**
     * Returns the value of a key, defined by a 'dot-noted' string, from an array.
     *
     * @param   array   array to search
     * @param   string  dot-noted string: foo.bar.baz
     * @return  string  if the key is found
     * @return  void    if the key is not found
     */
    public static function key_string($array, $keys)
    {
        if (empty($array))
            return NULL;

        // Prepare for loop
        $keys = explode('.', $keys);

        do
        {
            // Get the next key
            $key = array_shift($keys);

            if (isset($array[$key]))
            {
                if (is_array($array[$key]) AND ! empty($keys))
                {
                    // Dig down to prepare the next loop
                    $array = $array[$key];
                }
                else
                {
                    // Requested key was found
                    return $array[$key];
                }
            }
            else
            {
                // Requested key is not set
                break;
            }
        }
        while ( ! empty($keys));

        return NULL;
    }

    /**
     * Sets values in an array by using a 'dot-noted' string.
     *
     * @param   array   array to set keys in (reference)
     * @param   string  dot-noted string: foo.bar.baz
     * @return  mixed   fill value for the key
     * @return  void
     */
    public static function key_string_set( & $array, $keys, $fill = NULL)
    {
        if (is_object($array) AND ($array instanceof ArrayObject))
        {
            // Copy the array
            $array_copy = $array->getArrayCopy();

            // Is an object
            $array_object = TRUE;
        }
        else
        {
            if ( ! is_array($array))
            {
                // Must always be an array
                $array = (array) $array;
            }

            // Copy is a reference to the array
            $array_copy =& $array;
        }

        if (empty($keys))
            return $array;

        // Create keys
        $keys = explode('.', $keys);

        // Create reference to the array
        $row =& $array_copy;

        for ($i = 0, $end = count($keys) - 1; $i <= $end; $i++)
        {
            // Get the current key
            $key = $keys[$i];

            if ( ! isset($row[$key]))
            {
                if (isset($keys[$i + 1]))
                {
                    // Make the value an array
                    $row[$key] = array();
                }
                else
                {
                    // Add the fill key
                    $row[$key] = $fill;
                }
            }
            elseif (isset($keys[$i + 1]))
            {
                // Make the value an array
                $row[$key] = (array) $row[$key];
            }

            // Go down a level, creating a new row reference
            $row =& $row[$key];
        }

        if (isset($array_object))
        {
            // Swap the array back in
            $array->exchangeArray($array_copy);
        }
    }


    /**
     * Retrieves current user agent information:
     * keys:  browser, version, platform, mobile, robot, referrer, languages, charsets
     * tests: is_browser, is_mobile, is_robot, accept_lang, accept_charset
     *
     * @param   string   key or test name
     * @param   string   used with "accept" tests: user_agent(accept_lang, en)
     * @return  array    languages and charsets
     * @return  string   all other keys
     * @return  boolean  all tests
     */
    public static function user_agent($key = 'agent', $compare = NULL)
    {
        static $info;

        // Return the raw string
        if ($key === 'agent')
            return self::$user_agent;

        if ($info === NULL)
        {
            // Parse the user agent and extract basic information
            $agents = self::config('user_agents');

            foreach ($agents as $type => $data)
            {
                foreach ($data as $agent => $name)
                {
                    if (stripos(self::$user_agent, $agent) !== FALSE)
                    {
                        if ($type === 'browser' AND preg_match('|'.preg_quote($agent).'[^0-9.]*+([0-9.][0-9.a-z]*)|i', self::$user_agent, $match))
                        {
                            // Set the browser version
                            $info['version'] = $match[1];
                        }

                        // Set the agent name
                        $info[$type] = $name;
                        break;
                    }
                }
            }
        }

        if (empty($info[$key]))
        {
            switch ($key)
            {
                case 'is_robot':
                case 'is_browser':
                case 'is_mobile':
                    // A boolean result
                    $return = ! empty($info[substr($key, 3)]);
                break;
                case 'languages':
                    $return = array();
                    if ( ! empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
                    {
                        if (preg_match_all('/[-a-z]{2,}/', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])), $matches))
                        {
                            // Found a result
                            $return = $matches[0];
                        }
                    }
                break;
                case 'charsets':
                    $return = array();
                    if ( ! empty($_SERVER['HTTP_ACCEPT_CHARSET']))
                    {
                        if (preg_match_all('/[-a-z0-9]{2,}/', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])), $matches))
                        {
                            // Found a result
                            $return = $matches[0];
                        }
                    }
                break;
                case 'referrer':
                    if ( ! empty($_SERVER['HTTP_REFERER']))
                    {
                        // Found a result
                        $return = trim($_SERVER['HTTP_REFERER']);
                    }
                break;
            }

            // Cache the return value
            isset($return) and $info[$key] = $return;
        }

        if ( ! empty($compare))
        {
            // The comparison must always be lowercase
            $compare = strtolower($compare);

            switch ($key)
            {
                case 'accept_lang':
                    // Check if the lange is accepted
                    return in_array($compare, self::user_agent('languages'));
                break;
                case 'accept_charset':
                    // Check if the charset is accepted
                    return in_array($compare, self::user_agent('charsets'));
                break;
                default:
                    // Invalid comparison
                    return FALSE;
                break;
            }
        }

        // Return the key, if set
        return isset($info[$key]) ? $info[$key] : NULL;
    }

    /**
     * Quick debugging of any variable. Any number of parameters can be set.
     *
     * @return  string
     */
    public static function debug()
    {
        if (func_num_args() === 0)
            return;

        // Get params
        $params = func_get_args();
        $output = array();

        foreach ($params as $var)
        {
            $output[] = '('.gettype($var).') '.var_export($var,TRUE).'';
        }
        if(RUNTIME_UI=='WEB'){
            return '<pre>'.implode("</pre>\n<pre>", $output).'</pre>';
        }else{
            return implode("\n", $output);
        }
    }

    /**
     * Get all include paths. APPPATH is the first path, followed by module
     * paths in the order they are configured, follow by the SYSPATH.
     *
     * @param   boolean  re-process the include paths
     * @return  array
     */
    public static function include_paths($process = FALSE)
    {
        if ($process === TRUE)
        {
            // Add APPPATH as the first path
            self::$include_paths = array(APP_PATH);

            // Add SYSPATH as the last path
            self::$include_paths[] = RUNTIME_PATH;
        }

        return self::$include_paths;
    }

    /**
     * Get a config item or group.
     *
     * @param   string   item name
     * @param   boolean  force a forward slash (/) at the end of the item
     * @param   boolean  is the item required?
     * @return  mixed
     */
    public static function config($key, $slash = FALSE, $required = TRUE)
    {
        if (self::$configuration === NULL)
        {
            // Load core configuration
            self::$configuration['core'] = self::config_load('core');

            // Re-parse the include paths
            self::include_paths(TRUE);
        }

        // Get the group name from the key
        $group = explode('.', $key, 2);
        $group = $group[0];

        if ( ! isset(self::$configuration[$group]))
        {
            // Load the configuration group
            self::$configuration[$group] = self::config_load($group, $required);
        }

        // Get the value of the key string
        $value = self::key_string(self::$configuration, $key);

        if ($slash === TRUE AND is_string($value) AND $value !== '')
        {
            // Force the value to end with "/"
            $value = rtrim($value, '/').'/';
        }

        return $value;
    }

    /**
     * Sets a configuration item, if allowed.
     *
     * @param   string   config key string
     * @param   string   config value
     * @return  boolean
     */
    public static function config_set($key, $value)
    {
        // Do this to make sure that the config array is already loaded
        self::config($key);

        if (substr($key, 0, 7) === 'routes.')
        {
            // Routes cannot contain sub keys due to possible dots in regex
            $keys = explode('.', $key, 2);
        }
        else
        {
            // Convert dot-noted key string to an array
            $keys = explode('.', $key);
        }

        // Used for recursion
        $conf =& self::$configuration;
        $last = count($keys) - 1;

        foreach ($keys as $i => $k)
        {
            if ($i === $last)
            {
                $conf[$k] = $value;
            }
            else
            {
                $conf =& $conf[$k];
            }
        }

        return TRUE;
    }

    /**
     * Load a config file.
     *
     * @param   string   config filename, without extension
     * @param   boolean  is the file required?
     * @return  array
     */
    public static function config_load($name, $required = TRUE)
    {
        if ($name === 'core')
        {
            // Load the application configuration file
            require APP_PATH.'config/config.php';

            if ( ! isset($config['app_code']))
            {
                // Invalid config file
                throw new LemonException('Your application configuration file is not valid.',500);
            }

            return $config;
        }

        if (isset(self::$internal_cache['configuration'][$name])){
            return self::$internal_cache['configuration'][$name];
        }
        // Load matching configs
        $configuration = array();

        if ($files = self::find_file('config', $name, $required))
        {
            foreach ($files as $file)
            {
                require $file;

                if (isset($config) AND is_array($config))
                {
                    // Merge in configuration
                    $configuration = array_merge($configuration, $config);
                }
            }
        }

        if ( ! isset(self::$write_cache['configuration']))
        {
            // Cache has changed
            self::$write_cache['configuration'] = TRUE;
        }

        return self::$internal_cache['configuration'][$name] = $configuration;
    }

    /**
     * Clears a config group from the cached configuration.
     *
     * @param   string  config group
     * @return  void
     */
    public static function config_clear($group)
    {
        // Remove the group from config
        unset(self::$configuration[$group], self::$internal_cache['configuration'][$group]);

        if ( ! isset(self::$write_cache['configuration']))
        {
            // Cache has changed
            self::$write_cache['configuration'] = TRUE;
        }
    }

    /**
     * Provides class auto-loading.
     *
     * @throws  Kohana_Exception
     * @param   string  name of class
     * @return  bool
     */
    public static function auto_load($class)
    {
        if (class_exists($class, FALSE)){
            return TRUE;
        }

        if (($suffix = strrpos($class, '_')) > 0)
        {
            // Find the class suffix
            $suffix = substr($class, $suffix + 1);
        }
        else
        {
            // No suffix
            $suffix = FALSE;
        }

        switch ($suffix){
            case 'Core':
                $type = 'library';
                $file = substr($class, 0, -5);
                break;
            case 'Library':
                $type = 'library';
                $file = substr($class, 0, -8);
                break;
            case 'Driver':
                $type = 'library/driver';
                $file = str_replace('_', '/', substr($class, 0, -7));
                break;
            case 'Service':
                $type = 'service';
                $file = substr($class, 0, -8);
                break;
            case 'Dao':
                $type = 'dao';
                $file = substr($class, 0, -4);
                break;
            case 'Po':
                $type = 'po';
                $file = substr($class, 0, -3);
                break;
            case 'Controller':
                $type = 'controller';
                $file = strtolower(substr($class, 0, -11));
                break;
            case 'Helper':
                $type = 'helper';
                $file = substr($class, 0, -7);
                break;
            case 'Util':
                $type = 'util';
                $file = substr($class, 0, -5);
                break;
            default:
                // This could be either a library or a helper, but libraries must
                // always be capitalized, so we check if the first character is
                // uppercase. If it is, we are loading a library, not a helper.
                $type = ($class[0] < 'a') ? 'library' : 'helper';
                $file = $class;
                break;
        }

        if ($filename = self::find_file($type, $file))
        {
            // Load the class
            require $filename;
        }
        else
        {
            // The class could not be found
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Find a resource file in a given directory. Files will be located according
     * to the order of the include paths. Configuration and i18n files will be
     * returned in reverse order.
     *
     * @throws  Kohana_Exception  if file is required and not found
     * @param   string   directory to search in
     * @param   string   filename to look for (without extension)
     * @param   boolean  file required
     * @param   string   file extension
     * @return  array    if the type is config, i18n or l10n
     * @return  string   if the file is found
     * @return  FALSE    if the file is not found
     */
    public static function find_file($directory, $filename, $required = FALSE, $ext = FALSE)
    {
        // NOTE: This test MUST be not be a strict comparison (===), or empty
        // extensions will be allowed!
        if ($ext == '')
        {
            // Use the default extension
            $ext = '.php';
        }
        else
        {
            // Add a period before the extension
            $ext = '.'.$ext;
        }

        // Search path
        $search = $directory.'/'.$filename.$ext;

        if (isset(self::$internal_cache['find_file_paths'][$search])){
            return self::$internal_cache['find_file_paths'][$search];
        }
        // Load include paths
        $paths = self::$include_paths;

        // Nothing found, yet
        $found = NULL;

        if ($directory === 'config')
        {
            // Search in reverse, for merging
            $paths = array_reverse($paths);

            foreach ($paths as $path)
            {
                if (is_file($path.$search))
                {
                    // A matching file has been found
                    $found[] = $path.$search;
                }
            }
        }
        else
        {
            foreach ($paths as $path)
            {
                if (is_file($path.$search))
                {
                    // A matching file has been found
                    $found = $path.$search;

                    // Stop searching
                    break;
                }
            }
        }

        if ($found === NULL)
        {
            if ($required === TRUE)
            {
                // If the file is required, throw an exception
                throw new LemonRuntimeException('core.resource_not_found:'.$filename,404);
            }
            else
            {
                // Nothing was found, return FALSE
                $found = FALSE;
            }
        }

        if ( ! isset(self::$write_cache['find_file_paths']))
        {
            // Write cache at shutdown
            self::$write_cache['find_file_paths'] = TRUE;
        }

        return self::$internal_cache['find_file_paths'][$search] = $found;
    }

    /**
     * Lists all files and directories in a resource path.
     *
     * @param   string   directory to search
     * @param   boolean  list all files to the maximum depth?
     * @param   string   full path to search (used for recursion, *never* set this manually)
     * @return  array    filenames and directories
     */
    public static function list_files($directory, $recursive = FALSE, $path = FALSE)
    {
        $files = array();

        if ($path === FALSE)
        {
            $paths = array_reverse(self::include_paths());

            foreach ($paths as $path)
            {
                // Recursively get and merge all files
                $files = array_merge($files, self::list_files($directory, $recursive, $path.$directory));
            }
        }
        else
        {
            $path = rtrim($path, '/').'/';

            if (is_readable($path))
            {
                $items = (array) glob($path.'*');

                if ( ! empty($items))
                {
                    foreach ($items as $index => $item)
                    {
                        $files[] = $item = str_replace('\\', '/', $item);

                        // Handle recursion
                        if (is_dir($item) AND $recursive == TRUE)
                        {
                            // Filename should only be the basename
                            $item = pathinfo($item, PATHINFO_BASENAME);

                            // Append sub-directory search
                            $files = array_merge($files, self::list_files($directory, TRUE, $path.$item));
                        }
                    }
                }
            }
        }

        return $files;
    }
    
} // End Lemon
