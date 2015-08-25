<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class StoreData_Service {
    private $apiKey = NULL;
    //路由实例管理实例
    private $servRouteInstance = NULL;
    private static $instance = NULL;

    /**
     * 单实例方法
     * @param $id
     */
    // 获取单态实例
    public static function getInstance($apiKey=NULL,$servRouteInstance=NULL){
        $className = get_called_class();
        if(self::$instance===NULL){
            self::$instance = new $className($apiKey,$servRouteInstance);
        }
        return self::$instance;
    }

    private function __construct($apiKey=NULL,$servRouteInstance=NULL){
        if(!empty($apiKey)){
            $this->apiKey = $apiKey;
        }else{
            $this->apiKey = Lemon::config('store.local.phprpcApiKey');
        }
        if(!empty($servRouteInstance)){
            $this->servRouteInstance = $servRouteInstance;
        }else{
            $this->servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this;
    }

    const STORE_TYPE_ENTITY = 1; // 实体存储（存储在表字段内）
    const STORE_TYPE_FS = 2; // 存储在FS系统内（本地磁盘或者NFS）
    const STORE_TYPE_TT = 3; // 存储在网络KVDB数据库里（如TT,MemcacheDB等）
    const STORE_TYPE_MEM = 4; // 存储在网络兼容MEMCACHE协议的KVDB数据库里（如TT,MemcacheDB等,使用MEMCACHE协议）
    const STORE_TYPE_WEBDAV = 5; // 存储在网络路径里（如WebDAV,SVN等）
    const STORE_TYPE_PHPRPC = 6; // 存储到网络的PHPRPC协议远程服务端


    /**
     * 获取路由实例管理实例
     */
    private function getServRouteInstance(){
        if($this->servRouteInstance===NULL){
            $this->servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this->servRouteInstance;
    }
    
    private function getApiKey(){
        if($this->apiKey===NULL){
            $this->apiKey = Lemon::config('store.local.phprpcApiKey');
        }
        return $this->apiKey;
    }
    
    private function verifySign($fileKey,$meta,$sign){
        return $sign== md5($fileKey.$meta.$this->apiKey);
    }
    public function set($fileKey,$fileData,$meta,$sign){
        if($this->verifySign($fileKey,$meta,$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        $metaStruct = array();
        !empty($meta) && $metaStruct = json_decode($meta,TRUE);
        $objectName = array_key_exists('objectName',$metaStruct) ? $metaStruct['objectName'] : 'StoreData' ;
        $routeSet = array_key_exists('id',$metaStruct) ? array('id'=>$metaStruct['id']):array();
        // 请求的存储类型
        $storeType = isset($metaStruct['storeType'])?$metaStruct['storeType']:Lemon::config('store.apiDefaultType');
        $storeType == self::STORE_TYPE_PHPRPC && $storeType= Lemon::config('store.apiDefaultType');
        // 请求的存储数据长度
        $storeLength = isset($metaStruct['storeLength'])?$metaStruct['storeLength']:strlen($fileData);
        switch($storeType){
            case self::STORE_TYPE_FS:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据$metaStruct去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($objectName,$routeSet)->getInstance();
                    $saveOk = $fsInstCurrent->putFileData($fileKey,$fileData);
                    if($saveOk==FALSE){
                        throw new MyRuntimeException(_('store failed'),500);
                    }
                break;
            case self::STORE_TYPE_TT:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($objectName,$routeSet)->getInstance();
                    $ttInstCurrent->put($fileKey,$fileData);
//                    $saveOk = $ttInstCurrent->put($fileKey,$fileData); 
//                    if($saveOk==FALSE){
//                        throw new MyRuntimeException(_('store failed'),500);
//                    }
                break;
            case self::STORE_TYPE_MEM:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($objectName,$routeSet)->getInstance();
                    $memInstCurrent->set($fileKey,$fileData);
//                    $saveOk = $memInstCurrent->set($fileKey,$fileData); 
//                    if($saveOk==FALSE){
//                        throw new MyRuntimeException(_('store failed'),500);
//                    }
                break;
            default:
                    throw new MyRuntimeException(_('unsupported store type'),500);
                break;
        }
    }

    public function get($fileKey,$meta,$sign){
        if($this->verifySign($fileKey,$meta,$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        $metaStruct = array();
        !empty($meta) && $metaStruct = json_decode($meta,TRUE);
        $objectName = array_key_exists('objectName',$metaStruct) ? $metaStruct['objectName'] : 'StoreData' ;
        $routeSet = array_key_exists('id',$metaStruct) ? array('id'=>$metaStruct['id']):array();
        // 请求的存储类型
        $storeType = isset($metaStruct['storeType'])?$metaStruct['storeType']:Lemon::config('store.apiDefaultType');
        $storeType == self::STORE_TYPE_PHPRPC && $storeType= Lemon::config('store.apiDefaultType');
        // 请求的存储数据长度
        $storeLength = isset($metaStruct['storeLength'])?$metaStruct['storeLength']:0;
        $refArray = isset($metaStruct['refArray'])?$metaStruct['refArray']:array();
        // 应用对象类型
        $refType = (!empty($refArray) && isset($refArray[0]['refPart']))?$refArray[0]['refPart']:'default';
        // 应用对象id
        $refId = (!empty($refArray) && isset($refArray[0]['refId']))?$refArray[0]['refId']:0;
        
        switch($storeType){
            case self::STORE_TYPE_FS:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据$metaStruct去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($objectName,$routeSet)->getInstance();
                    return $fsInstCurrent->getFileData($fileKey);
                break;
            case self::STORE_TYPE_TT:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($objectName,$routeSet)->getInstance();
                    return $ttInstCurrent->get($fileKey);
                break;
            case self::STORE_TYPE_MEM:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($objectName,$routeSet)->getInstance();
                    return $memInstCurrent->get($fileKey);
                break;
            default:
                    throw new MyRuntimeException(_('unsupported store type'),500);
                break;
        }
    }

    public function delete($fileKey,$meta,$sign){
        if($this->verifySign($fileKey,$meta,$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        $metaStruct = array();
        !empty($meta) && $metaStruct = json_decode($meta,TRUE);
        $objectName = array_key_exists('objectName',$metaStruct) ? $metaStruct['objectName'] : 'StoreData' ;
        $routeSet = array_key_exists('id',$metaStruct) ? array('id'=>$metaStruct['id']):array();
        // 请求的存储类型
        $storeType = isset($metaStruct['storeType'])?$metaStruct['storeType']:Lemon::config('store.apiDefaultType');
        $storeType == self::STORE_TYPE_PHPRPC && $storeType= Lemon::config('store.apiDefaultType');
        // 请求的存储数据长度
        $storeLength = isset($metaStruct['storeLength'])?$metaStruct['storeLength']:0;
        $refArray = isset($metaStruct['refArray'])?$metaStruct['refArray']:array();
        // 应用对象类型
        $refType = (!empty($refArray) && isset($refArray[0]['refPart']))?$refArray[0]['refPart']:'default';
        // 应用对象id
        $refId = (!empty($refArray) && isset($refArray[0]['refId']))?$refArray[0]['refId']:0;
        
        switch($storeType){
            case self::STORE_TYPE_FS:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据$metaStruct去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($objectName,$routeSet)->getInstance();
                    $fsInstCurrent->delete($fileKey);
                break;
            case self::STORE_TYPE_TT:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($objectName,$routeSet)->getInstance();
                    $ttInstCurrent->delete($fileKey);
                break;
            case self::STORE_TYPE_MEM:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($objectName,$routeSet)->getInstance();
                    $memInstCurrent->delete($fileKey);
                break;
            default:
                    throw new MyRuntimeException(_('unsupported store type'),500);
                break;
        }
    }
}