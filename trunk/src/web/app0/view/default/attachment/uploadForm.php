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
    <div id="bd">
        <ul class="navBar ui-widget-content ui-corner-all"><li>&#187 <a href="/attachment/uploadForm" title="上传数据">上传数据</a></li></ul>
        <div id="respTips" class="ui-corner-all"><?php isset($returnStruct['msg']) && print($returnStruct['msg']);?></div>
        <p>
        <?php if(isset($returnStruct)&& isset($returnStruct['content'])){
            $returnData = $returnStruct['content'];
            if(isset($returnData['attach']) && !empty($returnData['attach'])){
                ?><ul><?php
                foreach($returnData['attach'] as $attachId){
                    ?><li><a href="/attachment/get/<?php echo $attachId;?>" title="view attachment" class="ui-button ui-state-default ui-corner-all" target="_blank">[Attachment <?php echo $attachId;?>]</a> <a href="/attachment/delete?id=<?php echo $attachId;?>" title="remove attachment" class="ui-button ui-state-default ui-corner-all">[X]</a></li><?php 
                }
                ?></ul><?php
            }
        }?>
        </p>
        <form action="/attachment/uploadForm" method="POST" enctype="multipart/form-data">
<ul>
    <li>
        Attachment:
        <input id="addAttach" type="button" value="Add Attachment" class="ui-button ui-state-default ui-corner-all" />
        <ul id="attachmentContainer">
        </ul>
    </li>
    <li>
        <input name="commit" type="submit" class="ui-button ui-state-default ui-corner-all" />
    </li>
</ul>

</form>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
/* document base */
urlBase = '<?php echo url::base();?>';

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

    $("#addAttach").unbind().bind('click keyup',function(e){
        $("#attachmentContainer").append('<li><input name="myattach[]" type="file" class="ui-button ui-state-default ui-corner-all" /> <a class="cancelAttachmentField ui-button ui-state-default ui-corner-all" href="#">[X]</a></li>');
        if(e){ e.preventDefault();}
        return false;
    });
    $("a.cancelAttachmentField").live('click keyup',function(e){
        $(this).parent().remove();
        if(e){ e.preventDefault();}
        return false;
    });

});
//]]>
</script>