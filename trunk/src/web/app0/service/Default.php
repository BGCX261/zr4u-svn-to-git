<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

abstract class Default_Service {
    //对象名称(表名)
    protected $objectName = '';    // SameDao
    //**  以下为共用方法和变量，请勿修改除非您知道自己在做什么，业务逻辑包括业务变量请加到类最后处理 **//
    protected $servRouteInstance = NULL;
    protected $daoInstance = NULL;
    protected static $instances = array();
    // 获取单态实例 5.2版本无晚静态绑定，需要屏蔽此处并在各个子类中实现此方法，同时不能使用static关键字和get_called_class方法，需要用self和__CLASS___替换。
    public static function getInstance($servRouteInstance = NULL){
        $className = get_called_class();
        $classObjectName = substr($className, 0, -8);
        if(!isset(static::$instances[$classObjectName])){
            
            static::$instances[$classObjectName] = new $className($servRouteInstance);
        }
        return static::$instances[$classObjectName];
    }
    
//    public static function factory($servRouteInstance = NULL)
//    {
//        // Set class name
//        $className = get_called_class();
//        return new $className($servRouteInstance);
//    }
    

    // FIXME 兼容旧版5.2写法 用 protected 理论上 5.3 应该用 private
    private function __construct($servRouteInstance = NULL){
        $this->objectName   = substr(get_class($this), 0, -8);// 0 -8 : _Service
        if(is_null($servRouteInstance)){
            $this->servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }else{
            $this->servRouteInstance = $servRouteInstance;
        }
        return $this;
    }

    public function getObjectName(){
        return $this->objectName;
    }

    /**
     * 获取路由实例管理实例
     */
    protected function getServRouteInstance(){
        if($this->servRouteInstance===NULL){
            $this->servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this->servRouteInstance;
    }
    protected function setServRouteInstance($servRouteInstance){
        $this->servRouteInstance = $servRouteInstance;
    }

    /**
     * 获取公共Dao实例
     */
    protected function getDaoInstance(){
        if(is_null($this->daoInstance)){
            $this->daoInstance = DaoDb::factory($this->objectName);
        }
        return $this->daoInstance;
    }
    protected function setDaoInstance($daoInstance){
        $this->daoInstance = $daoInstance;
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
            is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
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

    /**
     * 读取数据
     * @param array $request_data
     * @return array
     * @throws MyRuntimeException
     */
    public function read($requestData, $dbInstance=NULL){
        try {
            // TODO 根据$requestData组织特征向量做dbInstance;
            is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,array('id'=>$requestData['id']))->getInstance();
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance,$requestData['id']);
            if($daoInstance->loaded == FALSE){
                return NULL;
                //throw new MyRuntimeException(_('object not found'),404);
            }
            return $daoInstance->asArray();
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }

    /**
     * 更新数据
     * @param array $request_data
     * @return void
     * @throws MyRuntimeException
     */
    public function update($requestData, $dbInstance=NULL){
        try {
            // TODO 根据$requestData组织特征向量做dbInstance;
            is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,array('id'=>$requestData['id']))->getInstance();
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance,$requestData['id']);
            if($daoInstance->loaded == FALSE){
                throw new MyRuntimeException(_('object not found'),404);
            }
            $data = $daoInstance->asArray();
            foreach ($requestData as $key=>$val) {
                array_key_exists($key,$data) && $daoInstance->$key = $val;
            }
            $daoInstance->save();
            if($daoInstance->saved !== TRUE){
                throw new MyRuntimeException(_('internal error'),500);
            }
            //return $daoInstance->saved;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }

    /**
     * 删除数据
     * @param array $request_data
     * @return void
     * @throws MyRuntimeException
     */
    public function delete($requestData, $dbInstance=NULL){
        try {
            // TODO 根据$requestData组织特征向量做dbInstance;
            is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,array('id'=>$requestData['id']))->getInstance();
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance,$requestData['id']);
            if($daoInstance->loaded == FALSE){
                throw new MyRuntimeException(_('object not found'),404);
            }
            $daoInstance->delete();
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 查询数据项
     * @param array $queryStruct
     * @return array
     * @throws MyRuntimeException
     */
    public function queryVar($queryStruct=array(),$dbInstance=NULL){
        try {
            // TODO 根据$queryStruct组织特征向量做dbInstance;
            $routeSet = array();
            if(!empty($queryStruct) && isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey']['id'])){
                $routeSet = array('id'=>$queryStruct['conditionKey']['id']);
            }
            if(!empty($routeSet)){
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,$routeSet)->getInstance();
            }else{
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
            }
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance);
            return $daoInstance->queryVar($queryStruct);
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    /**
     * 查询数据行
     * @param array $queryStruct
     * @return array
     * @throws MyRuntimeException
     */
    public function queryRow($queryStruct=array(),$dbInstance=NULL){
        try {
            // TODO 根据$queryStruct组织特征向量做dbInstance;
            $routeSet = array();
            if(!empty($queryStruct) && isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey']['id'])){
                $routeSet = array('id'=>$queryStruct['conditionKey']['id']);
            }
            if(!empty($routeSet)){
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,$routeSet)->getInstance();
            }else{
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
            }
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance);
            return $daoInstance->queryRow($queryStruct);
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 查询数据列表
     * @param array $queryStruct
     * @return array
     * @throws MyRuntimeException
     */
    public function queryAssoc($queryStruct=array(),$dbInstance=NULL){
        try {
            // TODO 根据$queryStruct组织特征向量做dbInstance;
            $routeSet = array();
            if(!empty($queryStruct) && isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey']['id'])){
                $routeSet = array('id'=>$queryStruct['conditionKey']['id']);
            }
            if(!empty($routeSet)){
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,$routeSet)->getInstance();
            }else{
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
            }
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance);
            return $daoInstance->queryAssoc($queryStruct);
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }

    /**
     * 查询数据统计结果
     * @param array $queryStruct
     * @return int
     * @throws MyRuntimeException
     */
    public function queryCount($queryStruct=array(),$dbInstance=NULL){
        try {
            // TODO 根据$queryStruct组织特征向量做dbInstance;
            $routeSet = array();
            if(!empty($queryStruct) && isset($queryStruct['conditionKey']) && isset($queryStruct['conditionKey']['id'])){
                $routeSet = array('id'=>$queryStruct['conditionKey']['id']);
            }
            if(!empty($routeSet)){
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName,$routeSet)->getInstance();
            }else{
                is_null($dbInstance) && $dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
            }
            $daoInstance = DaoDb::factory($this->objectName ,$dbInstance);
            return $daoInstance->queryCount($queryStruct);
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    //** 业务逻辑代码请写在此行之后　**//

    //FIXME 根据本类属性对这部分应用函数做一定业务逻辑上的调整
    public function get($id){
        // Custom 
        return $this->read(array('id'=>$id));
    }
    public function set($id,$data){
        // Custom 
        $requestData = $data;
        $requestData['id'] = $id;
        return $this->update($requestData);
    }
    public function add($data){
        // Custom 
        return $this->create($data);
    }
    public function remove($id){
        // Custom 
        return $this->delete(array('id'=>$id));
    }
    public function index($queryStruct=array()){
        // Custom 
        return $this->queryAssoc($queryStruct);
    }
    public function count($queryStruct=array()){
        // Custom 
        return $this->queryCount($queryStruct);
    }

    //:: 本类定制的业务逻辑 :://
    //TODO 根据业务逻辑需求提供对应的函数调用
    
}
