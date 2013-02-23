<?php
@define("DEFAULT_TIMEZONE", 'PRC');
@define('DEFAULT_PAGE_COUNT', 10);

@define("LOG_DIR", ROOT."/writable/log");
@define("LOGIN_URL", 'index.php?ctrl=index&act=login');


global $__global;
$__global['noPermissionPage'] = array('index_verification_code', 'index_login');