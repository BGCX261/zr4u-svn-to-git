<?php

class util
{
    /**
     * 函数说明: 代码访问控制
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     *              2006-09-06 樊振兴 修改添加charset功能
     * @param       mixed acceptRoleLabels 允许的角色标记（包含允许角色标记的数组或'*'表示所有）
     * @param       mixed deniedRoleLabels 禁止的角色标记（包含禁止角色标记的数组或null表示无）
     * @param       string currRoleLabel 当前用户的角色标记（通常使用isset($_SESSION['user_label'])?$_SESSION['user_label']:'guest'或isset($_COOKIE['uuid'])?$_COOKIE['uuid']:'guest' 作为传入参数）
     * @param       int order 检测顺序 0 Deny,Allow 1 Allow,Deny
     * @return      bool
     */
    public static function isAccess($acceptRoleLabels = '*' , $deniedRoleLabels = null, $currRoleLabel = 'GUEST', $order=0){
        if($order==0){
            if(!empty($deniedRoleLabels)){
                if(in_array($currRoleLabel, $deniedRoleLabels)){
                    return FALSE;
                }
            }
            if($acceptRoleLabels != '*'){
                if(!in_array($currRoleLabel, $acceptRoleLabels)){
                    return FALSE;
                }
            }
        }else{// order
            if($acceptRoleLabels != '*'){
                if(!in_array($currRoleLabel, $acceptRoleLabels)){
                    return FALSE;
                }
            }
            if(!empty($deniedRoleLabels)){
                if(in_array($currRoleLabel, $deniedRoleLabels)){
                    return FALSE;
                }
            }
        }// order
        return TRUE;

    } // End of function isAccess

