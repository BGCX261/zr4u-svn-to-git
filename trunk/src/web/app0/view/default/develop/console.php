<?php
/**
 * 默认View
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */
?>
<div id="terminal_container"></div>
<script type="text/javascript">
//<![CDATA[
$('#terminal_container').height($(document).height());
$('#terminal_container').terminal(
		'/develop/console_service',
		{custom_prompt : "console&gt; ",
		 hello_message : 'Welcome! Type \'help\' for, help.'
		});
//]]>
</script>