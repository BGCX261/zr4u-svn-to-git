<?php
/**
 * 默认View
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
?>
<div id="doc3">
    <div id="hd">
        <h1 class="ui-widget-content ui-corner-all"><a href="/" title="<?php echo Lemon::config('site.name');?>"><?php echo Lemon::config('site.name');?></a></h1>
    </div>
    <div id="bd">
        <ul class="navBar ui-widget-content ui-corner-all"><li>&#187 <a href="#" title="<?php isset($title) && print($title);?>"><?php isset($title) && print($title);?></a></li></ul>
        <div id="respTips" class="ui-corner-all"><?php isset($returnStruct['msg']) && print($returnStruct['msg']);?></div>
        <p>&nbsp;</p> 

        <p>&nbsp;</p>
    </div>
    <div id="ft">
        <p>-</p>
    </div>

</div>

<script type="text/javascript">
//<![CDATA[
/* response ui data */
var uiData = {
    'status': <?php isset($returnStruct['status']) && print($returnStruct['status']);?>,
    'message': '<?php isset($returnStruct['msg']) && print($returnStruct['msg']);?>',
    'trigger_tips': null,
    'style_tips':'<?php if(isset($returnStruct['status']) && $returnStruct['status']==1){ ?>ui-state-highlight<?php }else{?>ui-state-error<?php }?>'
};
function renderMessage(){
    uiMessage = (arguments.length>0)?arguments[0]:null;
    uiStatus = (arguments.length>1)?arguments[1]:null;
    if(uiMessage!==null){
        uiData['message'] = uiMessage;
    }
    if(uiStatus!==null){
        uiData['status'] = uiStatus;
    }
    /* tips effect */
    if(uiData['message']!=''){
        if(uiData['status']==1){
            uiData['style_tips'] = 'ui-state-highlight';
            tips_content = '<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span> '+uiData['message'];
        }else{
            uiData['style_tips'] = 'ui-state-error';
            tips_content = '<span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> '+uiData['message'];
        }
        $("#respTips").addClass(uiData['style_tips']).html(tips_content).effect("highlight",{},2000,function(){
                if(uiData['trigger_tips']!=null){
                    window.clearTimeout(uiData['trigger_tips']);
                }
                uiData['trigger_tips'] = window.setTimeout(function(){
                        $("#respTips").removeClass(uiData['style_tips']).empty().fadeOut('slow').hide();
                },1000);
            });
    }
}
/* document dom ready */
$(function() {
    /* tips effect */
    renderMessage();
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