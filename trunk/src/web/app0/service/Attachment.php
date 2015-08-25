<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class Attachment_Service extends Default_Service {
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
    
    private $apiKey = NULL;
    
    private function getApiKey(){
        if($this->apiKey===NULL){
            $this->apiKey = Lemon::config('phprpc.local.'.$this->objectName.'.apiKey');
        }
        return $this->apiKey;
    }
    
    private function verifySign($args,$sign){
        return $sign== md5(json_encode($args).$this->getApiKey());
    }

    /**
     * 添加附件信息和附件文件
     * @param array $attachmentData
     * @param string $tmpFilePath
     */
    public function addAttachmentFile($attachmentData,$tmpFilePath){
        if(!is_file($tmpFilePath) || !is_readable($tmpFilePath)){
            //文件不可访问时抛出异常
            throw new MyRuntimeException(_('File not accessible'),400);
        }
        $tmpFileData = @file_get_contents($tmpFilePath);
        return $this->addAttachmentFileData($attachmentData,$tmpFileData);
    }
    
    /**
     * 添加附件信息和附件文件内容
     * @param array $attachmentData
     * @param string $tmpFileData
     */
    public function addAttachmentFileData($attachmentData,$tmpFileData){
        //TODO 设定需要的appMeta信息
        $appMeta = array();
        $requestData = $attachmentData;
        $attachmentId = $this->add($attachmentData);
        if(empty($attachmentId)){
            throw new MyRuntimeException(_('request attachment Id failed'),500);
        }
        $requestData['id'] = $attachmentId;
        $storeService = Store_Service::getInstance($this->servRouteInstance);
        $storeId = $storeService->storeFileData($tmpFileData,$appMeta);
        if(empty($storeId)){
            throw new MyRuntimeException(_('request resource Id failed'),500);
        }
        $requestData['storeId'] =$storeId;
        $this->update($requestData);
        return $attachmentId;
    }
    
    public function phprpc_addAttachmentFileData($attachmentData,$tmpFileData,$sign){
        if($this->verifySign(array($attachmentData),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->addAttachmentFileData($attachmentData,$tmpFileData);
    }
    
    /**
     * 获取附件信息内容根据附件Id
     * @param int $attachmentId
     */
    public function getAttachmentDataById($attachmentId){
        return $this->get($attachmentId);
    }
    
    public function phprpc_getAttachmentDataById($attachmentId,$sign){
        if($this->verifySign(array($attachmentId),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->getAttachmentDataById($attachmentId);
    }
    
    /**
     * 获取附件存储内容根据附件Id
     * @param int $attachmentId
     */
    public function getStoreDataByAttachmentId($attachmentId){
        $attachmentData = $this->get($attachmentId);
        if($attachmentData['storeId']!=0){
            $storeService = Store_Service::getInstance($this->servRouteInstance);
            return $storeService->getStoreDataById($attachmentData['storeId']);
        }else{
            throw new MyRuntimeException(_('data not initialized yet.'),500);
        }
    }
    
    public function phprpc_getStoreDataByAttachmentId($attachmentId,$sign){
        if($this->verifySign(array($attachmentId),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->getStoreDataByAttachmentId($attachmentId);
    }
    
    /**
     * 获取附件存储内容根据存储Id
     * @param int $storeId
     */
    public function getStoreDataByStoreId($storeId){
        $storeService = Store_Service::getInstance($this->servRouteInstance);
        return $storeService->getStoreDataById($storeId);
    }
    
    public function phprpc_getStoreDataByStoreId($storeId,$sign){
        if($this->verifySign(array($storeId),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->getStoreDataByStoreId($storeId);
    }
    
    /**
     * 获取附件存储信息根据存储Id
     * @param int $storeId
     */
    public function getStoreInfoByStoreId($storeId){
        $storeService = Store_Service::getInstance($this->servRouteInstance);
        return $storeService->get($storeId);
    }
    
    public function phprpc_getStoreInfoByStoreId($storeId,$sign){
        if($this->verifySign(array($storeId),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->getStoreInfoByStoreId($storeId);
    }

    public function removeAttachmentDataByAttachmentId($attachmentId){
        $attachmentData = $this->get($attachmentId);
        if($attachmentData['storeId']!=0){
            $storeService = Store_Service::getInstance($this->servRouteInstance);
            $storeService->removeStoreDataById($attachmentData['storeId']);
        }
        return $this->remove($attachmentId);
    }
    public function phprpc_removeAttachmentDataByAttachmentId($attachmentId,$sign){
        if($this->verifySign(array($attachmentId),$sign)==FALSE){
            throw new MyRuntimeException(_('sign verify failed'));
        }
        return $this->removeAttachmentDataByAttachmentId($attachmentId);
    }
}