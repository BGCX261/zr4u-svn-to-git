<?php
/**
 * 路由驱动 - Tt
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServRoute_Tt_Driver extends ServRoute_Driver {
    private static $type = '';
    public function __construct($defaultSetup=NULL,$thisArgs=NULL){
        parent::__construct($defaultSetup,$thisArgs);
        $className = __CLASS__;
        $tmpInf = explode('_',$className);
        self::$type = strtolower($tmpInf[1]);
        $this->setDefault();
        $this->setup($thisArgs);
    }
    public function getSetupArray($configPath=NULL){
        $currentConfigPath = PROJECT_ROOT.'var/etc/web/'.APP_CODE.'/'.self::$type.'/setup.ini';
        //现有配置为空
        if(empty($this->setupArray)){ 
            if(!empty($configPath)){ //指定配置
                $currentConfigPath = $configPath;
            }
            if(is_file($currentConfigPath)){
                $getConfigArray = parse_ini_file($currentConfigPath,TRUE);
                if(!empty($getConfigArray)){
                    $this->setupArray = $getConfigArray;
                    $this->currentConfigPath = $currentConfigPath;
                }
            }
        }else{//现有配置不为空
            if(!empty($configPath) && $configPath!=$this->currentConfigPath){//新配置和现有的不同
                $currentConfigPath = $configPath;
                if(is_file($currentConfigPath)){
                    $getConfigArray = parse_ini_file($currentConfigPath,TRUE);
                    if(!empty($getConfigArray)){
                        $this->setupArray = $getConfigArray;
                        $this->currentConfigPath = $currentConfigPath;
                    }
                }
            }
        }
        return $this->setupArray;
    }
    public function setDefault($defaultSetup = NULL){
        if(!empty($defaultSetup)){
            $this->defaultSetup = $this->filterVars($defaultSetup);
        }else{
            $this->getSetupArray();
            if(!empty($this->setupArray) && array_key_exists('default',$this->setupArray)){
                $this->defaultSetup = $this->filterVars($this->setupArray['default']);
            }else{
                if(!empty($this->rootDefaultSetup)){
                    $this->defaultSetup = $this->rootDefaultSetup;
                }
            }
        }
    }
    public function setup($thisArgs = NULL){
        if(!empty($thisArgs)){
            $this->thisArgs = $thisArgs;
            $separateId = '';
            $routeKeyBase = isset($thisArgs[0])?$thisArgs[0]:'default';
            $attributes = isset($thisArgs[1])?$thisArgs[1]:array();
            if(!empty($attributes)){
                switch ($routeKeyBase){
                    case 'Attachment':
                                $routeId=array_key_exists('id',$attributes)?$attributes['id']:0;
                                //TODO 根据不同的设置配置到不同的设定上去
                                if($routeId<50000){
                                    $separateId=1;
                                }elseif($routeId>49999 && $routeId <100000){
                                    $separateId=2;
                                }elseif($routeId>99999 && $routeId <150000){
                                    $separateId=3;
                                }elseif($routeId>149999 && $routeId <200000){
                                    $separateId=4;
                                }elseif($routeId>199999 && $routeId <250000){
                                    $separateId=5;
                                }elseif($routeId>249999 && $routeId <300000){
                                    $separateId=6;
                                }else{
                                    // 超值数据，未分组数据放在备用待分组库里
                                    $separateId=0;
                                }
                        break;
                    case 'Store':
                                $routeId=array_key_exists('id',$attributes)?$attributes['id']:0;
                                //TODO 根据不同的设置配置到不同的设定上去
                                if($routeId<100000){
                                    $separateId=1;
                                }elseif($routeId>99999 && $routeId <200000){
                                    $separateId=2;
                                }elseif($routeId>199999 && $routeId <300000){
                                    $separateId=3;
                                }else{
                                    // 超值数据，未分组数据放在备用待分组库里
                                    $separateId=0;
                                }
                        break;
                    case 'StoreData':
                                $routeId=array_key_exists('id',$attributes)?$attributes['id']:0;
                                //TODO 根据不同的设置配置到不同的设定上去
                                if($routeId<100000){
                                    $separateId=1;
                                }elseif($routeId>99999 && $routeId <200000){
                                    $separateId=2;
                                }elseif($routeId>199999 && $routeId <300000){
                                    $separateId=3;
                                }else{
                                    // 超值数据，未分组数据放在备用待分组库里
                                    $separateId=0;
                                }
                        break;
                    case 'Temp':
                                $routeId=array_key_exists('id',$attributes)?$attributes['id']:0;
                                //TODO 根据不同的设置配置到不同的设定上去
                                $separateId = $routeId % 2;
                        break;
                    case 'default':
                    default:
                        $routeKeyBase = 'default';
                        $separateId = '';
                }
            }
            $this->routeKey = $routeKeyBase.$separateId;
            $this->getSetupArray();
            if(!empty($this->setupArray) && array_key_exists($this->routeKey,$this->setupArray)){
                $currentSetup = $this->filterVars($this->setupArray[$this->routeKey]);
                $this->currentSetup = array_merge($this->defaultSetup,$currentSetup);
            }else{
                if($this->routeKey!='default'){
                    $this->currentSetup = $this->defaultSetup;
                }else{
                    throw new ServRouteDriverException(_('required Configure not found'),404);
                }
            }
            $this->thisArgs = NULL;
        }
        empty($this->currentSetup) && $this->currentSetup = $this->defaultSetup;
        empty($this->routeKey) && $this->routeKey = 'default';
    }
}