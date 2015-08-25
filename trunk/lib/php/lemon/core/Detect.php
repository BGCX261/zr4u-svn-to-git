<?php
/**
 * 系统环境检测代码
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

// 定义运行时环境 -[暂时取消在内部检测/可以外部define或者外部检测]-
//!defined('RUNTIME_UI') && define('RUNTIME_UI',  php_sapi_name() == 'cli'?'CLI':'WEB');
// 设置换行 -[暂时取消在内部检测/可以外部define或者外部检测]-
//!defined('RUNTIME_EOL') && define('RUNTIME_EOL',  RUNTIME_UI=='CLI'? "\n" : '<br />');

// Test of lemon is running in Windows
define('LEMON_IS_WIN', strtoupper(substr(PHP_OS,0,3))=='WIN');

// 参考kohana2
if ( ! preg_match('/^.$/u', 'ñ'))
{
    trigger_error
    (
        '<a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support. '.
        'See <a href="http://php.net/manual/reference.pcre.pattern.modifiers.php">PCRE Pattern Modifiers</a> '.
        'for more information. This application cannot be run without UTF-8 support.',
        E_USER_ERROR
    );
}

if ( ! extension_loaded('iconv'))
{
    trigger_error
    (
        'The <a href="http://php.net/iconv">iconv</a> extension is not loaded. '.
        'Without iconv, strings cannot be properly translated to UTF-8 from user input. '.
        'This application cannot be run without UTF-8 support.',
        E_USER_ERROR
    );
}

if (extension_loaded('mbstring') AND (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING))
{
    trigger_error
    (
        'The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP\'s native string functions. '.
        'Disable this by setting mbstring.func_overload to 0, 1, 4 or 5 in php.ini or a .htaccess file.'.
        'This application cannot be run without UTF-8 support.',
        E_USER_ERROR
    );
}

// Check PCRE support for Unicode properties such as \p and \X.
$ER = error_reporting(0);
define('PCRE_UNICODE_PROPERTIES', (bool) preg_match('/^\pL$/u', 'ñ'));
error_reporting($ER);

// SERVER_UTF8 ? use mb_* functions : use non-native functions
if (extension_loaded('mbstring'))
{
    mb_internal_encoding('UTF-8');
    define('SERVER_UTF8', TRUE);
}
else
{
    define('SERVER_UTF8', FALSE);
}
