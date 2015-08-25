<?php
/**
 * 系统变量、环境变量清理代码
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */


if(RUNTIME_UI=='WEB'){
    // Convert all global variables to UTF-8.
    $_GET    = utf8::clean($_GET);
    $_POST   = utf8::clean($_POST);
    $_COOKIE = utf8::clean($_COOKIE);
    $_SERVER = utf8::clean($_SERVER);
}

if (RUNTIME_UI == 'CLI')
{
    // Convert command line arguments
    $_SERVER['argv'] = utf8::clean($_SERVER['argv']);
}
