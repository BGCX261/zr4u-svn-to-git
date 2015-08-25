<?php
/**
 * 默认Controller
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
class Phprpc_Controller extends App_Controller {
    // Disable this controller when Lemon is set to production mode.
    // See http://docs.kohanaphp.com/installation/deployment for more details.
    const ALLOW_PRODUCTION = TRUE;

    private $packageName = '';
    private $className = '';

    // Set the name of the template to use
    public $template = 'layout/default_html';

    public function __construct()
    {
        $packageName = substr(dirname(__FILE__),strlen(APP_PATH.'controller/'));
        empty($packageName) && $packageName = 'default';
        $this->packageName = $packageName;
        $this->className = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        parent::__construct(FALSE);
        if($this->isAjaxRequest()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    

    /**
     * 默认请求处理模板
     */
    public function _default()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            //* 初始化返回数据 */
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
            //if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$USER_ROLE_LABEL_DENIED,Logon::$USER_ROLE_LABEL_GUEST), $this->getUserRoleLabel())==FALSE){
            //    throw new MyRuntimeException(_('Access Denied'),403);
            //}
            if(util::isAccess('*', array(Logon::$USER_ROLE_LABEL_DENIED,), $this->getUserRoleLabel())==FALSE){
                throw new MyRuntimeException(_('Access Denied'),403);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            
            //* 数据验证 ==根据业务逻辑定制== */
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            
            // 执行业务逻辑
            
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template->returnStruct = $returnStruct;
                $content = new View($this->packageName.'/'.$this->className.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Lemon::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
                //:: 当前应用专用数据
                $this->template->content->title = Lemon::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $returnStruct['status'] = 0;
            $returnStruct['code']   = $ex->getCode();
            $returnStruct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->isAjaxRequest()){
                $this->template->content = $returnStruct;
            }else{
                $this->template->returnStruct = $returnStruct;
                $content = new  View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
            }
        }
    }

    /**
     * rpc服务
     */
    public function storedata()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            //* 初始化返回数据 */
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
            //if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$USER_ROLE_LABEL_DENIED,Logon::$USER_ROLE_LABEL_GUEST), $this->getUserRoleLabel())==FALSE){
            //    throw new MyRuntimeException(_('Access Denied'),403);
            //}
            if(util::isAccess('*', array(Logon::$USER_ROLE_LABEL_DENIED,), $this->getUserRoleLabel())==FALSE){
                throw new MyRuntimeException(_('Access Denied'),403);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            
            //* 数据验证 ==根据业务逻辑定制== */
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            // 执行业务逻辑
            require_once(Lemon::find_file('vendor', 'phprpc/phprpc_server',TRUE));
            $server = new PHPRPC_Server();
            $server->add(array('set', 'get','delete'), StoreData_Service::getInstance()); 
            $server->start();
            exit();
            throw new MyRuntimeException(_('Internal Error'),500);
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template->returnStruct = $returnStruct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Lemon::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
                //:: 当前应用专用数据
                $this->template->content->title = Lemon::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $returnStruct['status'] = 0;
            $returnStruct['code']   = $ex->getCode();
            $returnStruct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->isAjaxRequest()){
                $this->template->content = $returnStruct;
            }else{
                $this->template->returnStruct = $returnStruct;
                $content = new  View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
            }
        }
    }

    /**
     * rpc服务
     */
    public function attachment()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            //* 初始化返回数据 */
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
            //if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$USER_ROLE_LABEL_DENIED,Logon::$USER_ROLE_LABEL_GUEST), $this->getUserRoleLabel())==FALSE){
            //    throw new MyRuntimeException(_('Access Denied'),403);
            //}
            if(util::isAccess('*', array(Logon::$USER_ROLE_LABEL_DENIED,), $this->getUserRoleLabel())==FALSE){
                throw new MyRuntimeException(_('Access Denied'),403);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            
            //* 数据验证 ==根据业务逻辑定制== */
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            // 执行业务逻辑
            require_once(Lemon::find_file('vendor', 'phprpc/phprpc_server',TRUE));
            $server = new PHPRPC_Server();
            $server->add(array('phprpc_addAttachmentFileData', 'phprpc_getAttachmentDataById','phprpc_getStoreDataByStoreId','phprpc_getStoreDataByAttachmentId','phprpc_removeAttachmentDataByAttachmentId','phprpc_getStoreInfoByStoreId'), Attachment_Service::getInstance()); 
            $server->start();
            exit();
            throw new MyRuntimeException(_('Internal Error'),500);
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template->returnStruct = $returnStruct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Lemon::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
                //:: 当前应用专用数据
                $this->template->content->title = Lemon::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $returnStruct['status'] = 0;
            $returnStruct['code']   = $ex->getCode();
            $returnStruct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->isAjaxRequest()){
                $this->template->content = $returnStruct;
            }else{
                $this->template->returnStruct = $returnStruct;
                $content = new  View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->requestData = $requestData;
                //* 返回结构体绑定 */
                $this->template->content->returnStruct = $returnStruct;
            }
        }
    }
}