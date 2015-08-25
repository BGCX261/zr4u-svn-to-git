<?php
/**
 * 默认View
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

@ob_end_clean();

// 下载不缓存
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

header('Last-Modified: ' . gmdate('D, d M Y H:i:s' , $attachmentData['modifyTimestamp']) . ' GMT');
header('Content-type: application/octet-stream');
header('Content-Encoding: none');
header('Content-Transfer-Encoding: binary');
header('Content-Disposition: attachment; filename=' . $attachmentData['fileName']);
header('Content-Length: '.$attachmentData['fileSize']);
echo $storeData;
exit;