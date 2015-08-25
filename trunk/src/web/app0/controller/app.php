<?php
/**
 * Allows a app to be automatically loaded and displayed. Display can be
 * dynamically turned off in the controller methods, and the app file
 * can be overloaded.
 *
 * To use it, declare your controller to extend this class:
 * `class Your_Controller extends Template_Controller`
 *
 * $Id: app.php 80 2010-04-02 01:35:09Z zhubin $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class App_Controller extends Controller {

    public static $msgNotice =array(
        0=>'Access Denied',
        1=>'Login First Please',
    );
	// Template view name
	public $template = 'app';

	// Default to do auto-rendering
	public $autoRender = TRUE;
	public $autoMinifiy = TRUE;
    public $ajaxRequest = FALSE;

    public $sessionInstance = NULL;
    public $logon = NULL;
    public $userRoleLabel = 'GUEST';
    public $mgrRole = 'MGR_GUEST';

    public $check_mgr = FALSE;
    public function isAjaxRequest()
    {
        return $this->ajaxRequest == TRUE;
    }
    public function setAjaxRequest($bool)
    {
        $this->ajaxRequest = $bool == TRUE;
    }

	/**
	 * Template loading and setup routine.
	 */
	public function __construct($initSession=TRUE)
	{
	    self::$msgNotice[0]= _('Access Denied');
	    self::$msgNotice[1]= _('Login First Please');
	    
		parent::__construct();
		$this->autoMinifiy = Lemon::config('core.output_minify');
        // checke request is ajax
        $this->ajaxRequest = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        $this->logon = Logon::getInstance();
        $this->cookieLogon();
        // do init session
        if($initSession==TRUE){
            $PHPSESSIONID = $this->input->get('PHPSESSIONID');
            if(!empty($PHPSESSIONID)){
                $this->sessionInstance=Session::instance($PHPSESSIONID);
            }else{
                $this->sessionInstance=Session::instance();
            }
            $getLogonInfo = $this->logon->getLogonInfo();
            if($getLogonInfo['userId']==0 || ($this->check_mgr && $getLogonInfo['mgrRole']==Logon::$MGR_ROLE_LABEL_GUEST)){ // 未登录用户才尝试去session里尝试获取一下用户信息。
                $this->setLogonInfoBySession();
            }
        }
        $this->userRoleLabel = $this->logon->getLogonInfoValueByKey('userRoleLabel',Logon::$USER_ROLE_LABEL_GUEST);
        $this->mgrRole = $this->logon->getLogonInfoValueByKey('mgrRole',Logon::$MGR_ROLE_LABEL_GUEST);

		// Load the app
		$this->template = new View($this->template);

		if ($this->autoRender == TRUE)
		{
			// Render the app immediately after the controller method
			Event::add('system.post_controller', array($this, '_render'));
		}
	}

	public function setSessionInstance($sessionInstance){
	    $this->sessionInstance=$sessionInstance;
	}

	public function getSessionInstance(){
	    return $this->sessionInstance;
	}

	public function cookieLogon(){
	    //TODO
	}
	public function setLogonInfoBySession(){
	    if($this->logon!=NULL && $this->sessionInstance!=NULL){
            $sessionLogonInfo = $this->sessionInstance->get('logonInfo');
            //exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($sessionLogonInfo,TRUE)."\n</pre></div>");
            if(!empty($sessionLogonInfo)){
                $this->logon->setLogonInfo($sessionLogonInfo);
            }else{
                $this->sessionInstance->set('logonInfo',$this->logon->getLogonInfo());
            }
	    }
	}
	/* 根据用户信息更新logon信息 */
    public function updateLogonInfoByUserInfo($requestUserInfo){
        $requestLogonInfo = array(
                'userId'=>$requestUserInfo['id'],
                'userLoginId'=>$requestUserInfo['loginId'],
                'userMail'=>$requestUserInfo['mail'],
                'userScreenName'=>$requestUserInfo['screenName'],
                'userLoginTimestamp'=>$requestUserInfo['loginTimestamp'],
                'activeTimestamp'=>time(),
                'userRoleLabel'=>$requestUserInfo['roleLabel'],
            );
        foreach($requestLogonInfo as $logonKey=>$logonRow){
            $this->logon->setLogonInfoValueByKey($logonKey,$logonRow);
        }
        $this->userRoleLabel = $this->logon->getLogonInfoValueByKey('userRoleLabel',Logon::$USER_ROLE_LABEL_GUEST);
        $this->mgrRole = $this->logon->getLogonInfoValueByKey('mgrRole',Logon::$MGR_ROLE_LABEL_GUEST);
        if($this->sessionInstance!==NULL){
            $this->sessionInstance->set('logonInfo',$this->logon->getLogonInfo());
        }
    }
    
    /* 根据管理信息更新logon信息 */
    public function updateLogonInfoByMgrInfo($requestMgrInfo){
        $requestLogonInfo = array(
                'mgrId'=>$requestMgrInfo['mgrId'],
                'mgrLoginId'=>$requestMgrInfo['mgrLoginId'],
                'mgrMail'=>$requestMgrInfo['mgrMail'],
                'mgrScreenName'=>$requestMgrInfo['mgrScreenName'],
                'mgrLoginTimestamp'=>$requestMgrInfo['mgrLoginTimestamp'],
                'activeTimestamp'=>time(),
                
            );
        isset($requestMgrInfo['mgrRole']) && $requestLogonInfo['mgrRole'] = $requestMgrInfo['mgrRole'];
        isset($requestMgrInfo['mgrRoleLabels']) && $requestLogonInfo['mgrRoleLabels'] = $requestMgrInfo['mgrRoleLabels'];
        isset($requestMgrInfo['mgrRelateRoles']) && $requestLogonInfo['mgrRelateRoles'] = $requestMgrInfo['mgrRelateRoles'];
        isset($requestMgrInfo['mgrRegOpRelation']) && $requestLogonInfo['mgrRegOpRelation'] = $requestMgrInfo['mgrRegOpRelation'];
        foreach($requestLogonInfo as $logonKey=>$logonRow){
            $this->logon->setLogonInfoValueByKey($logonKey,$logonRow);
        }
        $this->userRoleLabel = $this->logon->getLogonInfoValueByKey('userRoleLabel',Logon::$USER_ROLE_LABEL_GUEST);
        $this->mgrRole = $this->logon->getLogonInfoValueByKey('mgrRole',Logon::$MGR_ROLE_LABEL_GUEST);
        if($this->sessionInstance!==NULL){
            $this->sessionInstance->set('logonInfo',$this->logon->getLogonInfo());
        }
    }
	public function getUserRoleLabel(){
	    return $this->userRoleLabel;
	}
	public function setUserRoleLabel($label){
	    $this->userRoleLabel=$label;
	    $this->logon->setLogonInfoValueByKey('userRoleLabel',$label);
	    if($this->sessionInstance!==NULL){
	        $this->sessionInstance->set('logonInfo',$this->logon->getLogonInfo());
	    }
	}
    public function getMgrRole(){
        return $this->mgrRole;
    }
    public function setMgrRole($label){
        $this->mgrRole=$label;
        $this->logon->setLogonInfoValueByKey('mgrRole',$label);
        if($this->sessionInstance!==NULL){
            $this->sessionInstance->set('logonInfo',$this->logon->getLogonInfo());
        }
    }

	/**
	 * Render the loaded app.
	 */
	public function _render()
	{
		if ($this->autoRender == TRUE)
		{
		    // Render the app when the class is destroyed
            if ($this->autoMinifiy == TRUE){

                $this->template->render(TRUE,array(__CLASS__, '_minifiyRender'));
            }else{
                $this->template->render(TRUE);
            }
		}
	}
    public static function _minifiyRender($output){
        $headers = array();
        $heads= headers_list();
        
        if(is_array($heads)){
            foreach ($heads as $ahead) {
                list($head_name, $head_value)= explode(":", $ahead);
                if ($head_name){
                    $headers[strtolower($head_name)]= trim($head_value);
                }
            }
        }
        if(array_key_exists('content-type',$headers)){
            $contentTypeStr = $headers['content-type'];
            $contentTypeArr = explode(';',$contentTypeStr);
            $contentType = $contentTypeArr[0];
            $contentType = strtolower(trim($contentType));
            switch($contentType){
                case 'text/html':
                        require_once(Lemon::find_file('vendor', 'htmlmin',TRUE));
                        require_once(Lemon::find_file('vendor', 'cssmin',TRUE));
                        require_once(Lemon::find_file('vendor', 'jsmin',TRUE));
                        $options = array('cssMinifier'=>array('cssmin','minify'),'jsMinifier'=>array('JSMin','minify'));
                        $output = Minify_HTML::minify($output,$options);
                    break;
            }
        }
        return $output;
    }
} // End Template_Controller