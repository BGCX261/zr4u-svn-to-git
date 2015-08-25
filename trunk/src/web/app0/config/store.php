<?php
/* 附件存储相关 */
/*
    const STORE_TYPE_ENTITY = 1; // 实体存储（存储在表字段内 disabled）
    const STORE_TYPE_FS = 2; // 存储在FS系统内（本地磁盘或者NFS）
    const STORE_TYPE_TT = 3; // 存储在网络KVDB数据库里（如TT,MemcacheDB等）
    const STORE_TYPE_MEM = 4; // 存储在网络兼容MEMCACHE协议的KVDB数据库里（如TT,MemcacheDB等,使用MEMCACHE协议）
    const STORE_TYPE_WEBDAV = 5; // 存储在网络路径里（如WebDAV,SVN等）
    const STORE_TYPE_PHPRPC = 6; // 存储到网络的PHPRPC协议远程服务端
 */
$config['defaultType'] = 3;
$config['apiDefaultType'] = 3;
$config['local'] = array(
    'phprpcApiKey' => '20b64c03b4012ce8bf4e9bec49539386',
);
$config['remote'] = array(
    'phprpcApiKey' => '20b64c03b4012ce8bf4e9bec49539386',
);
