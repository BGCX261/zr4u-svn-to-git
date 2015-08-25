<?php
/**
 * 应用系统入口文件
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id: index.php 76 2010-04-18 05:39:31Z axiong $
 */



// 调试状态
!defined ( 'DEBUG' ) && define ( 'DEBUG', 1);

/**
 * project root path
 */
if(DEBUG==TRUE){
//    // only for development under windows workstation
//    $project_root = '';
//    $is_win = strtoupper(substr(PHP_OS,0,3))=='WIN';
//    $is_win && $project_root .= 'D:';
//    $project_root .= '/data0/apps/zr4u';
    $project_root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
}else{
    /**
     * project root path
     */
    $project_root = '/data0/apps/zr4u';
}

if(DEBUG==1){
    ini_set('xdebug.show_exception_trace', 0);
}

//定义应用代号
!defined ( 'APP_CODE' ) && define ( 'APP_CODE', 'app0');

// 系统初始化
!defined ( 'SYS_INIT' ) && define ( 'SYS_INIT', 1);

// 定义项目文件路径
!defined('PROJECT_ROOT') && define('PROJECT_ROOT', str_replace('\\', '/', realpath($project_root)).'/');
// 定义运行环境文件路径
!defined('RUNTIME_PATH') && define('RUNTIME_PATH', PROJECT_ROOT.'lib/php/lemon/');

// 定义应用文件路径
!defined('APP_PATH') && define('APP_PATH', PROJECT_ROOT.'src/web/'.APP_CODE.'/');

$lemon_pathinfo = pathinfo(__FILE__);

!defined('DOC_ROOT') && define('DOC_ROOT', $lemon_pathinfo['dirname'].'/');
!defined('LEMON') && define('LEMON',  $lemon_pathinfo['basename']);
// If the front controller is a symlink, change to the real docroot
is_link(LEMON) and chdir(dirname(realpath(__FILE__)));

// 引入应用初始化文件
require_once RUNTIME_PATH.'core/Bootstrap.php';

