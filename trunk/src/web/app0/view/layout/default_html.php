<?php
/**
 * 默认View
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

if(isset($resourceUpdateTimestamp) && !empty($resourceUpdateTimestamp)){
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s' , $resourceUpdateTimestamp) . ' GMT');
    if((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $resourceUpdateTimestamp) || (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $resourceUpdateTimestamp)){
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}
if(isset($resourceEtag) && !empty($resourceEtag)){
    header('Etag: ' . $resourceEtag);
    if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $resourceEtag == $_SERVER['HTTP_IF_NONE_MATCH']){
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

if(isset($resourceCacheTimeInterval)){
    if($resourceCacheTimeInterval==-1){
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }else{
        if($resourceCacheTimeInterval>0){
            header('Cache-control: max-age='.$resourceCacheTimeInterval);
        }
        if(isset($resourceExpiresTimestamp) && !empty($resourceExpiresTimestamp)){
            header('Expires: ' . gmdate('D, d M Y H:i:s', $resourceExpiresTimestamp) . ' GMT');
        }else{
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$resourceCacheTimeInterval) . ' GMT');
        }
    }
}

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<!--  <link type="text/css" href="http://res.zr4u.com/res/css/grids-min.css" rel="stylesheet">-->
<!--  <link type="text/css" href="http://res.zr4u.com/res/css/jquery/themes/cupertino/jquery-ui-1.8.custom.css" rel="stylesheet" />-->
<!--  <link type="text/css" href="http://res.zr4u.com/res/css/skeleton.css" rel="stylesheet" />-->
<!--  <link type="text/css" href="http://res.zr4u.com/res/css/main.css" rel="stylesheet" />-->
<!--<link type="text/css" href="http://res.zr4u.com/min/?g=csscommon" rel="stylesheet" />-->
<link type="text/css" href="http://res.zr4u.com/res/css/default.min.css" rel="stylesheet" />
<?php isset($addonCssLinkContext) && print($addonCssLinkContext);?>
<!--  <script type="text/javascript" src="http://res.zr4u.com/res/js/jquery/jquery-1.4.2.min.js"></script>-->
<!--  <script type="text/javascript" src="http://res.zr4u.com/res/js/jquery/plugins/jquery-ui-1.8.custom.min.js"></script>-->
<!--  <script type="text/javascript" src="http://res.zr4u.com/res/js/jquery/plugins/jquery.cookie.min.js"></script>-->
<!--  <script type="text/javascript" src="http://res.zr4u.com/res/js/common/common.min.js"></script>-->
<!--<script type="text/javascript" src="http://res.zr4u.com/min/?g=jscommon"></script>-->
<script type="text/javascript" src="http://res.zr4u.com/res/js/default.min.js"></script>
<?php isset($addonJsLinkContext) && print($addonJsLinkContext);?>
  <title><?php isset($title) && print($title);?></title>
<?php if(isset($addonCssContentContext)){
?>
<style type="text/css">
<!--
<?php echo $addonCssContentContext;?>
-->
</style>
<?php }//end of $addonCssContentContext?>
<?php if(isset($addonJsContentContext)){
?>
<script type="text/javascript">
//<![CDATA[
<?php echo $addonJsContentContext;?>
//]]>
</script>
<?php }//end of $addonJsContentContext?>
</head>
<body><?php echo $content; ?></body>
</html>