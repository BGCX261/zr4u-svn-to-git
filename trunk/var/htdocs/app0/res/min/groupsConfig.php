<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 **/

return array(
    // 'js' => array('//js/file1.js', '//js/file2.js'),
    // 'css' => array('//css/file1.css', '//css/file2.css'),
    'csscommon' => array(
            '//res/css/grids-min.css',
            '//res/css/jquery/themes/cupertino/jquery-ui-1.8.custom.css',
            '//res/css/skeleton.css',
            '//res/css/main.css',
        ),
    'jscommon' => array(
            '//res/js/jquery/jquery-1.4.2.src.js',
            '//res/js/jquery/plugins/jquery-ui-1.8.custom.src.js',
            '//res/js/jquery/plugins/jquery.cookie.src.js',
            '//res/js/common/common.src.js',
        ),
    // custom source example
    /*'js2' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => create_function('$a', 'return $a;')
        ))
    ),//*/

    /*'js3' => array(
        dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
        // do NOT process this file
        new Minify_Source(array(
            'filepath' => dirname(__FILE__) . '/../min_unit_tests/_test_files/js/before.js',
            'minifier' => array('Minify_Packer', 'minify')
        ))
    ),//*/
);