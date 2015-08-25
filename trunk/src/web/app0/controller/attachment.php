<?php
/**
 * 默认Controller
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
class Attachment_Controller extends App_Controller {
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
     * 查看
     */
    public function view($id)
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            // 是否调用本地服务
            $useLocalService = TRUE;
            //$useLocalService = FALSE;
            //* 初始化返回数据 */
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->get();
            !empty($id) && !isset($requestData['id']) && $requestData['id'] = $id;
            
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
            if(!isset($requestData['id']) || empty($requestData['id']) || !is_numeric($requestData['id'])){
                throw new MyRuntimeException(_('Bad Request,id required'),400);
            }
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            // 执行业务逻辑
            if($useLocalService==TRUE){
                !isset($attachmentService) && $attachmentService = Attachment_Service::getInstance($servRouteInstance);
            }else{
                require_once(Lemon::find_file('vendor', 'phprpc/phprpc_client',TRUE));
                !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Lemon::config('phprpc.remote.Attachment.host'));
                !isset($phprpcApiKey) && $phprpcApiKey = Lemon::config('phprpc.remote.Attachment.apiKey');
            }
            
            if($useLocalService==TRUE){
                $attachmentData = $attachmentService->getAttachmentDataById($requestData['id']);
            }else{
                $args = array($requestData['id']);
                $sign = md5(json_encode($args).$phprpcApiKey);
                $attachmentData = $attachmentService->phprpc_getAttachmentDataById($requestData['id'],$sign);
            }
            
            if(empty($attachmentData)){
                throw new MyRuntimeException(_('Attachment Not Found'),404);
            }
            $attachmentAllowView = in_array($attachmentData['filePostfix'],Lemon::config('mimemap.allowViewTypes'));
            $isImgType =  in_array($attachmentData['filePostfix'],Lemon::config('mimemap.isImgType'));
            $returnData = $attachmentData;
            
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            // 资源更新时间戳
            $resourceUpdateTimestamp = $attachmentData['modifyTimestamp'];
            // 资源缓存时间间隔
            $resourceCacheTimeInterval = Lemon::config('attach.httpCacheTimeDefault'); // 当前应用配置数据覆盖全局设置
            // 发送检测http lastModified头
            page::httpLastModified($resourceUpdateTimestamp);
            // 发送http过期头
            page::httpExpiresInterval($resourceCacheTimeInterval);
            
            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
                // 资源更新时间戳
                $this->template->resourceUpdateTimestamp = $resourceUpdateTimestamp;
                // 资源缓存时间间隔
                $this->template->resourceCacheTimeInterval = $resourceCacheTimeInterval;
            }else{
                    //查看或者是下载
                    if($useLocalService==TRUE){
                        $storeData = $attachmentService->getStoreDataByStoreId($attachmentData['storeId']);
                    }else{
                        $args = array($attachmentData['storeId']);
                        $sign = md5(json_encode($args).$phprpcApiKey);
                        $storeData = $attachmentService->phprpc_getStoreDataByStoreId($attachmentData['storeId'],$sign);
                    }
                    if($attachmentAllowView==TRUE){
                        // 查看视图
                        $this->template= new View($this->packageName.'/'.$this->className.'/'.'get'.'DefaultView');
                        // 是否为图片类型
                        $this->template->isImgType = $isImgType;
                    }else{
                        // 下载视图
                        $this->template= new View($this->packageName.'/'.$this->className.'/'.'get'.'DefaultDownload');
                    }
                    
                    // 附件信息
                    $this->template->attachmentData = $attachmentData;
                    // 存储数据实体
                    $this->template->storeData = $storeData;
                // 资源更新时间戳
                isset($resourceUpdateTimestamp) && $this->template->resourceUpdateTimestamp = $resourceUpdateTimestamp;
                // 资源缓存时间间隔
                isset($resourceCacheTimeInterval) && $this->template->resourceCacheTimeInterval = $resourceCacheTimeInterval;
                
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
     * 获取
     */
    public function get($id)
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            // 是否调用本地服务
            $useLocalService = TRUE;
            //$useLocalService = FALSE;
            //* 初始化返回数据 */
            $returnStatus = 1;
            $returnCode = 200;
            $returnMessage = '';
            $returnData = array();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $requestData = $this->input->get();
            !empty($id) && !isset($requestData['id']) && $requestData['id'] = $id;
            
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
            if(!isset($requestData['id']) || empty($requestData['id']) || !is_numeric($requestData['id'])){
                throw new MyRuntimeException(_('Bad Request,id required'),400);
            }
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            // 执行业务逻辑
            if($useLocalService==TRUE){
                !isset($attachmentService) && $attachmentService = Attachment_Service::getInstance($servRouteInstance);
            }else{
                require_once(Lemon::find_file('vendor', 'phprpc/phprpc_client',TRUE));
                !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Lemon::config('phprpc.remote.Attachment.host'));
                !isset($phprpcApiKey) && $phprpcApiKey = Lemon::config('phprpc.remote.Attachment.apiKey');
            }
            if($useLocalService==TRUE){
                $attachmentData = $attachmentService->getAttachmentDataById($requestData['id']);
            }else{
                $args = array($requestData['id']);
                $sign = md5(json_encode($args).$phprpcApiKey);
                $attachmentData = $attachmentService->phprpc_getAttachmentDataById($requestData['id'],$sign);
            }
            if(empty($attachmentData)){
                throw new MyRuntimeException(_('Attachment Not Found'),404);
            }
            $attachmentAllowView = in_array($attachmentData['filePostfix'],Lemon::config('mimemap.allowViewTypes'));
            $isImgType =  in_array($attachmentData['filePostfix'],Lemon::config('mimemap.isImgType'));
            $returnData = $attachmentData;
            
            //* 补充&修改返回结构体 */
            $returnStruct['status'] = $returnStatus;
            $returnStruct['code']   = $returnCode;
            $returnStruct['msg']    = $returnMessage;
            $returnStruct['content']= $returnData;

            // 资源更新时间戳
            $resourceUpdateTimestamp = $attachmentData['modifyTimestamp'];
            // 资源缓存时间间隔
            $resourceCacheTimeInterval = Lemon::config('attach.httpCacheTimeDefault'); // 当前应用配置数据覆盖全局设置
            // 发送检测http lastModified头
            page::httpLastModified($resourceUpdateTimestamp);
            // 发送http过期头
            page::httpExpiresInterval($resourceCacheTimeInterval);
            
            //* 请求类型 */
            if($this->isAjaxRequest()){
                // ajax 请求
                // json 输出
                $this->template->content = $returnStruct;
                // 资源更新时间戳
                $this->template->resourceUpdateTimestamp = $resourceUpdateTimestamp;
                // 资源缓存时间间隔
                $this->template->resourceCacheTimeInterval = $resourceCacheTimeInterval;
            }else{
                if(!isset($requestData['view']) && !isset($requestData['download'])){
                    // html 输出
                    //* 模板输出 */
                    //$this->template->return_struct = $return_struct;
                    $content = new View($this->packageName.'/'.$this->className.'/'.__FUNCTION__);
                    //* 变量绑定 */
                    $this->template->title = html::specialchars(strip_tags($attachmentData['fileName'])).' - '.Lemon::config('site.name');
                    $this->template->content = $content;
                    //* 请求结构数据绑定 */
                    $this->template->content->requestData = $requestData;
                    //* 返回结构体绑定 */
                    $this->template->content->returnStruct = $returnStruct;
                    //:: 当前应用专用数据
                    $this->template->content->title = $attachmentData['fileName'];
                    $this->template->content->attachmentAllowView = $attachmentAllowView;
                    $this->template->content->isImgType = $isImgType;
                    
                }else{
                    //查看或者是下载
                    if($useLocalService==TRUE){
                        $storeData = $attachmentService->getStoreDataByStoreId($attachmentData['storeId']);
                    }else{
                        $args = array($attachmentData['storeId']);
                        $sign = md5(json_encode($args).$phprpcApiKey);
                        $storeData = $attachmentService->phprpc_getStoreDataByStoreId($attachmentData['storeId'],$sign);
                    }
                    if(isset($requestData['view']) && $attachmentAllowView){
                        // 查看视图
                        $this->template= new View($this->packageName.'/'.$this->className.'/'.__FUNCTION__.'DefaultView');
                        // 是否为图片类型
                        $this->template->isImgType = $isImgType;
                    }else{
                        // 下载视图
                        $this->template= new View($this->packageName.'/'.$this->className.'/'.__FUNCTION__.'DefaultDownload');
                    }
                    
                    // 附件信息
                    $this->template->attachmentData = $attachmentData;
                    // 存储数据实体
                    $this->template->storeData = $storeData;
                }
                // 资源更新时间戳
                isset($resourceUpdateTimestamp) && $this->template->resourceUpdateTimestamp = $resourceUpdateTimestamp;
                // 资源缓存时间间隔
                isset($resourceCacheTimeInterval) && $this->template->resourceCacheTimeInterval = $resourceCacheTimeInterval;
                
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
     * 上传处理
     */
    public function uploadForm()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

            // 是否调用本地服务
            $useLocalService = TRUE;
            //$useLocalService = FALSE;
            
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
            
            //:: 多附件上传
            // 上传的表单域名字
            $attachField = 'myattach';
            // 附件应用类型
            $attachAppType = 'appAttach';
            // 如果有上传请求
            if(page::issetFile($attachField)){
                $returnData['attach']=array();
                //读取当前应用配置
                $attachSetup = Lemon::config('attach.'.$attachAppType);
                $mimeType2Postfix = Lemon::config('mimemap.type2postfix');
                $mimePostfix2Type = Lemon::config('mimemap.postfix2type');

                // 表单文件上传控件总数量
                $fileUploadCount = page::getFileCount($attachField);
                // 初始化一些数据
                // 本次文件上传总数量
                $fileCountTotal = 0;
                // 本次文件上传总大小
                $fileSizeTotal = 0;
                // 上传文件meta信息
                $fileMetaData = array();
                // 遍历所有的上传域 //验证上传/采集上传信息
                for($index=0;$index<$fileUploadCount;$index++){
                    // 如果上传标志成功
                    if((int) $_FILES[$attachField]['error'][$index] === UPLOAD_ERR_OK){
                        if(!is_uploaded_file($_FILES[$attachField]['tmp_name'][$index])){
                            throw new MyRuntimeException(_('File not uploaded,index:').$index,400);
                        }
                        $fileSizeCurrent = filesize($_FILES[$attachField]['tmp_name'][$index]);
                        if($attachSetup['fileSizePreLimit']>0 && $fileSizeCurrent>$attachSetup['fileSizePreLimit']){
                            throw new MyRuntimeException(_('File Size PreLimit exceed,Limit:').$attachSetup['fileSizePreLimit'].' index:'.$index.' size:'.$fileSizeCurrent,400);
                        }
                        
                        $fileTypeCurrent = FALSE;
                        $fileTypeCurrent === FALSE && page::getImageType($_FILES[$attachField]['tmp_name'][$index]); // 尝试通过图片类型判断
                        $fileTypeCurrent === FALSE && $fileTypeCurrent = page::getFileType($attachField,$index); // 尝试通过Mime类型判断
                        $fileTypeCurrent === FALSE && $fileTypeCurrent = page::getPostfix($attachField,$index); // 尝试通过后缀截取
                        if(!empty($attachSetup['allowTypes']) && !in_array($fileTypeCurrent,$attachSetup['allowTypes'])){
                            throw new MyRuntimeException(_('File Type invalid,index:').$index,400);
                        }
                        // 当前文件mime类型
                        $fileMimeCurrent = isset($_FILES[$attachField]['type'][$index])?$_FILES[$attachField]['type'][$index]:'';
                        // 检测规整mime类型
                        if(!array_key_exists($fileMimeCurrent,$mimeType2Postfix)){
                            if(array_key_exists($fileTypeCurrent,$mimePostfix2Type)){
                                $fileMimeCurrent = $mimePostfix2Type[$fileTypeCurrent];
                            }else{
                                $fileMimeCurrent = 'application/octet-stream';
                            }
                        }
                        
                        //存储文件meta信息
                        $fileMetaData[$index]=array(
                            'name'=>strip_tags($_FILES[$attachField]['name'][$index]),
                            'size'=>$fileSizeCurrent,
                            'type'=>$fileTypeCurrent,
                            'mime'=>$fileMimeCurrent,
                        );
                        // 设置上传总数量
                        $fileCountTotal +=1;
                        // 设置上传总大小
                        $fileSizeTotal+=$fileSizeCurrent;
                    }
                }
                if($attachSetup['fileCountLimit']>0 && $fileCountTotal>$attachSetup['fileCountLimit']){
                    throw new MyRuntimeException(_('File Count Limit exceed,Limit:').$attachSetup['fileCountLimit'],400);
                }
                if($attachSetup['fileSizeTotalLimit']>0 && $fileSizeTotal>$attachSetup['fileSizeTotalLimit']){
                    throw new MyRuntimeException(_('File Size Total Limit exceed,Limit:').$attachSetup['fileSizeTotalLimit'].' size:'.$fileSizeTotal,400);
                }
                // 执行上传
                // 调用附件服务
                if($useLocalService==TRUE){
                    !isset($attachmentService) && $attachmentService = Attachment_Service::getInstance($servRouteInstance);
                }else{
                    require_once(Lemon::find_file('vendor', 'phprpc/phprpc_client',TRUE));
                    !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Lemon::config('phprpc.remote.Attachment.host'));
                    !isset($phprpcApiKey) && $phprpcApiKey = Lemon::config('phprpc.remote.Attachment.apiKey');
                }
                //预备一些数据
                $srcIpAddress = $this->input->ip_address();
                $timeStampCurrent = time();
                // 遍历所有的上传meta域
                foreach($fileMetaData as $index=>$fileMeta){
                    $attachmentData = array(
                        'filePostfix'=>$fileMeta['type'],
                        'fileMimeType'=>$fileMeta['mime'],
                        'fileSize'=>$fileMeta['size'],
                        'fileName'=>$fileMeta['name'],
                        'srcIp'=>$srcIpAddress,
                        'createTimestamp'=>$timeStampCurrent,
                        'modifyTimestamp'=>$timeStampCurrent,
                    );
                    // 调用后端添加附件信息，并调用存储服务存储文件
                    if($useLocalService==TRUE){
                        $attachmentId = $attachmentService->addAttachmentFileData($attachmentData,@file_get_contents($_FILES[$attachField]['tmp_name'][$index]));
                    }else{
                        $args = array($attachmentData);
                        $sign = md5(json_encode($args).$phprpcApiKey);
                        $attachmentId = $attachmentService->phprpc_addAttachmentFileData($attachmentData,@file_get_contents($_FILES[$attachField]['tmp_name'][$index]),$sign);
                    }
                    $returnData['attach'][]=$attachmentId;
                }
            }
            
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
     * 删除数据 action
     */
    public function delete()
    {
        $returnStruct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => _('Not Implemented'),
            'content'       => array(),
        );
       try {

           // 是否调用本地服务
            $useLocalService = TRUE;
            //$useLocalService = FALSE;
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
            if(!isset($requestData['id']) || empty($requestData['id']) || !is_numeric($requestData['id'])){
                throw new MyRuntimeException(_('Bad Request,id required'),400);
            }
            //* 逻辑验证 ==根据业务逻辑定制== */
            
            // 调用底层服务
            !isset($servRouteInstance) && $servRouteInstance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
            // 执行业务逻辑
            // TODO 根据数据特征定制对应的服务实例
            if($useLocalService==TRUE){
                !isset($attachmentService) && $attachmentService = Attachment_Service::getInstance($servRouteInstance);
            }else{
                require_once(Lemon::find_file('vendor', 'phprpc/phprpc_client',TRUE));
                !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Lemon::config('phprpc.remote.Attachment.host'));
                !isset($phprpcApiKey) && $phprpcApiKey = Lemon::config('phprpc.remote.Attachment.apiKey');
            }
            try{
                
                if($useLocalService==TRUE){
                    $attachmentService->removeAttachmentDataByAttachmentId($requestData['id']);
                }else{
                    $args = array($requestData['id']);
                    $sign = md5(json_encode($args).$phprpcApiKey);
                    $attachmentService->phprpc_removeAttachmentDataByAttachmentId($requestData['id'],$sign);
                }
            }catch(MyRuntimeException $ex){
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            $returnMessage = _('Sucess');
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