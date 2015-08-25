<?php
/**
 * 数据服务
 * 根据底层驱动的特性以及dao层的封装去curd数据。
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class Seq_Service extends Default_Service {
    
    private $seqInstances = array();
    private $dbInstance = NULL;
//    protected static $instance = NULL;
//    // 获取单态实例
//    public static function getInstance($servRouteInstance = NULL){
//        if(self::$instance === null){
//            $classname = __CLASS__;
//            self::$instance = new $classname($servRouteInstance);
//        }
//        return self::$instance;
//    }

    /**
     * 获取公共Dao实例
     */
    protected function getDbInstance(){
        if(is_null($this->dbInstance)){
            $this->dbInstance = $this->servRouteInstance->getDbInstance($this->objectName)->getInstance();
        }
        return $this->dbInstance;
    }
    protected function setDbInstance($dbInstance){
        $this->dbInstance = $dbInstance;
    }
    //** 业务逻辑代码请写在此行之后　**//

    public function nextSeq($objectName = ''){
        $dbInstance = $this->getDbInstance();
        $daoInstance = DaoDb::factory($this->objectName.$objectName,$dbInstance);
        $daoInstance->save();
        if($daoInstance->saved !== TRUE){
            throw new MyRuntimeException(_('internal error'),500);
        }
        //TODO 逻辑与数据分离：状态与数据分离
        return $daoInstance->id;
    }
    
    public function currentSeq($objectName = ''){
        $dbInstance = $this->getDbInstance();
        $daoInstance = DaoDb::factory($this->objectName.$objectName,$dbInstance);
        $queryStruct = array(
            'orderSet'=>array(
                array('id'=>'DESC',),
            ),
            'limitOffset'=>array('limit'=>1),
        );
        $resultVar = $daoInstance->queryVar($queryStruct);
        return !empty($resultVar)?intval($resultVar):0;
    }
    
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
