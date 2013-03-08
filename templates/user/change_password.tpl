<{include file="header.tpl"}>
<{if $msg}><p style="color:red;margin:20px"><{$msg}></p><{/if}>
<form method="post" id="change_password_form" action="index.php?ctrl=user&act=change_password">
    <table class="itable">
    <tbody><tr>
    <td></td>
    <td id="change_password_msg" style="color:red;"></td>
    </tr>
    <tr>
      <th>旧密码:</th>
      <td><input type="password" value="" name="old_password">
      <font color="red">*</font></td>
    </tr>
    <tr>
      <th>新密码:</th>
      <td><input type="password" value="" name="password" id="password" />
      <font color="red">*</font>包含大小写字母、数字和特殊字符会让你的密码更安全
      </td>
    </tr>
    <tr>
      <th>重复密码:</th>
      <td><input type="password" value="" name="password2" id="password2" />
      <font color="red">*</font>
      </td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" name="change_password_submit" class="g-button" value="确认修改" name="submit">
      </td>
    </tr>
  </tbody></table>
</form>
<{include file="footer.tpl"}>