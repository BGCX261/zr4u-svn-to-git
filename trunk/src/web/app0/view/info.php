<?php
/**
 * 消息交互
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

$renderStruct = array(
    'status'=>0,
    'code'=>501,
    'msg'=>'',
    'action'=>array(
        'url'=>request::referrer('about:blank'),
        'time'=>3,
        'type'=>'back',//header/back/close/page/location/stand
        'frame'=>'self',//self/blank/top/parent/[string]
        'script'=>'',
    ),

);
isset($returnStruct['status']) && $renderStruct['status'] = $returnStruct['status'];
isset($returnStruct['code']) && $renderStruct['code'] = $returnStruct['code'];
isset($returnStruct['msg']) && $renderStruct['msg'] = $returnStruct['msg'];
if(isset($returnStruct['action'])){
    isset($returnStruct['action']['url']) && $renderStruct['action']['url'] = $returnStruct['action']['url'];
    //empty($renderStruct['action']['url']) && $renderStruct['action']['url'] = request::referrer('about:blank');
    isset($returnStruct['action']['time']) && $renderStruct['action']['time'] = $returnStruct['action']['time'];
    isset($returnStruct['action']['type']) && $renderStruct['action']['type'] = $returnStruct['action']['type'];
    isset($returnStruct['action']['frame']) && $renderStruct['action']['frame'] = $returnStruct['action']['frame'];
    isset($returnStruct['action']['script']) && $renderStruct['action']['script'] = $returnStruct['action']['script'];
}
$renderStruct['action']['target'] = in_array($renderStruct['action']['frame'], array('blank', 'top', 'self', 'parent')) ? "_".$renderStruct['action']['frame'] : $renderStruct['action']['frame'];

//exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($renderStruct,TRUE)."\n</pre></div>");

$actionLinkText = Lemon::config('common.proceedLinkText');
$actionLinkContext = '';
$actionActionContext = '';

if($renderStruct['action']['type']=='header'){
    header("Location:" . $renderStruct['action']['url']);
    exit();
}
//elseif(in_array($renderStruct['action']['type'],array('location','close')))
switch($renderStruct['action']['type']){
    case 'location':
    case 'close':
        if($renderStruct['action']['frame']!='self'){
            if($renderStruct['action']['type']=='location'){
                $actionContextCurrent = $renderStruct['action']['script'].' '
                  .'top.window[\''.$renderStruct['action']['frame'].'\'].location.href=\''.$renderStruct['action']['url'].'\';';
            }elseif($renderStruct['action']['type']=='close'){
                $actionContextCurrent = $renderStruct['action']['script'].' '
                  .'top.window[\''.$renderStruct['action']['frame'].'\'].close();';
            }
        }else{
            if($renderStruct['action']['type']=='location'){
                $actionContextCurrent = $renderStruct['action']['script'].' '
                                  .'self.location.href=\''.$renderStruct['action']['url'].'\';';
            }elseif($renderStruct['action']['type']=='close'){
                $actionContextCurrent = $renderStruct['action']['script'].' '
                                  .'self.close();';
            }
        }
        $actionLinkContext = '<a href="javascript:'.$actionContextCurrent.'" '
                               .'name="action_current" id="action_current" '
                               .'target="'.$renderStruct['action']['target'].'"'
                               .'class="action_current ui-button ui-state-default ui-corner-all" '
                               .'>'.$actionLinkText.'</a>'
                               .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $actionActionContext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$actionContextCurrent.' },1000*'.$renderStruct['action']['time'].');</script>';
    break;
    case 'page':
            $actionContextCurrent = $renderStruct['action']['script'].' '
                .'var pageredirect=function(){ if (top.location !== self.location){top.location=self.location;} location.href = \''.$renderStruct['action']['url'].'\'; return ; }; pageredirect(); ';
            $actionLinkContext = '<a href="javascript:'.$actionContextCurrent.'" '
                                   .'name="action_current" id="action_current" '
                                   .'target="'.$renderStruct['action']['target'].'"'
                                   .'class="action_current ui-button ui-state-default ui-corner-all" '
                                   .'>'.$actionLinkText.'</a>'
                                   .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
            $actionActionContext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$actionContextCurrent.' },1000*'.$renderStruct['action']['time'].');</script>';
            break;
    case 'stand':
        $actionContextCurrent = $renderStruct['action']['script'].' ';
        if(!empty($renderStruct['action']['url'])){
            if($renderStruct['action']['frame']!='self'){
                $actionContextCurrent .= 'top.window[\''.$renderStruct['action']['frame'].'\'].location.href=\''.$renderStruct['action']['url'].'\';';
            }else{
                $actionContextCurrent .= 'self.location.href=\''.$renderStruct['action']['url'].'\';';
            }
        }
        $actionContextCurrent .= ';';
        $actionLinkContext = '<a href="javascript:'.$actionContextCurrent.'" '
                               .'name="action_current" id="action_current" '
                               .'target="'.$renderStruct['action']['target'].'"'
                               .'class="action_current ui-button ui-state-default ui-corner-all" '
                               .'>'.$actionLinkText.'</a>'
                               .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $actionActionContext = '';
        break;
    case 'back':
    default:
        $actionContextCurrent = $renderStruct['action']['script'].' '
                                  .'history.back();';
        $actionLinkContext = '<a href="javascript:'.$actionContextCurrent.'" '
                               .'name="action_current" id="action_current" '
                               .'target="'.$renderStruct['action']['target'].'"'
                               .'class="action_current ui-button ui-state-default ui-corner-all" '
                               .'>'.$actionLinkText.'</a>'
                               .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $actionActionContext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$actionContextCurrent.' },1000*'.$renderStruct['action']['time'].');</script>';
        break;
}

?>
<style type="text/css">
<!--
body,#bd,#doc3 { margin: 0; padding:0;}
body {height:100%;}

#info_floater    {
    position:relative; float:left;
    height:50%; margin-bottom:-120px;/* half of layout height */
    width:1px;
}
#info_layout    {
    position:relative; clear:left;
    height:240px; width:75%; max-width:480px; min-width:240px;
    margin:0 auto;
    background:#fff;
}
#info_container {
    position:absolute; left:0; right:0; top:0; bottom:0;
    overflow:auto; height:240px;
    padding:20px; margin:10px;
}
#title_layout {
	padding:10px;
}
#action_layout{
	padding:10px; margin:10px;
	text-align:center;
}
#action_layout ul li a{
	margin-bottom:12px; padding: .7em;
}
-->
</style>
<div id="doc3">
    <div id="bd">
        <div id="info_floater"></div><!-- end of div id info_floater -->
            <div id="info_layout">
                <div id="info_container" class="ui-widget-content ui-corner-all">
                    <p id="title_layout" class="ui-state-default ui-corner-all"><strong>响应信息:</strong></p>
                    <div id="message_layout" class="ui-widget">
                        <div id="respTips" class="<?php if($renderStruct['status']==1){?>ui-state-highlight<?php }else{?>ui-state-error<?php }?> ui-corner-all">
                        <?php
                            if($renderStruct['status']==1){
                                ?><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span><?php
                            }else{
                                ?><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span><?php
                            }
                        ?>
                        <?php echo $renderStruct['msg'];?></div><!-- end of div id respTips -->
                    </div><!-- end of div id message_layout -->
                    <div id="action_layout">
                        <ul><li><?php echo $actionLinkContext;?></li></ul>
                    </div>
                </div><!-- end of div id info_container -->
            </div><!-- end of div id info_layout -->
    </div><!-- end of id bd -->
</div>
<?php echo $actionActionContext;?>
<script type="text/javascript">
//<![CDATA[
/* response ui data */
var uiData = {
    'status': <?php echo $renderStruct['status'];?>,
    'message': '<?php echo $renderStruct['msg'];?>',
    'trigger_tips': null,
    'style_tips':'<?php if($renderStruct['status']==1){ ?>ui-state-highlight<?php }else{?>ui-state-error<?php }?>'
};

/* document dom ready */
$(function() {
    /* tips effect */
    if(uiData['message']!=''){
        $("#respTips").effect("highlight",{},2000);
    }
    /* back button */
    $("button[name='goback'],input[name='goback']").click(function(e){
        history.go(-1);
        if(e){ e.preventDefault(); }
        return false;
    });
    /* ui effect */
    $('.ui-state-default').hover(
            function(){
                $(this).addClass("ui-state-hover");
            },
            function(){
                $(this).removeClass("ui-state-hover");
            }
        ).mousedown(function(){
            $(this).addClass("ui-state-active");
        }).mouseup(function(){
            $(this).removeClass("ui-state-active");
    });
});
//]]>
</script>