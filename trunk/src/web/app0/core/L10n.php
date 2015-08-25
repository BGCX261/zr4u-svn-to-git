<?php

//TODO 需要更标准化的设置方式，目前阶段直接使用统一设定方式，随后精细化设计。
//$domain = Lemon::config('locale.domain');
//$lang = Lemon::config('locale.lang');
//$charset = Lemon::config('locale.charset');

function setL10n($domain='default',$lang='zh_CN',$charset='UTF-8'){
    putenv("LANGUAGE=$lang");
    putenv("LANG=$lang");
    //setlocale(LC_MESSAGES,'');
    setlocale(LC_ALL,$lang.'.'.$charset);
    bindtextdomain($domain,APP_PATH.'locale/');
    bind_textdomain_codeset($domain , $charset);
    textdomain($domain);
}
//setL10n();
setL10n(Lemon::config('locale.domain'),Lemon::config('locale.lang'),Lemon::config('locale.charset'));