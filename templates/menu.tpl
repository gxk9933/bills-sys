<html>
<head>
<script src="style/js/TaskMenu.js"></script>
<script>
var taskMenu1;
var taskMenu2;
var taskMenu3;
var taskMenu4;

var item1;
var item2;
var item3;
var item4;
var item5;
var item6;
var item7;
var item8;
TaskMenu.setStyle("style/css/Blue/BlueStyle.css"); 

window.onload = function()
{
	TaskMenu.setHeadMenuSpecial(true);
	//TaskMenu.setScrollbarEnabled(true);
	//TaskMenu.setAutoBehavior(false);
	////////////////////////////////////////////////
	item1 = new TaskMenuItem("管理首页","style/images/task_menu/demo.gif","parent.window.frames[1].location.href='index.php?ctrl=index&act=home'");

	item11 = new TaskMenuItem("添加流水账","style/images/task_menu/copy.gif","parent.window.frames[1].location.href='index.php?ctrl=bill&act=edit'");
	item12 = new TaskMenuItem("查看流水账","style/images/task_menu/friends.gif","parent.window.frames[1].location.href='index.php?ctrl=bill&act=bills'");

	item13 = new TaskMenuItem("添加新业务","style/images/task_menu/copy.gif","parent.window.frames[1].location.href='index.php?ctrl=business&act=edit'");
	item14 = new TaskMenuItem("查看业务","style/images/task_menu/friends.gif","parent.window.frames[1].location.href='index.php?ctrl=business&act=items'");

	item51 = new TaskMenuItem("密码修改","style/images/task_menu/dload.gif","parent.window.frames[1].location.href='index.php?ctrl=user&act=change_password'");
	item52 = new TaskMenuItem("退出","style/images/task_menu/update.gif","parent.window.location.href='index.php?ctrl=index&act=login'");

	////////////////////////////////////////////////
	taskMenu1 = new TaskMenu("账单后台管理");
	taskMenu1.add(item1);
	taskMenu1.init();

	
	taskMenu2 = new TaskMenu("常规管理");
	taskMenu2.add(item11);
	taskMenu2.add(item12);
	taskMenu2.add(item13);
	taskMenu2.add(item14);
	taskMenu2.init();


	taskMenu3 = new TaskMenu("系统管理");
	taskMenu3.add(item51);
	taskMenu3.add(item52);
	taskMenu3.init();
	
}
</script>
</head>
</html>