    /**
     * 函数说明: 代码访问控制
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     *              2006-09-06 樊振兴 修改添加charset功能
     * @param       mixed acceptRoleLabels 允许的角色标记（包含允许角色标记的数组或'*'表示所有）
     * @param       mixed deniedRoleLabels 禁止的角色标记（包含禁止角色标记的数组或null表示无）
     * @param       string currRoleLabel 当前用户的角色标记（通常使用isset($_SESSION['user_label'])?$_SESSION['user_label']:'guest'或isset($_COOKIE['uuid'])?$_COOKIE['uuid']:'guest' 作为传入参数）
     * @param       int order 检测顺序 0 Deny,Allow 1 Allow,Deny
     * @param       string deniedInfo 被拒绝访问时返回的信息内容默认Access Denied
     * @param       string redirectUrl 被拒绝访问时转向的地址,'back'表示返回前一页；'close'表示关闭
     * @param       string frameset 被拒绝访问时转向地址所在框架,'self'表示当前框架，'page'表示整页
     * @param       string charset 系统字符编码默认utf-8
     * @return      bool
     */
    public static function isAccessAction($acceptRoleLabels = '*' , $deniedRoleLabels = null, $currRoleLabel = 'GUEST', $order=0, $deniedInfo = "Access Denied", $redirectUrl = 'back', $frameset = 'self', $charset = 'utf-8'){
        if($order==0){
            if(!empty($deniedRoleLabels)){
                    if(in_array($currRoleLabel, $deniedRoleLabels)){
                                    if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
                                    if(!empty($deniedInfo)){
                                        echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
                                    }
                                    if(!empty($redirectUrl)){
                                                    if($redirectUrl == 'back'){
                                                                    echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
                                                    }elseif($redirectUrl == 'close'){
                                                                    echo "<script type=\"text/javascript\"> window.close();</script>";
                                                    }else{
                                                                    if($frameset == 'page'){
                                                                                    echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
                                                                    }else{
                                                                                    echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
                                                                    }
                                                    }
                                                    exit;
                                                    return false;
                                    }
                                    exit;
                                    return false;
                    }
    }
    if($acceptRoleLabels != '*'){
                    if(!in_array($currRoleLabel, $acceptRoleLabels)){
                                    if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
                                    if(!empty($deniedInfo)){
                                        echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
                                    }
                                    if(!empty($redirectUrl)){
                                                    if($redirectUrl == 'back'){
                                                                    echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
                                                    }elseif($redirectUrl == 'close'){
                                                                    echo "<script type=\"text/javascript\"> window.close();</script>";
                                                    }else{
                                                                    if($frameset == 'page'){
                                                                                    echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
                                                                    }else{
                                                                                    echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
                                                                    }
                                                    }
                                                    exit;
                                                    return false;
                                    }
                                    exit;
                                    return false;
                    }
    }
    
    
        }else{// order
            
        if($acceptRoleLabels != '*'){
                    if(!in_array($currRoleLabel, $acceptRoleLabels)){
                                    if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
                                    if(!empty($deniedInfo)){
                                        echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
                                    }
                                    if(!empty($redirectUrl)){
                                                    if($redirectUrl == 'back'){
                                                                    echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
                                                    }elseif($redirectUrl == 'close'){
                                                                    echo "<script type=\"text/javascript\"> window.close();</script>";
                                                    }else{
                                                                    if($frameset == 'page'){
                                                                                    echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
                                                                    }else{
                                                                                    echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
                                                                    }
                                                    }
                                                    exit;
                                                    return false;
                                    }
                                    exit;
                                    return false;
                    }
    }
        if(!empty($deniedRoleLabels)){
                    if(in_array($currRoleLabel, $deniedRoleLabels)){
                                    if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
                                    if(!empty($deniedInfo)){
                                        echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
                                    }
                                    if(!empty($redirectUrl)){
                                                    if($redirectUrl == 'back'){
                                                                    echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
                                                    }elseif($redirectUrl == 'close'){
                                                                    echo "<script type=\"text/javascript\"> window.close();</script>";
                                                    }else{
                                                                    if($frameset == 'page'){
                                                                                    echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
                                                                    }else{
                                                                                    echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
                                                                    }
                                                    }
                                                    exit;
                                                    return false;
                                    }
                                    exit;
                                    return false;
                    }
    }

            
        }// order

    } // End of function isAccessAction

/**
 * 函数说明: 获取分页显示代码
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int num 记录数
 * @param int perpage 每页显示数量
 * @param int curr_page 当前页数
 * @param string mpurl 分页地址
 * @return string 
 * @css:

ul.multipage{
    list-style:none;
}
ul.multipage li{
    line-height:24px;
    display:inline;
    color:#0291d9;
    margin:0 3px;
}

ul.multipage li span{
    border:1px #1B9EDD solid;
    font-size:11px;
    font-weight:bold;
    color:#FFF;
    text-decoration:none;
    text-align:center;
    padding:2px 3px;
    background:#0291d9;
}

ul.multipage li a{
    font-size:11px;
    color:#0291d9;
    padding:2px 3px;
    border:1px #8BD1EE solid;
    text-align:center;
    text-decoration:none;
    background:#fff;
}

ul.multipage li a:hover{
    background:#0291d9;
    color:#fff;
    padding:2px 3px;
    border:1px #0291d9 solid;
    font-weight:bold;
}

 */
function getMultiPage($num, $perpage, $curr_page, $mpurl,$rpmnt='&page=',$rtrm='& '){
                if($num > $perpage){
                                $page = 10;
                                $offset = 2;
                                $pages = ceil($num / $perpage); //get pages
                                $curr_page<1 && $curr_page=1;
                                $curr_page>$pages && $curr_page=$pages;
                                $from = $curr_page - $offset; //minus offset
                                $to = $curr_page + $page - $offset - 1;
                                if($page > $pages){
                                                $from = 1;
                                                $to = $pages;
                                }else{
                                                if($from < 1){
                                                                $to = $curr_page + 1 - $from;
                                                                $from = 1;
                                                                if(($to - $from) < $page && ($to - $from) < $pages){
                                                                                $to = $page;
                                                                }
                                                }elseif($to > $pages){
                                                                $from = $curr_page - $pages + $to;
                                                                $to = $pages;
                                                                if(($to - $from) < $page && ($to - $from) < $pages){
                                                                                $from = $pages - $page + 1;
                                                                }
                                                }
                                }
                                $mpstr = isset($rtrm)?rtrim($mpurl,$rtrm):$mpurl;
                                $fwd_back = '';
                                $fwd_back .= '<ul class="multipage">';
                                if($curr_page>1){
                                    $fwd_back .= '<li class="multipage_first"><a href="'.$mpstr.$rpmnt.'1">|&lt;</a></li>';
                                    $fwd_back .= '<li class="multipage_prev"><a href="'.$mpstr.$rpmnt.($curr_page-1).'">&lt;</a></li>';
                                }
                                for($i = $from; $i <= $to; $i++){
                                    if($i != $curr_page){
                                        $fwd_back .= '<li class="multipage_num"><a href="'.$mpstr.$rpmnt.$i.'">'.$i.'</a></li>';
                                    }else{
                                        $fwd_back .= '<li class="multipage_cur"><span>'.$i.'</span></li>';
                                    }
                                }
                                if($pages > $page){
                                    if($curr_page!=$pages){
                                        $fwd_back .= '<li class="multipage_ellip">...</li>';
                                        $fwd_back .= '<li class="multipage_next"><a href="'.$mpstr.$rpmnt.($curr_page+1).'">&gt;</a></li>';
                                        $fwd_back .= '<li class="multipage_last"><a href="'.$mpstr.$rpmnt.$pages.'">'.$pages.'&gt;|</a></li>';
                                    }
                                }else{
                                    if($curr_page!=$pages){
                                        $fwd_back .= '<li class="multipage_next"><a href="'.$mpstr.$rpmnt.($curr_page+1).'">&gt;</a></li>';
                                        $fwd_back .= '<li class="multipage_last"><a href="'.$mpstr.$rpmnt.$pages.'">&gt;|</a></li>';
                                    }
                                }
                                $fwd_back .= '<li class="multipage_stats"><span>'.$curr_page.'/'.$pages.' ('.$num.')</span></li>';
                                $fwd_back .= '</ul>';
                                $multipage = $fwd_back;
                                return $multipage;
                }
}// end of function getMultiPage
    
    
    
