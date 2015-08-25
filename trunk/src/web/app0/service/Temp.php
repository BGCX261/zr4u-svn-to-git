<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class Temp_Service extends Default_Service {
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
    //** 业务逻辑代码请写在此行之后　**//

    //FIXME 根据本类属性对这部分应用函数做一定业务逻辑上的调整
//    public function get($id){
//        // Custom 
//        return $this->read(array('id'=>$id));
//    }
//    public function set($id,$data){
//        // Custom 
//        $requestData = $data;
//        $requestData['id'] = $id;
//        return $this->update($requestData);
//    }
//    public function add($data){
//        // Custom 
//        return $this->create($data);
//    }
//    public function remove($id){
//        // Custom 
//        return $this->delete(array('id'=>$id));
//    }
//    public function index($queryStruct=array()){
//        // Custom 
//        return $this->queryAssoc($queryStruct);
//    }
//    public function count($queryStruct=array()){
//        // Custom 
//        return $this->queryCount($queryStruct);
//    }

    //:: 本类定制的业务逻辑 :://
    //TODO 根据业务逻辑需求提供对应的函数调用

}
