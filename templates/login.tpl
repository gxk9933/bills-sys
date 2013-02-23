<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>后台登录</title>
<style type="text/css">
body{font-size:12px;color:#555555;}
.wrap{background:url("style/images/login_bg.jpg") no-repeat scroll center 0 transparent;height: 580px;margin-left: auto;margin-right: auto;width: 800px;}
.head{padding:110px 0 0 255px;}
.logo{background:url("style/images/login_logo.png") no-repeat scroll left top transparent;height: 64px;overflow: hidden;width: 210px;}
.logo span{text-align:center;margin:10 5px;font-size:25px;font-weight:bolder;}
.login{padding:60px 0 0 170px;}
.login th {
  font-size: 14px;
  font-weight: normal;
  height: 35px;
  line-height: 31px;
  list-style: none outside none;
  padding-right: 5px;
  text-align: right;
  width: 190px;
}
.li{ padding:0 6px;vertical-align:middle; border-radius:4px;}
input {border: 1px solid #ABADB3;border-radius: 4px;color: #444;font-size: 14px; width:10em; height:1.4em;}
input[type="checkbox"],input[type="radio"]{border:none;}
input:focus {border: 1px solid #B7D4EA;box-shadow: 0 0 8px #B7D4EA; background:#EDF5F5;}

#login_button{background:#E8EFFC; border:#6C6C6C solid 1px; color:#000;height:26px;line-height:22px; cursor:pointer;}
#login_button:hover{background:#D6E3FA;box-shadow: 0 0 4px #B7D4EA;}

</style>
<script type="text/javascript" src="style/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#username').focus();
	$('#verification_code').click(function(){
		$(this).attr('src', 'index.php?ctrl=index&act=verification_code&'+Math.random());
	});
})
</script>
</head>
<body>
<div class="wrap">
  <div class="head">
    <div class="logo"></div>
  </div>
  <div class="login">
  <form action="<{$login_url}>" name="login" method="post">
  <input type="hidden" value="<{$login_token}>" name="login_token">
    <table cellspacing="0" cellpadding="0">
      <tbody><tr class="li">
        <th><label for="username">管理员账号:</label></th>
        <td><input type="text" name="username" id="username" style="width:150px;"></td>
      </tr>
      <tr class="li">
        <th><label for="password">密码:</label></th>
        <td><input type="password" name="password" id="password" style="width:150px;"></td>
      </tr>

      <tr class="li">
        <th><label for="verification_code">验证码:</label></th>
        <td><input type="text" name="verification_code" style="width:72px;"><img src="index.php?ctrl=index&act=verification_code" id="verification_code" title="点击更换验证码" style="margin-left:10px;position:relative;top:7px;*top:4px;"/></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input style="width:5em;" type="submit" id="login_button" value="登&nbsp;&nbsp;录"></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><font color="red"><{$msg}></font></td>
      </tr>
    </tbody></table>
  </form>
  </div>
</div>
</body>
</html>