    /**
     * page redirect
     * 
     * @name redirect
     * @author nickfan<nickfan81@gmail.com> 
     * @last nickfan<nickfan81@gmail.com>
     * @update 2006/01/06 13:41:47
     * @version 0.1
     * @method Multi Params:
     * @param string $url (default blank page)
     * @param string $method header/refresh/location/page (default=refresh)
     * @param string $frame blank/top/self/parent/[userdefine] (default=self)
     */
    public static function respRedirect(){
                    $thisargs = func_get_args();
                    $thisurl = isset($thisargs[0])?$thisargs[0]:(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"about:blank");
                    $thismethod = isset($thisargs[1])?$thisargs[1]:'refresh';
                    $thistime = isset($thisargs[2])?intval($thisargs[2]):0;
                    $thisframe = isset($thisargs[3]) && is_string($thisargs[3]) && ($thisargs[3] != '' || !empty($thisargs[3]))?$thisargs[3]:'self';
                    $thistarget = in_array($thisframe, array('blank', 'top', 'self', 'parent'))?"_" . $thisframe:$thisframe;
                    switch($thismethod){
                                    case 'header':
                                                    header("Location:" . $thisurl);
                                                    break;
                                    case 'refresh':
                                                    echo "<meta http-equiv=\"Window-target\" content=\"" . $thistarget . "\"><meta http-equiv=\"Refresh\" content=\"" . $thistime . "; url=" . $thisurl . "\">";
                                                    break;
                                    case 'location':
                                                    echo "<script type=\"text/javascript\">top.window['" . $thisframe . "'].location.href='" . $thisurl . "';</script>";
                                                    break;
                                    case 'page':
                                                    echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $thisurl . "';</script>";
                                                    break;
                                    default:
                                                    echo "<meta http-equiv=\"Window-target\" content=\"" . $thistarget . "\"><meta http-equiv=\"Refresh\" content=\"" . $thistime . "; url=" . $thisurl . "\">";
                    }
    }

