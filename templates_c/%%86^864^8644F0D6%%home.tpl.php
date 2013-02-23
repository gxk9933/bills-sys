<?php /* Smarty version 2.6.27, created on 2013-02-21 21:55:42
         compiled from home.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table class="itable">
	<tr><td width="50%">服务器时间：<?php echo $this->_tpl_vars['date']; ?>
</td><td>登录账号：<?php echo $this->_tpl_vars['username']; ?>
  - <?php echo $this->_tpl_vars['remote_ip']; ?>
</td></tr>
	<tr><td>服务器IP：<?php echo $this->_tpl_vars['server_ip']; ?>
</td><td>站点路径：<?php echo $this->_tpl_vars['root']; ?>
</td></tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>