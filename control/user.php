<?php
class control extends base{
	function __construct(){
		parent::__construct();
		$this->userClass = $this->load('user');
	}
	
	function change_password(){
		if(isset($_POST['change_password_submit'])){
	        $result = $this->userClass->changePassword($_POST);
	        switch ($result['code']){
	            case 1:
	                $msg = '修改成功';
	                break;
	            case -1:
	                $msg = '修改密码失败';
	                break;
	            case -2:
	                $msg = '原始密码错误';
	                break;
	            case -3:
	                $msg = '两次输入密码不相同';
	                break;
	            default:
	                $msg = '未知错误:'.$result['code'];
	        }
	    }
		$this->smarty->assign('msg', $msg);
		$this->smarty->display('user/change_password.tpl');
	}
	
	
}