    /**
     * 函数说明: 截取字符串
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string title 字符串
     * @param int length 截取长度
     * @param string etc 截取后附加字符
     * @param string enc 字符编码
     * @return string 
     */
    public static function subString($str = '', $start = 0, $length = 0, $enc = 'utf-8', $etc = '...'){
                    if ($length == 0)
                                    return '';
                    $enc = strtolower($enc);
                    $enc_length = $enc == 'utf-8'?3:2;
    
                    if(extension_loaded('mbstring')){
                                    $newstr = mb_substr($str, $start, $length, $enc);
                                    $strlen = mb_strlen($str, $enc);
                                    $newstrlen = mb_strlen($newstr, $enc);
                    }elseif($enc == 'utf-8'){
                                    $strlen = strlen($str);
    
                                    $r = array();
                                    $n = 0;
                                    $m = 0;
                                    for($i = 0; $i < $strlen; $i++){
                                                    $x = substr($str, $i, 1);
                                                    $a = base_convert(ord($x), 10, 2);
                                                    $a = substr('00000000' . $a, -8);
                                                    if ($n < $start){
                                                                    if (substr($a, 0, 1) == 0){
                                                                    }elseif (substr($a, 0, 3) == 110){
                                                                                    $i += 1;
                                                                    }elseif (substr($a, 0, 4) == 1110){
                                                                                    $i += 2;
                                                                    }
                                                                    $n++;
                                                    }else{
                                                                    if (substr($a, 0, 1) == 0){
                                                                                    $r[] = substr($str, $i, 1);
                                                                    }elseif (substr($a, 0, 3) == 110){
                                                                                    $r[] = substr($str, $i, 2);
                                                                                    $i += 1;
                                                                    }elseif (substr($a, 0, 4) == 1110){
                                                                                    $r[] = substr($str, $i, 3);
                                                                                    $i += 2;
                                                                    }else{
                                                                                    $r[] = '';
                                                                    }
                                                                    if (++$m >= $length){
                                                                                    break;
                                                                    }
                                                    }
                                    }
                                    $newstr = implode('', $r);
                                    $newstrlen = strlen($newstr);
                    }else{
                                    $string = "";
                                    $count = 1;
                                    $strlen = strlen($str);
                                    for ($i = 0; $i < $strlen; $i++){
                                                    if (($count + 1 - $start) > $length){ 
                                                                    // $str  .= "...";
                                                                    break;
                                                    }elseif ((ord(substr($str, $i, 1)) <= 128) && ($count < $start)){
                                                                    $count++;
                                                    }elseif ((ord(substr($str, $i, 1)) > 128) && ($count < $start)){
                                                                    $count = $count + 2;
                                                                    $i = $i + $enc_length-1;
                                                    }elseif ((ord(substr($str, $i, 1)) <= 128) && ($count >= $start)){
                                                                    $string .= substr($str, $i, 1);
                                                                    $count++;
                                                    }elseif ((ord(substr($str, $i, 1)) > 128) && ($count >= $start)){
                                                                    $string .= substr($str, $i, $enc_length);
                                                                    $count = $count + 2;
                                                                    $i = $i + $enc_length-1;
                                                    }
                                    }
                                    $newstr = $string;
                                    $newstrlen = strlen($newstr);
                    }
                    return ($strlen > $newstrlen) ? $newstr . $etc : $str;
    }

    /*
     * 64们机和32位机兼容的ip2long
     */
    public static function myip2long($strIP)
    {
        $longIP=ip2long($strIP);
        if ($longIP < 0){
            $longIP += 4294967296;
        }
        return $longIP;
    }


    /*
     * 得到服务器上的长整型IP
     */
    public static function get_long_ip()
    {
        $ip     = Input::instance()->ip_address();
        return self::myip2long($ip);
    }


    /*
     * 根据IP得到商业数据库里面的IP详情
     */

    public static function get_ip_country($ip)
    {
        $ip_arr = array();
        $ip_arr = @unserialize(stripcslashes(@file_get_contents("http://ip.backstage-gateway.com/ip?ip=$ip")));
        if(isset($ip_arr['country']))
        {
            return $ip_arr['country'];
        }
        else
        {
            return NULL;
        }
    }

