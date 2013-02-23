<?php
$ctrl = isset($_GET['ctrl']) ? strtolower(trim($_GET['ctrl'])) : 'index';
$act = isset($_GET['act']) ? trim($_GET['act']) : 'index';


//权限判断
$result = has_purview($ctrl, $act);
switch ($result){
    case 1:
        break;
    case -1:
        header("Location:".LOGIN_URL);//未登录，跳转登录页面
        exit();
    case -2:
        exit('不可访问的ip');
        break;
    default:
        exit('未知错误');
        break;
}

require_once ROOT."/control/{$ctrl}.php";
$control = new control();


if (method_exists($control, $act)) {
    $control->$act();
} else {
    throw new Exception("Not exists function $act() in class $ctrl");
}

router_destruct();
function router_destruct(){
    global $mysql_link;
    foreach($mysql_link as $link){
        $link->close();
        unset($link);
    }
    unset($mysql_link);
}



