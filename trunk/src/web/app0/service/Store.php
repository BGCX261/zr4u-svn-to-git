<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class Store_Service extends Default_Service {
//    private static $instance = NULL;
//    // 获取单态实例
//    public static function getInstance($servRouteInstance = NULL){
//        if(self::$instance === null){
//            $classname = __CLASS__;
//            self::$instance = new $classname($servRouteInstance);
//        }
//        return self::$instance;
//    }

    protected $seqService = NULL;
    protected function getSeqService(){
        if(is_null($this->seqService)){
            $this->seqService =  Seq_Service::getInstance($this->getServRouteInstance());
        }
        return $this->seqService;
    }
    protected function setSeqService($seqService){
        $this->seqService = $seqService;
    }

    /**
     * 创建数据
     * @param array $request_data
     * @return int
     * @throws MyRuntimeException
     */
    public function create($requestData, $dbInstance=NULL){
        try {
            //TODO 先申请资源ID流程再请求DB实例
            // TODO 根据$requestData组织特征向量做dbInstance;
            if(!array_key_exists('id',$requestData)){
                // 先去seqservice 申请 id
                $seqService = $this->getSeqService();
                $seqId = $seqService->nextSeq($this->objectName);
                $requestData['id'] = $seqId;
                
            }
            is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,array('id'=>$requestData['id']))->getInstance();
            $daoInstance = DaoDb::factory($this->objectName,$dbInstance);
            $data = $daoInstance->asArray();
            foreach ($requestData as $key=>$val) {
                array_key_exists($key,$data) && $daoInstance->$key = $val;
            }
            $daoInstance->save();
            if($daoInstance->saved !== TRUE){
                throw new MyRuntimeException(_('internal error'),500);
            }
            //TODO 逻辑与数据分离：状态与数据分离
            return $daoInstance->id;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    public function get($id){
        $cacheInstance = $this->servRouteInstance->getMemInstance($this->objectName,array('id'=>$id,))->getInstance();
        $routeKey = $this->objectName.'_'.$id;
        $cacheObject = $cacheInstance->get($routeKey);
        if(empty($cacheObject)){
            $cacheObject = $this->read(array('id'=>$id));
            if(!empty($cacheObject)){
                $cacheInstance->set($routeKey,$cacheObject);
            }
        }
        return $cacheObject;
    }
    public function set($id,$data){
        $requestData = $data;
        $requestData['id'] = $id;
        $this->update($requestData);
        
        $cacheInstance = $this->servRouteInstance->getMemInstance($this->objectName,array('id'=>$id,))->getInstance();
        $routeKey = $this->objectName.'_'.$id;
        // 清理单体cache
        $cacheInstance->delete($routeKey,0);
    }
    public function remove($id){
        $this->delete(array('id'=>$id));
        $cacheInstance = $this->servRouteInstance->getMemInstance($this->objectName,array('id'=>$id,))->getInstance();
        $routeKey = $this->objectName.'_'.$id;
        // 清理单体cache
        $cacheInstance->delete($routeKey,0);
    }
    
    //:: 本类定制的业务逻辑 :://
    //TODO 根据业务逻辑需求提供对应的函数调用

    const STORE_TYPE_ENTITY = 1; // 实体存储（存储在表字段内）
    const STORE_TYPE_FS = 2; // 存储在FS系统内（本地磁盘或者NFS）
    const STORE_TYPE_TT = 3; // 存储在网络KVDB数据库里（如TT,MemcacheDB等）
    const STORE_TYPE_MEM = 4; // 存储在网络兼容MEMCACHE协议的KVDB数据库里（如TT,MemcacheDB等,使用MEMCACHE协议）
    const STORE_TYPE_WEBDAV = 5; // 存储在网络路径里（如WebDAV,SVN等）
    const STORE_TYPE_PHPRPC = 6; // 存储到网络的PHPRPC协议远程服务端
    
    private $phprpcApiKey = NULL;
    
    private function getPhprpcApiKey(){
        if($this->phprpcApiKey===NULL){
            $this->phprpcApiKey = Lemon::config('store.remote.phprpcApiKey');
        }
        return $this->phprpcApiKey;
    }
    
    /**
     * 存储文件
     * @param $filePath
     * @param $appMeta
     */
    public function storeFile($filePath,$appMeta=NULL){
        
        if(!is_file($filePath) || !is_readable($filePath)){
            //文件不可访问时抛出异常
            throw new MyRuntimeException(_('file not accessible.'),400);
        }
        $fileData = @file_get_contents($filePath);
        return $this->storeFileData($fileData, $appMeta);
    }
    
    /**
     * 存储文件内容
     * @param $fileData
     * @param $appMeta
     */
    public function storeFileData($fileData,$appMeta=NULL){
        //TODO 根据appMeta路由本地资源申请的地址
        //先申请id
        $requestData = array('storeType'=>0);
        $storeId = $this->add($requestData);
        if(empty($storeId)){
            throw new MyRuntimeException(_('request resource Id failed'),500);
        }
        //TODO 加入appMeta的指定逻辑的解析工作
        $fileMeta = $appMeta;
        if(!empty($fileMeta) && is_array($fileMeta)){
            $storeType = isset($fileMeta['storeType'])?$fileMeta['storeType']:Lemon::config('store.defaultType');
            $fileMeta['storeType'] = $storeType;
            $storeLength = isset($fileMeta['storeLength'])?$fileMeta['storeLength']:strlen($fileData);
            $fileMeta['storeLength'] = $storeLength;
        }else{
            $storeType = Lemon::config('store.defaultType');
            $storeLength = strlen($fileData);
            $fileMeta = array(
                'storeType'=>$storeType,
                'storeLength'=>$storeLength,
            );
        }
        $fileMeta['id'] = $storeId;
        $fileMeta['objectName'] = $this->objectName.'Data';
        //预备下一步存储流程结束后的更新数据
        $requestData = array(
            'id'=>$storeId,
            'storeType'=>$storeType,
            'storeLength'=>$storeLength,
            'storeMeta'=>!empty($fileMeta)?json_encode($fileMeta):'',
        );
        
        //FIXME 目前只支持本地FS存储故此处暂时使用嵌入的方式解决，后面应该写成驱动形式。
        switch($storeType){
            case self::STORE_TYPE_FS:
                    $fileKey = md5(uniqid(rand(), true));
                    $requestData['getUri'] = $fileKey;
                    $requestData['setUri'] = $fileKey;
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($this->objectName.'Data',array('id'=>$requestData['id']))->getInstance();
                    $saveOk = $fsInstCurrent->putFileData($requestData['setUri'],$fileData);
                    if($saveOk==FALSE){
                        throw new MyRuntimeException(_('store failed'),500);
                    }
                    break;
            case self::STORE_TYPE_TT:
                    $fileKey = md5(uniqid(rand(), true));
                    $requestData['getUri'] = $fileKey;
                    $requestData['setUri'] = $fileKey;
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($this->objectName.'Data',array('id'=>$requestData['id']))->getInstance();
                    $ttInstCurrent->put($requestData['setUri'],$fileData);
//                    $saveOk = $ttInstCurrent->put($requestData['setUri'],$fileData);
//                    if($saveOk==FALSE){
//                        throw new MyRuntimeException(_('store failed'),500);
//                    }
                    break;
            case self::STORE_TYPE_MEM:
                    $fileKey = md5(uniqid(rand(), true));
                    $requestData['getUri'] = $fileKey;
                    $requestData['setUri'] = $fileKey;
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($this->objectName.'Data',array('id'=>$requestData['id']))->getInstance();
                    $memInstCurrent->set($requestData['setUri'],$fileData);
//                    $saveOk = $ttInstCurrent->put($requestData['setUri'],$fileData);
//                    if($saveOk==FALSE){
//                        throw new MyRuntimeException(_('store failed'),500);
//                    }
                    break;
            case self::STORE_TYPE_PHPRPC:
                    $fileKey = md5(uniqid(rand(), true));
                    $requestData['getUri'] = $fileKey;
                    $requestData['setUri'] = $fileKey;
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $phprpcInstCurrent = $servRouteInstance->getPhprpcInstance($this->objectName.'Data',array('id'=>$requestData['id']))->getInstance();
                    $fileMeta['storeType'] = Lemon::config('store.apiDefaultType');
                    $storeMeta = !empty($fileMeta)?json_encode($fileMeta):'';
                    $sign = md5($requestData['setUri'].$storeMeta.$this->getPhprpcApiKey());
                    $phprpcInstCurrent->set($requestData['setUri'],$fileData,$storeMeta,$sign);
                break;
            case self::STORE_TYPE_ENTITY:
            default:
                    throw new MyRuntimeException(_('store type not supportted right now.'),500);
                    $requestData['getUri'] = $storeId;
                    $requestData['setUri'] = $storeId;
                    $requestData['storeContent'] = $fileData;
                    break;
        }
        $this->set($requestData['id'],$requestData);
        return $storeId;
    }

    /**
     * 读取存储数据内容
     * @param $storeId
     */
    public function getStoreDataById($storeId){
        $storeInfo = $this->get($storeId);
        switch($storeInfo['storeType']){
            case self::STORE_TYPE_ENTITY:
                    return $storeInfo['storeContent'];
                break;
            case self::STORE_TYPE_FS:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    return $fsInstCurrent->getFileData($storeInfo['getUri']);
                break;
            case self::STORE_TYPE_TT:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    return $ttInstCurrent->get($storeInfo['getUri']);
                break;
            case self::STORE_TYPE_MEM:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    return $memInstCurrent->get($storeInfo['getUri']);
                break;
            case self::STORE_TYPE_PHPRPC:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $phprpcInstCurrent = $servRouteInstance->getPhprpcInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    $storeMeta = $storeInfo['storeMeta'];
                    $sign = md5($storeInfo['getUri'].$storeMeta.$this->getPhprpcApiKey());
                    return $phprpcInstCurrent->get($storeInfo['getUri'],$storeMeta,$sign);
                break;
            case 0:
                    throw new MyRuntimeException(_('data not initialized yet.'),500);
                break;
            default:
                throw new MyRuntimeException(_('store type not supportted right now.'),500);
        }
    }
    
    public function removeStoreDataById($storeId){
        $storeInfo = $this->get($storeId);
        switch($storeInfo['storeType']){
            case self::STORE_TYPE_FS:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $fsInstCurrent = $servRouteInstance->getFsInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    $fsInstCurrent->delete($storeInfo['setUri']);
                break;
            case self::STORE_TYPE_TT:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $ttInstCurrent = $servRouteInstance->getTtInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    $ttInstCurrent->out($storeInfo['setUri']);
                break;
            case self::STORE_TYPE_MEM:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据 store信息的fileMeta去调用不同的存储逻辑实例
                    $memInstCurrent = $servRouteInstance->getMemInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    $memInstCurrent->delete($storeInfo['setUri']);
                break;
            case self::STORE_TYPE_PHPRPC:
                    // 调用路由实例
                    $servRouteInstance = $this->getServRouteInstance();
                    //TODO 根据fileMeta去调用不同的存储逻辑实例
                    $phprpcInstCurrent = $servRouteInstance->getPhprpcInstance($this->objectName.'Data',array('id'=>$storeId))->getInstance();
                    $storeMeta = $storeInfo['storeMeta'];
                    $sign = md5($storeInfo['getUri'].$storeMeta.$this->getPhprpcApiKey());
                    $phprpcInstCurrent->delete($storeInfo['getUri'],$storeMeta,$sign);
                break;
            case self::STORE_TYPE_ENTITY:
            case 0:
            default:
                break;
        }
        return $this->remove($storeId);
    }
}