    /*
     * curl模拟post
     */
    public static function curl_pay($API_Endpoint,$nvpStr)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpStr);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            $curl_error_no  =curl_errno($ch) ;
            $curl_error_msg =curl_error($ch);
        }else {
            curl_close($ch);
        }
        return $response;
    }


    /*
     * 安全过滤字符串,在添加入数据训之前用
     */
    public static function filter_keywords($keywords)
    {
        $keyowrds           = strip_tags($keywords);

        $search             = array('http://','www.','www');
        $keyowrds           = str_replace($search,"",$keywords);
        return trim($keyowrds);
    }

    /*
     *tiffany解密链接
     */
    public static function query_decode($sEncode){
        if(strlen($sEncode)==0){
            return '';
        }else{
            $s_tem = strrev($sEncode);
            $s_tem = base64_decode($s_tem);
            $s_tem = rawurldecode($s_tem);
            $vcode=substr($s_tem,6,7);
            $s_tem=substr($s_tem,14);
            $a_tem = explode('&', $s_tem);
            $hash='id8ap';
            $verifyCode='';
            foreach($a_tem as $rs){
                $verifyCode.=$hash.$rs;
            }
            $verifyCode=substr(md5($verifyCode),3,7);
            if($verifyCode==$vcode){
              return $s_tem;
            }else{
                return '';
            }
        }
    }

    /**
     * 根据键值求二维数组的交集
     *
     * @param <Array> $array1
     * @param <Array> $array2
     *
     * @return Array
     */
    function array_common($array1,$array2,$compare_string = 'id') 
    {
        if (!is_array($array1) || !is_array($array2))
        {
            return false;
        }
        $compare_arr = array();
        foreach($array1 as $value)
        {
            $compare_arr[] = $value[$compare_string];
        }

        $arr_result = array();
        foreach ($array2 as $value)
        {
            if(in_array($value[$compare_string],$compare_arr))
            {
                $arr_result[] = $value;
            }
        }
        return $arr_result;
    }

    /**
     * 二维数组相差，根据ID
     * @param <Array> $source_array
     * @param <Array> $target_array
     *
     * @return Array
     */
    public static function my_array_diff($source_array = array(),$target_array = array())
    {
        if(count($target_array))
        {
            $id_arr = array();
            $result_arr = array();

            foreach($target_array as $key=>$value)
            {
                $id_arr[] = $value['id'];
            }

            foreach($source_array as $key=>$value)
            {
                if(!in_array($value['id'],$id_arr))
                {
                    $result_arr[] = $value;
                }
            }
            return $result_arr;
        }
        else
        {
            return $source_array;
        }
    }

    /**
     * 简化输出数组
     * @param $item
     * @param $key
     * @param $requestkeys
     * @example @array_walk($result_arr,'util::simplify_return_array',$requestkeys);
     */
    public static function simplify_return_array(&$item,$key,$requestkeys){
       $diffkeys = array_diff(array_keys($item),$requestkeys);
       foreach ($diffkeys as $diffkey){
            unset($item[$diffkey]);
       }
    }

    /**
     * 过滤数组，保留指定键值列表
     * @param array $array
     * @param array $requestkeys
     * @example tool::filter_keys（$array,$requestkeys);
     */
    public static function filter_keys(&$array,$requestkeys){
       foreach ($array as $key=>$val){
           if(!in_array($key,$requestkeys)){
               unset($array[$key]);
           }
       }
    }

    /**
     * 返回客户端IP地址
     * 
     * @return string
     */
    public static function getClientIP() {
        global $_SERVER;
        if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
            $onlineip = getenv ( 'HTTP_CLIENT_IP' );
        } elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
            $onlineip = getenv ( 'HTTP_X_FORWARDED_FOR' );
        } elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
            $onlineip = getenv ( 'REMOTE_ADDR' );
        } elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
            $onlineip = $_SERVER ['REMOTE_ADDR'];
        }
        $onlineip = preg_replace ( "/^([\d\.]+).*/", "\\1", $onlineip );
        preg_match ( "/[\d\.]{7,15}/", $onlineip, $match );
        $onlineip = $match [0] ? $match [0] : '0.0.0.0';
        return $onlineip;
    }

    /**
     * 判断是否为ajax请求
     */
    public static function isAjaxRequest(){
        global $_SERVER;
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * 返回一个GUID
     * 
     * @return string
     */
    public static function reGUID() {
        list ( $usec, $sec ) = explode ( " ", microtime () );
        $curtm = $sec . substr ( $usec, 2, 3 );
        $svname = isset ( $_ENV ['COMPUTERNAME'] ) ? $_ENV ['COMPUTERNAME'] : 'localhost';
        $svip = isset ( $_SERVER ['SERVER_ADDR'] ) ? $_SERVER ['SERVER_ADDR'] : '127.0.0.1';
        $tmp = rand ( 0, 1 ) ? '-' : '';
        $randstr = $tmp . rand ( 1000, 9999 ) . rand ( 1000, 9999 ) . rand ( 1000, 9999 ) . rand ( 100, 999 ) . rand ( 100, 999 );
        $cstr = $svname . '/' . $svip . ':' . $curtm . ':' . $randstr;
        $md5cstr = strtolower ( md5 ( $cstr ) );
        return substr ( $md5cstr, 0, 8 ) . '-' . substr ( $md5cstr, 8, 4 ) . '-' . substr ( $md5cstr, 12, 4 ) . '-' . substr ( $md5cstr, 16, 4 ) . '-' . substr ( $md5cstr, 20 );
    }
    

    /**
     * 函数说明: 构建查询字符串
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2007-12-03 樊振兴 添加了本方法
     * @param       array $reqstruct 请求数组
     * @param       array/null $upstruct 更新用的数组
     * @return      string
     */
    public static function buildQstr($reqstruct,$upstruct=NULL) {
        $qstruct=$reqstruct;
        if(!empty($upstruct)){
            $qstruct=array_merge($qstruct,$upstruct);
        }
        $qstr='';
        foreach($qstruct as $qkey=>$qrow){
            if(is_array($qrow)){
                foreach($qrow as $qrval){
                    $qstr.='&'.$qkey.'[]='.urlencode($qrval);
                }
            }else{
                    $qstr.='&'.$qkey.'='.urlencode($qrow);
            }
        }
        $qstr=ltrim($qstr,'&');
        return $qstr;
    }

    /**
     * 函数说明: 判断是否已发送指定名称域的文件
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     * @param       string item 文件域的名称(id/name)
     * @return      bool
     */
    public static function issetFile($item){
                    if(isset($_FILES[$item]) && !empty($_FILES[$item]['name'])){
                                    if(!is_array($_FILES[$item]['name'])){
                                                    return true;
                                    }else{
                                                    $isset = false;
                                                    for($i = 0;$i < count($_FILES[$item]['name']);$i++){
                                                                    if(!empty($_FILES[$item]['name'][$i])){
                                                                                    $isset = true;
                                                                    }
                                                    }
                                                    return $isset;
                                    }
                    }else{
                                    return false;
                    }
    }

    /**
     * 函数说明: 返回一个随机数
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @return int 
     */
    public static function reRandNum(){
                    $authnum;
                    srand((double)microtime() * 1000000);
                    while(($authnum = rand() % 10000) < 1000);
                    return $authnum;
    }
    
    /**
     * 函数说明: 返回一个随机hash字符串(长度32)
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @return string 
     */
    public static function reRandToken(){
                    srand((double)microtime() * 1000000);
                    return md5(uniqid(rand(), true));
    }

    /**
     * 
     * @name hex2Bin
     * @author author<author@example.com> 
     * @last author<author@example.com>
     * @update 2006/01/06 13:41:47
     * @version 0.1
     * @param data $data 
     * @return data $newdata
     */
    public static function hex2bin($data){
                    $len = strlen($data);
                    for($i = 0;$i < $len;$i += 2){
                                    $newdata .= pack("C", hexdec(substr($string, $i, 2)));
                    }
                    return $newdata;
    }

    /**
     * 函数说明: 返回一个指定长度的随机字符串
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param int len 字符串长度
     * @return string 
     */
    public static function reRandStr($len = 3){
                    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    mt_srand((double)microtime() * 1000000 * getmypid());
                    $outstr = "";
                    while(strlen($outstr) < $len)
                    $outstr .= substr($chars, (mt_rand() % strlen($chars)), 1);
                    return $outstr;
    } // end of function reRandStr

    /**
     * 函数说明: 经过javascript的escape函数转编码的字符串还原
     * @author      樊振兴(nick)<nickfan81@gmail.com>
     * @history
     *              2006-08-25 樊振兴 添加了本方法
     * @param       string str 被javascript escape过的字符串
     * @param       string charset unescape后目标字符的编码格式默认utf-8
     * @return      string 被解码的字符串
     */
    public static function unEscape($str, $charset = 'UTF-8'){
                    $charset = strtoupper($charset);
                    $str = rawurldecode($str);
                    preg_match_all("/(?:%u.{4})|.+/", $str, $r);
                    $ar = $r[0];
                    foreach($ar as $k => $v){
                                    if(substr($v, 0, 2) == "%u" && strlen($v) == 6)
                                                    $ar[$k] = iconv("UCS-2", $charset, pack("H4", substr($v, -4)));
                    }
                    return join("", $ar);
    }

    
    /**
     * 函数说明: 截取文件Mime类型
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @param int index 如果是多文件则获取指定索引的文件的Mime类型
     * @return string /bool(false)
     */
    public static function getFileType($field , $index = 0){
                    if(isset($_FILES[$field]) && !empty($_FILES[$field]['type'])){
                                    if(!is_array($_FILES[$field]['type'])){
                                                    switch($_FILES[$field]['type']){
                                                                    case 'image/gif':
                                                                                    return 'gif';
                                                                    case 'image/jpeg':
                                                                                    return 'jpg';
                                                                    case 'image/png':
                                                                                    return 'png';
                                                                    case 'image/bmp':
                                                                                    return 'bmp';
                                                                    case 'image/x-portable-pixmap':
                                                                                    return 'ppm';
                                                                    case 'image/x-png':
                                                                                    return 'png';
                                                                    case 'image/pjpeg':
                                                                                    return 'jpg';
                                                                    case 'image/tiff':
                                                                                    return 'tif';
                                                                    case 'image/x-icon':
                                                                                    return 'ico';
                                                                    case 'image/svg+xml':
                                                                                    return 'svg';
                                                                    case 'image/vnd.wap.wbmp':
                                                                                    return 'wbmp';
                                                                    case 'application/x-shockwave-flash':
                                                                                    return 'swf';
                                                                    case 'text/plain':
                                                                                    return 'txt';
                                                                    case 'text/css':
                                                                                    return 'css';
                                                                    case 'text/html':
                                                                                    return 'html';
                                                                    case 'application/xml':
                                                                                    return 'xml';
                                                                    case 'application/zip':
                                                                                    return 'zip';
                                                                    case 'application/rar':
                                                                                    return 'rar';
                                                                    case 'text/vnd.wap.wml':
                                                                                    return 'wml';
                                                                    case 'application/xhtml+xml':
                                                                                    return 'html';
                                                                    case 'application/xslt+xml':
                                                                                    return 'xslt';
                                                                    case 'application/xml-dtd':
                                                                                    return 'dtd';
                                                                    case 'application/rdf+xml':
                                                                                    return 'rdf';
                                                                    case 'application/msword':
                                                                                    return 'doc';
                                                                    case 'application/x-gzip':
                                                                                    return 'gz';
                                                                    case 'application/x-tar':
                                                                                    return 'tar';
                                                                    case 'audio/midi':
                                                                                    return 'mid';
                                                                    case 'audio/mpeg':
                                                                                    return 'mp3';
                                                                    case 'audio/x-wav':
                                                                                    return 'wav';
                                                                    case 'application/ogg':
                                                                                    return 'ogg';
                                                                    case 'video/mpeg':
                                                                                    return 'mpg';
                                                                    case 'video/quicktime':
                                                                                    return 'mov';
                                                                    case '':
                                                                                    return false;
                                                                    default:
                                                                                    return false;
                                                    }
                                    }else{
                                                    switch($_FILES[$field]['type'][$index]){
                                                                    case 'image/gif':
                                                                                    return 'gif';
                                                                    case 'image/jpeg':
                                                                                    return 'jpg';
                                                                    case 'image/png':
                                                                                    return 'png';
                                                                    case 'image/bmp':
                                                                                    return 'bmp';
                                                                    case 'image/x-portable-pixmap':
                                                                                    return 'ppm';
                                                                    case 'image/x-png':
                                                                                    return 'png';
                                                                    case 'image/pjpeg':
                                                                                    return 'jpg';
                                                                    case 'image/tiff':
                                                                                    return 'tif';
                                                                    case 'image/x-icon':
                                                                                    return 'ico';
                                                                    case 'image/svg+xml':
                                                                                    return 'svg';
                                                                    case 'image/vnd.wap.wbmp':
                                                                                    return 'wbmp';
                                                                    case 'application/x-shockwave-flash':
                                                                                    return 'swf';
                                                                    case 'text/plain':
                                                                                    return 'txt';
                                                                    case 'text/css':
                                                                                    return 'css';
                                                                    case 'text/html':
                                                                                    return 'html';
                                                                    case 'application/xml':
                                                                                    return 'xml';
                                                                    case 'application/zip':
                                                                                    return 'zip';
                                                                    case 'application/rar':
                                                                                    return 'rar';
                                                                    case 'text/vnd.wap.wml':
                                                                                    return 'wml';
                                                                    case 'application/xhtml+xml':
                                                                                    return 'html';
                                                                    case 'application/xslt+xml':
                                                                                    return 'xslt';
                                                                    case 'application/xml-dtd':
                                                                                    return 'dtd';
                                                                    case 'application/rdf+xml':
                                                                                    return 'rdf';
                                                                    case 'application/msword':
                                                                                    return 'doc';
                                                                    case 'application/x-gzip':
                                                                                    return 'gz';
                                                                    case 'application/x-tar':
                                                                                    return 'tar';
                                                                    case 'audio/midi':
                                                                                    return 'mid';
                                                                    case 'audio/mpeg':
                                                                                    return 'mp3';
                                                                    case 'audio/x-wav':
                                                                                    return 'wav';
                                                                    case 'application/ogg':
                                                                                    return 'ogg';
                                                                    case 'video/mpeg':
                                                                                    return 'mpg';
                                                                    case 'video/quicktime':
                                                                                    return 'mov';
                                                                    case '':
                                                                                    return false;
                                                                    default:
                                                                                    return false;
                                                    }
                                    }
                    }else{
                                    return false;
                    }
    }
    
    public static function getImageType($srcFile) {
        $data = @GetImageSize($srcFile);
    
        if($data===false){
            return false;
        }else{
            switch($data[2]){
                    case 1:
                            return 'gif';
                            break;
                    case 2:
                            return 'jpg';
                            break;
                    case 3:
                            return 'png';
                            break;
                    case 4:
                            return 'swf';
                            break;
                    case 5:
                            return 'psd';
                            break;
                    case 6:
                            return 'bmp';
                            break;
                    case 7:
                            return 'tiff';
                            break;
                    case 8:
                            return 'tiff';
                            break;
                    case 9:
                            return 'jpc';
                            break;
                    case 10:
                            return 'jp2';
                            break;
                    case 11:
                            return 'jpx';
                            break;
                    case 12:
                            return 'jb2';
                            break;
                    case 13:
                            return 'swc';
                            break;
                    case 14:
                            return 'iff';
                            break;
                    case 15:
                            return 'wbmp';
                            break;
                    case 16:
                            return 'xbm';
                            break;
                    default:
                                    return false;
    
            }
        }
    }
    /**
     * 函数说明: 截取文件后缀名
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @param int index 如果是多文件则获取指定索引的文件的后缀名
     * @return string /bool(false)
     */
    public static function getPostfix($field, $index = 0){
                    if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
                                    if(!is_array($_FILES[$field]['name'])){
                                                    $file_name = $_FILES[$field]['name'];
                                                    $point_pos = strrpos($file_name, '.');
                                                    if($point_pos !== false){
                                                                    return substr($file_name, $point_pos + 1);
                                                    }else{
                                                                    return '';
                                                    }
                                    }else{
                                                    $file_name = $_FILES[$field]['name'][$index];
                                                    $point_pos = strrpos($file_name, '.');
                                                    if($point_pos !== false){
                                                                    return substr($file_name, $point_pos + 1);
                                                    }else{
                                                                    return '';
                                                    }
                                    }
                    }else{
                                    return false;
                    }
    }
    
    /**
     * 函数说明: 获取上传文件大小
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @return int 
     */
    public static function getFileSizeTotal($field){
                    if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
                                    if(!is_array($_FILES[$field]['name'])){
                                                    return sprintf("%u", filesize($_FILES[$field]['tmp_name']));
                                    }else{
                                                    $total = 0;
                                                    for($i = 0;$i < count($_FILES[$field]['name']);$i++){
                                                                    $total += filesize($_FILES[$field]['tmp_name'][$i]);
                                                    }
                                                    return $total;
                                    }
                    }else{
                                    return false;
                    }
    }
    
    /**
     * 函数说明: 获取上传文件个数
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param string field 文件域名称
     * @return int 
     */
    public static function getFileCount($field){
                    if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
                                    if(!is_array($_FILES[$field]['name'])){
                                                    return 1;
                                    }else{
                                                    $total = 0;
                                                    for($i = 0;$i < count($_FILES[$field]['name']);$i++){
                                                                    if(!empty($_FILES[$field]['name'][$i])){
                                                                                    $total++;
                                                                    }
                                                    }
                                                    return $total;
                                    }
                    }else{
                                    return false;
                    }
    }
    
    /**
     * 函数说明: 文件字节显示字符串
     * 
     * @author 樊振兴(nick)<nickfan81@gmail.com> 
     * @history 2006-08-25 樊振兴 添加了本方法
     * @param int filesize 文件字节数
     * @return string 
     */
    public static function getSizeDisp($filesize){
                    if($filesize >= 1073741824){
                                    //$filesize = round($filesize / 1073741824 * 100) / 100 . " GB";
                                    return sprintf("%.2fGB", $filesize/1073741824);
                    }elseif($filesize >= 1048576){
                                    //$filesize = round($filesize / 1048576 * 100) / 100 . " MB";
                                    return sprintf("%.2fMB", $filesize/1048576);
                    }elseif($filesize >= 1024){
                                    //$filesize = round($filesize / 1024 * 100) / 100 . " KB";
                                    return sprintf("%.2fKB", $filesize/1024);
                    }else{
                                    return $filesize . " Bytes";
                    }
    }
    
}
