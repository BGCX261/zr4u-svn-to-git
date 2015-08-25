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
    </div>
</div>
<script type="text/javascript">
//<![CDATA[

/* document dom ready */
$(function() {
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