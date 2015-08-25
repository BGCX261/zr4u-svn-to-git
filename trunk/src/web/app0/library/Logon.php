<?php

class Logon
{
    private static $instance = NULL;
    const USER_ROLE_LABEL_DENIED = 0;
    const USER_ROLE_LABEL_GUEST = 1;
    const USER_ROLE_LABEL_USER_COMMON = 2;

    const MGR_ROLE_LABEL_DENIED = 4;
    const MGR_ROLE_LABEL_GUEST = 5;
    const MGR_ROLE_LABEL_LOGIN = 6;
    const MGR_ROLE_LABEL_SYS_ADMIN = 7;
    const MGR_ROLE_LABEL_APP_MGR = 8;

    public static $USER_ROLE_LABEL = array(
        self::USER_ROLE_LABEL_DENIED => 'DENIED',
        self::USER_ROLE_LABEL_GUEST => 'GUEST',
        self::USER_ROLE_LABEL_USER_COMMON => 'USER_COMMON',
    );
    public static $MGR_ROLE_LABEL = array(
        self::MGR_ROLE_LABEL_DENIED => 'MGR_DENIED',
        self::MGR_ROLE_LABEL_GUEST => 'MGR_GUEST',
        self::MGR_ROLE_LABEL_SYS_ADMIN => 'SYS_ADMIN',
        self::MGR_ROLE_LABEL_APP_MGR => 'APP_MGR',
    );
    public static $USER_ROLE_LABEL_DENIED ='DENIED';
    public static $USER_ROLE_LABEL_GUEST ='GUEST';
    public static $USER_ROLE_LABEL_USER_COMMON ='USER_COMMON';

    public static $MGR_ROLE_LABEL_DENIED ='MGR_DENIED';
    public static $MGR_ROLE_LABEL_GUEST ='MGR_GUEST';
    public static $MGR_ROLE_LABEL_LOGIN ='MGR_LOGIN';
    public static $MGR_ROLE_LABEL_SYS_ADMIN ='SYS_ADMIN';
    public static $MGR_ROLE_LABEL_APP_MGR ='APP_MGR';

    private function __construct($getLogonInfo=NULL){
        try{
            if(!empty($getLogonInfo)){
                self::$logonInfo=$getLogonInfo;
            }
        }catch(Exception $ex){
            throw new Exception(__CLASS__._(' Init Error'));
            exit;
        }
    }

    // 获取单态实例
    public static function getInstance($getLogonInfo=NULL){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname($getLogonInfo);
        }
        return self::$instance;
    }

    public static $logonInfo = array(
        'activeTimestamp'=>0,
        'userId'=>0, //  -1 拒绝访问 0 游客（未登录） 1-n 用户
        'userLoginId'=>'',
        'userMail'=>'',
        'userScreenName'=>'Guest',
        'userLoginTimestamp'=>0,
        'userRoleLabel'=>'GUEST',

        'mgrId'=>0,
        'mgrLoginId'=>'',
        'mgrMail'=>'',
        'mgrScreenName'=>'Guest',
        'mgrLoginTimestamp'=>0,
        'mgrRole'=>'MGR_GUEST', // MGR_DENIED | MGR_GUEST | MGR_LOGIN | SYS_ADMIN | REG_OP
        'mgrRoleLabels'=>array(
//            'APP_MGR',
        ),
        'mgrRelateRoles'=>array(
            'APP'=>array(
                    //{id}=>'APP_MGR'     // 管理的群ID和对应身份
                ),
        ),
    );

    public static function getLogonInfo(){
        return self::$logonInfo;
    }
    public static function setLogonInfo($logonInfo){
        self::$logonInfo = $logonInfo;
    }

    public static function getLogonInfoValueByKey($key,$default = NULL){
        if(isset(self::$logonInfo[$key])){
            return self::$logonInfo[$key];
        }
        return $default;
    }

    public static function setLogonInfoValueByKey($key,$value){
        self::$logonInfo[$key] = $value;
    }
}
