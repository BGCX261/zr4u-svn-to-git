<?php
/**
 * 系统BootStrap启动预备程序 定义路径，修正环境变量，初始化通用全局变量，设置计时器，启动计时器
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

// 定义运行时环境是
!defined('RUNTIME_UI') && define('RUNTIME_UI',  php_sapi_name() == 'cli'?'CLI':'WEB');
// 设置换行
!defined('RUNTIME_EOL') && define('RUNTIME_EOL',  RUNTIME_UI=='CLI'? "\n" : '<br />');

// 自动载入
!defined ( 'AUTOLOAD' ) && define ( 'AUTOLOAD', 1);
// 自动运行
//!defined ( 'AUTORUN' ) && define ( 'AUTORUN', constant('RUNTIME_UI')=='CLI'?0:1);

// Kohana benchmarks are prefixed to prevent collisions
if(DEBUG == 1){
    define('SYSTEM_BENCHMARK', 'system_benchmark');
    // Load benchmarking support
    require RUNTIME_PATH.'core/Benchmark.php';
    // Start total_execution
    Benchmark::start(SYSTEM_BENCHMARK.'_total_execution');
    // Start runtime_loading
    Benchmark::start(SYSTEM_BENCHMARK.'_runtime_loading');
}

// 是否需要执行环境检测
if(!defined('ENV_DETECT') || constant('ENV_DETECT')!=0){
    /**
     * 如果不执行自动检测则需要define:
     * -[暂时取消在内部检测/可以外部define或者外部检测]- RUNTIME_UI = CLI : WEB
     * -[暂时取消在内部检测/可以外部define或者外部检测]- RUNTIME_EOL = \n : <br />
     * LEMON_IS_WIN = TRUE : FALSE
     * PCRE_UNICODE_PROPERTIES = TRUE : FALSE
     * SERVER_UTF8 = TRUE : FALSE
     */
    require RUNTIME_PATH.'core/Detect.php';
}
/* 引入utf8库 */
require RUNTIME_PATH.'core/utf8.php';
/* 执行清理 */
require RUNTIME_PATH.'core/Cleanup.php';
/* 引入事件系统 */
require RUNTIME_PATH.'core/Event.php';
/* 引入Lemon */
require RUNTIME_PATH.'core/Lemon.php';

// Prepare the environment
Lemon::setup();

!defined('APP_INIT') && is_file(APP_PATH.'core/Init.php') && include(APP_PATH.'core/Init.php');
!defined('APP_INIT') && define('APP_INIT',1);

// End runtime_loading
DEBUG == 1 && Benchmark::stop(SYSTEM_BENCHMARK.'_runtime_loading');


if(RUNTIME_UI=='WEB'){
    // Start system_initialization
    DEBUG == 1 && Benchmark::start(SYSTEM_BENCHMARK.'_system_initialization');
    // Prepare the system
    Event::run('system.ready');
    
    // Determine routing
    Event::run('system.routing');
    
    // End system_initialization
    DEBUG == 1 && Benchmark::stop(SYSTEM_BENCHMARK.'_system_initialization');
    
    // Make the magic happen!
    Event::run('system.execute');
    
    // Clean up and exit
    Event::run('system.shutdown');
}
