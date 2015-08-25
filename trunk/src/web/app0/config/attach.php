<?php

/*
 * 系统附件相关配置
 */

/* 附件默认缓存的时间 */
$config['httpCacheTimeDefault'] = NULL;

/* 系统附件上传配置 */
$config['appAttach']=array();
$config['appAttach']['allowTypes'] = array(
    'gif','png','jpg',
    //'jpeg','bmp','tif','tiff',
//    'swf',
//    'doc','docx','ppt','pps','txt','rtf','pdf',
//    'zip','rar','7z','tgz','gz','tar'
);
$config['appAttach']['fileCountLimit'] = 5; // 5 attachement file
$config['appAttach']['fileSizePreLimit'] = 3145728; // 3*1048576 (3M)
$config['appAttach']['fileSizeTotalLimit'] = 5242880; // 5*1048576 (5M)
