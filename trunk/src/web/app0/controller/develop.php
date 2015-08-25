<?php
/**
 * 默认Controller
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
class Develop_Controller extends App_Controller {
    // Disable this controller when Kohana is set to production mode.
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
            if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$MGR_ROLE_LABEL_DENIED,Logon::$MGR_ROLE_LABEL_GUEST), $this->getMgrRole())==FALSE){
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
    
    public function index()
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
            if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$MGR_ROLE_LABEL_DENIED,Logon::$MGR_ROLE_LABEL_GUEST), $this->getMgrRole())==FALSE){
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
     * 控制台命令处理
     */
    public function console_service()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {
            //禁止自动压缩 
            $this->autoMinifiy = FALSE;
            //* 初始化返回数据 */
            $returnContext = '';
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->post();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
            //if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$USER_ROLE_LABEL_DENIED,Logon::$USER_ROLE_LABEL_GUEST), $this->getUserRoleLabel())==FALSE){
            //    throw new MyRuntimeException(_('Access Denied'),403);
            //}
            if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$MGR_ROLE_LABEL_DENIED,Logon::$MGR_ROLE_LABEL_GUEST), $this->getMgrRole())==FALSE){
                throw new MyRuntimeException(_('Access Denied'),403);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            
            //* 数据验证 ==根据业务逻辑定制== */
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            
            // 执行业务逻辑
            $inputCommandLine = $this->input->post('input');
            if($inputCommandLine=='help'){
                $availableCommands = array(
                    'view [serverID]'=>'display #serverID server\'s information.',
                    'about'=>'about us',
                    'help'=>'Print this help message',
                );
                $welcomeMessage = 'Welcome to Ultimate-Complicated-Command-Line-Server'.PHP_EOL;
                $cmdsStr = '';
                foreach($availableCommands as $key=>$val){
                    $cmdsStr.='     '.$key.'    '.$val.PHP_EOL;
                }
                $cmdsStr = str_replace(' ','&nbsp;',$cmdsStr);
                
                $returnContext.= $welcomeMessage;
                $returnContext.= $cmdsStr;
            }elseif(preg_match('/^view\s+([0-9a-zA-Z]+)/',$inputCommandLine,$matches)){
                $returnStr = '';
                $returnStr .= 'ServerID: '.$matches[1].PHP_EOL;
                //TODO 显示对应的信息
                $returnStr .= ' TODO '.PHP_EOL;
                $returnContext.= $returnStr;
            }elseif($inputCommandLine=='about'){
                $returnStr = '';
                $returnStr.= ' View My info at <a href="http://axiong.me" target="_blank">http://axiong.me</a>';
                $returnContext.= $returnStr;
            }else{
                $returnContext.='Unknown Command,type \'help\' for help.';
            }
            
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                $this->template = new View('layout/console');
                $this->template->content = $returnStruct;
                $this->template->context = $returnContext;
            }else{
                throw new MyRuntimeException(_('Not Implemented'),501);
//                // html 输出
//                //* 模板输出 */
//                $this->template->returnStruct = $returnStruct;
//                $content = new View($this->packageName.'/'.$this->className.'/'.__FUNCTION__);
//                //* 变量绑定 */
//                $this->template->title = Lemon::config('site.name');
//                $this->template->content = $content;
//                //* 请求结构数据绑定 */
//                $this->template->content->requestData = $requestData;
//                //* 返回结构体绑定 */
//                $this->template->content->returnStruct = $returnStruct;
//                //:: 当前应用专用数据
//                $this->template->content->title = Lemon::config('site.name');
                
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
     * 控制台命令
     */
    public function console()
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
            $requestData = $this->input->post();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
            if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$MGR_ROLE_LABEL_DENIED,Logon::$MGR_ROLE_LABEL_GUEST), $this->getMgrRole())==FALSE){
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
                throw new MyRuntimeException(_('Not Implemented'),501);
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template = new View('layout/base_html');
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
                $addonJsLinkContext = '';
                $addonJsLinkContext .= '<script type="text/javascript" src="http://res.zr4u.com/res/js/jquery/jquery-1.4.2.min.js"></script>'.PHP_EOL;
                $addonJsLinkContext .= '<script type="text/javascript" src="http://res.zr4u.com/res/js/jquery/plugins/jquery.terminal.min.js"></script>'.PHP_EOL;
                $this->template->addonJsLinkContext = $addonJsLinkContext;
                $addonCssContentContext = '';
                $addonCssContentContext .= 'body { margin: 0;}'.PHP_EOL;
                $this->template->addonCssContentContext = $addonCssContentContext;
                
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

    public function sandbox()
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
            $getData = $this->input->get();
            $postData = $this->input->post();
            empty($getData) && $getData = array();
            empty($postData) && $postData = array();
            $requestData = array_merge($getData,$postData);
            
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException(_('Not Implemented'),501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
//            if(util::isAccess(array(Logon::$MGR_ROLE_LABEL_SYS_ADMIN,), array(Logon::$MGR_ROLE_LABEL_DENIED,Logon::$MGR_ROLE_LABEL_GUEST), $this->getMgrRole())==FALSE){
//                throw new MyRuntimeException(_('Access Denied'),403);
//            }
            //* 权限验证 ==根据业务逻辑定制== */
            
            //* 数据验证 ==根据业务逻辑定制== */
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            
            // 执行业务逻辑
            
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            
            //$seqService = Seq_Service::getInstance($servRouteInstance);
            //$tempId = $seqService->currentSeq('Temp');
            //print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($tempId,TRUE)."\n</pre></div>");
            //exit;
//            $myTemp = Temp_Service::getInstance($servRouteInstance);
////            $myt1 = Temp_Service::factory($servRouteInstance);
////            $myt2 = Temp_Service::factory($servRouteInstance);
//            $reqObj = array('name'=>'abc'.util::reRandStr(3),'val'=>'123abc');
//             $retId = $myTemp->add($reqObj);
//             print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($retId,TRUE)."\n</pre></div>");
//
//             $retObj = $myTemp->get($retId);
//             print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($retObj,TRUE)."\n</pre></div>");
//             exit;
//            $tobj1 = $myTemp->get(1);
//            $tobj2 = $myt1->get(2);
//            $tobj3 = $myt2->get(1);
//            print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($tobj1,TRUE)."\n</pre></div>");
//            print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($tobj2,TRUE)."\n</pre></div>");
//            print("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($tobj3,TRUE)."\n</pre></div>");
//            exit;
            
//            /* == thrift 调用样例 Start == */
//                // thrift 相关调用
//                require_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';
//                require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
//                require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php';
//                require_once $GLOBALS['THRIFT_ROOT'].'/transport/THttpClient.php';
//                require_once $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php';
//                // thrift 应用接口相关调用接口类定义库
//                $GEN_DIR = $GLOBALS['THRIFT_ROOT'].'/packages/zr4u';
//                require_once $GEN_DIR.'/MyappInterface.php';
//                require_once $GEN_DIR.'/zr4u_constants.php';
//                require_once $GEN_DIR.'/zr4u_types.php';
//                try {
//                  // thrift 服务调用
//                  $socket = new TSocket(Lemon::config('thrift.default.Host'), Lemon::config('thrift.default.Port'));
//                  $transport = new TBufferedTransport($socket, 1024, 1024);
//                  $protocol = new TBinaryProtocol($transport);
//                  $client = new ExpoInterfaceClient($protocol);
//                  $transport->open();
//                  //接口业务逻辑
//                  $serviceVersion = $client->getVER();
//                  
//                  //通讯关闭
//                  $transport->close();
//                } catch (TException $ex) {
//                    //print 'TException: '.$tx->getMessage()."\n";
//                    throw new MyRuntimeException(_('Server Communication Error'),500);
//                }
//            $returnData['serviceVersion']=$serviceVersion;
//            
//            /* == thrift 调用样例 End == */
            
            
            
//            /* == FS 调用样例 Start == */
//            // 调用路由实例
//            $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
//            
//            // 当前应用模块
//            $currentModuleName = 'attach';
//            // 收集数据特征
//            $testUserId = intval(date('YWHi',strtotime('2010-04-06 11:11:00')));
//            $crts = time();
//            //获取对应服务的路由实例
//            $fsInst_attach = $servRouteInstance->getFsInstance($currentModuleName,array('userId'=>$testUserId,'crts'=>$crts))->getInstance();
//
//            // 调用对应服务的对应调用方法使用服务
//            $fileKey = 'myfile_'.date('YmdHi',strtotime('2010-04-06 11:11:00'));
//            $putFileContent = md5(uniqid(rand(), true));
//            
//            $saveOk = $fsInst_attach->putFileData($fileKey,$putFileContent);
//            $getFileContent = $fsInst_attach->getFileData($fileKey);
//            
//            $returnData['fileKey'] = $fileKey;
//            $returnData['saveOK'] = $saveOk?'Yes':'No';
//            $returnData['putContent'] = $putFileContent;
//            $returnData['getContent'] = $getFileContent;
//            $returnData['match'] = $getFileContent==$putFileContent?'Yes':'No';
//            
//            /* == FS 调用样例 End == */
            
//            /* == Db 调用样例 Start == */
//            // 调用路由实例
//            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
//            //获取对应服务的路由实例
//            !isset($dbInst_default) && $dbInst_default = $servRouteInstance->getDbInstance()->getInstance();
//            $results = $dbInst_default->get_results("SHOW COLUMNS FROM Manager", OBJECT);
//            $returnData['dbresult'] = $results;
//            /* == Db 调用样例 End == */
            
            

            
            $returnMessage = 'Test Ok';
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
}