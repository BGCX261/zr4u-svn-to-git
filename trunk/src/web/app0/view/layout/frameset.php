<?php
/**
 * 默认框架页view
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<?php isset($addonCssLinkContext) && print($addonCssLinkContext);?>
<?php isset($addonJsLinkContext) && print($addonJsLinkContext);?>
  <title><?php isset($title) && print($title);?> - <?php isset($slogan) && print($slogan);?></title>
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
<frameset rows="60,*" cols="*" frameborder="yes" border="1" framespacing="1" bordercolor="#000000">
    <frame src="<?php echo url::base();?>manage/taskbar" name="taskbar" id="taskbar" title="taskbar" frameborder="no" scrolling="no" border="0" noresize="noresize">
    <frameset name="frame" id="frame" rows="*" cols="180,*" frameborder="yes" border="1" framespacing="1" bordercolor="#000000" topmargin="0"  leftmargin="0" marginheight="0" marginwidth="0">
        <frame src="<?php echo url::base();?>manage/sidebar" name="sidebar" id="sidebar" title="sidebar" frameborder="no" scrolling="auto" topmargin="0" leftmargin="0" border="0" noresize="noresize">
        <frame src="<?php echo !empty($forward)?$forward:(url::base().'manage/workspace');?>" name="workspace" id="workspace" title="workspace" frameborder="no">
    </frameset>
</frameset>
<noframes>
<body>
need browser frameset support(frame)
</body>
</noframes>
</html>