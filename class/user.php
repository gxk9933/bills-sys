<?php
class user{
	function __construct(&$base){
        $this->base = $base;
        $this->user_table = $base->mysqli;
        $this->user_table->setTableName('user');
    }
    
    function logout(){
    	unset($_SESSION['bills_uid'], $_SESSION['bills_username'], $_SESSION['bills_groupid'], $_SESSION['bills_admin'], $_SESSION['bills_login_time']);
    }
    
    function make_login_token(){
    	$_SESSION['bills_login_token'] = random(6);
    	return $_SESSION['bills_login_token'];
    }
    
	function getUserByNameAndPass($username, $password){
        $where = array('username' => $username, 'password' => $password);
        return $this->user_table->getOne($where);
    }
    
    function setLoginSession($user){
    	$now = $this->base->current_time;
    	
    	$_SESSION['bills_uid'] = $user['uid'];
    	$_SESSION['bills_username'] = $user['username'];
    	$_SESSION['bills_groupid'] = $user['groupid'];
    	$_SESSION['bills_admin'] = true;
    	$_SESSION['bills_login_time'] = $now;
    }
    
    function updateUserLoginData($uid){
    	$ip = get_ip();
    	$now = $this->base->current_time;
        $data = array(
            'uid' => $uid,
            'last_login_time' => $now,
            'last_login_ip' => $ip,
        );
        $this->user_table->update($data);
    }
    
    function do_login($username, $password){
        $now = $this->base->current_time;
    	if ($_POST['login_token'] != $_SESSION['bills_login_token']) {
            return 'can not login'; //不允许登录
        }
        if(strtolower($_POST['verification_code']) != strtolower($_SESSION['bills_verification_code'])){
            return 'verification code error';//验证码错误
        }
        
        $password = md5($password);
        $user = $this->getUserByNameAndPass($username, $password);
        
        
        if (empty($user)) { //用户名密码错误
            return 'user error';
        }

   		//登录成功，设置session
        $this->setLoginSession($user);
        
        //更新用户最后登录时间和ip
        $this->updateUserLoginData($user['uid']);
        return 'success'; //登录成功
    }
    
    function adminIsLogin(){
    	$now = $this->base->current_time;
        
        if ($_SESSION['bills_admin'] != true || ! $_SESSION['bills_login_time'] || $_SESSION['bills_login_time'] < $now - 43200 || !$_SESSION['bills_uid'] || !$_SESSION['bills_username']) {
            return false;
        }
        
        $user = $this->user_table->getOne(array('uid' => $_SESSION['bills_uid'], 'username' => $_SESSION['bills_username']));
        if(!user){
            return false;
        }
        return $user;
    }
    
    
    function has_purview($ctrl, $act){
    	$now = $this->base->current_time;
    	global $__global;
        $noPermissionPage = $__global['noPermissionPage'];
        
        $ctrl = $ctrl? $ctrl: 'index';
        $act = $act? $act: 'index';
        $permission = "{$ctrl}_{$act}";
        

        //如果是登录页面和显示验证码的页面则不需要权限，直接通过
        if(in_array($permission, $noPermissionPage)){
            return 1;
        }
        
        $user = $this->adminIsLogin();
        //未登录
        if(!$user){
            return -1;
        }
        
        
        $_SESSION['bills_login_time'] = $now;
        return 1;
    }
    
    function changePassword($data){
    	$now = $this->base->current_time;
        if($data['password'] != $data['password2'])
            return array('code' => -3);//两次密码输入不同
        
        
        $uid = $_SESSION['bills_uid'];
        $user = $this->user_table->getOneById($uid);
        if($user['password'] != md5($data['old_password'])){
            return array('code' => -2);
        }
        $data = array(
            'uid' => $uid,
            'password' => md5($data['password']),
        );
        return array('code' => $this->user_table->update($data));
    }
}