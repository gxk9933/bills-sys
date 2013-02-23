<?php
class control extends base{
	function __construct(){
		parent::__construct();
		$this->userClass = $this->load('user');
	}
	
	function index(){
		$this->smarty->display('index.tpl');
	}
	
	function menu(){
		$this->smarty->display('menu.tpl');
	}
	
	function home(){
		
		$week_arr = array("天", '一', '二', '三', '四', '五', '六');
		$this->smarty->assign('username', $_SESSION['bills_username']);
		$this->smarty->assign('date', date('Y年m月d日')." 星期".$week_arr[date('w')]);
		$this->smarty->assign('root', ROOT);
		$this->smarty->assign('server_ip', $_SERVER['SERVER_ADDR']);
		$this->smarty->assign('remote_ip', $_SERVER['REMOTE_ADDR']);
		
		
		$this->smarty->display('home.tpl');
	}
	
	function login(){
		if(empty($_POST['login_token'])){
		    //打开登录页面
			$this->userClass->logout();
		}else{
		    //登录操作
		    $username = trim($_POST['username']);
		    $password = trim($_POST['password']);
	        $result = $this->userClass->do_login($username, $password);
	        switch ($result){
	            case 'can not login':
	                $msg = '不允许的登录';
	                break;
	            case 'user error':
	                $msg = '用户名密码错误';
	                break;
	            case 'verification code error':
	                $msg = '验证码错误';
	                break;
	            case 'success':
	                header('Location:index.php');
	                exit();
	                break;
	            default:
	                $msg = '未知登录错误'.$result;
	        }
		    
			
		}
		$login_token = $this->userClass->make_login_token();
		$this->smarty->assign('login_token', $login_token);
		$this->smarty->assign('msg', $msg);
		$this->smarty->display('login.tpl');
	}
	
	function verification_code(){
		require_once(ROOT.'/library/ValidationCode.class.php');
		$ValidationCode = new ValidationCode();
		$_SESSION['bills_verification_code'] = $ValidationCode->getCheckCode();
		$ValidationCode->outputImage();
	}
}