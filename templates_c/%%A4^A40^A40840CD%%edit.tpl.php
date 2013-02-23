<?php /* Smarty version 2.6.27, created on 2013-02-21 21:45:56
         compiled from bill/edit.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form method="post" id="bill_edit_form" action="index.php?ctrl=bill&act=edit_submit" enctype="multipart/form-data">
    <table class="itable">
        <tr>
          <th>日期</th>
          <td>
          <input type="text" value="<?php echo $this->_tpl_vars['bill']['date_format']; ?>
" name="date" class="datepicker"/>
          </td>
        </tr>
        
        <tr>
          <th>款项类别</th>
          <td>
          <label><input type="radio" name="type" value="1" <?php if ($this->_tpl_vars['bill']['type'] == 1): ?>checked<?php endif; ?>/>收入</label>
          <label><input type="radio" name="type" value="2" <?php if ($this->_tpl_vars['bill']['type'] == 2): ?>checked<?php endif; ?>/>支出</label>
          </td>
        </tr>
        <tr>
          <th>款项名称</th>
          <td><input type="text" value="<?php echo $this->_tpl_vars['bill']['title']; ?>
" name="title" class="width-long"/></td>
        </tr>
        <tr>
          <th>发生金额</th>
          <td>
          <input type="text" value="<?php echo $this->_tpl_vars['bill']['money']; ?>
" name="money" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onblur="if(!this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?|\.\d*?)?$/))this.value=this.o_value;else{if(this.value.match(/^\.\d+$/))this.value=0+this.value;if(this.value.match(/^\.$/))this.value=0;this.o_value=this.value}" />
          </td>
        </tr>
        <tr>
          <th>上传凭证</th>
          <td><input type="file" name="file"/></td>
        </tr>
        <tr>
          <th>备注</th>
          <td><textarea type="text" name="remark" style="width:400px;height:100px;"><?php echo $this->_tpl_vars['bill']['remark']; ?>
</textarea></td>
        </tr>
        <!-- <tr>
          <th>状态</th>
          <td><select name="status">
          <option value="1">待确认</option>
          <option value="2">已确认</option>
          </select></td>
        </tr> -->
        <tr>
          <td>
          <input value="1" name="status" type="hidden"/></td>
          <td><input type="submit" name="submit" value="确定提交" /></td>
        </tr>
       
      </table>
 </form>
 
<script type="text/javascript">
$('.datepicker').datepicker();
$('#bill_edit_form').ajaxForm({
	beforeSend: function() {
		$("input[name='submit']").attr("disabled", "disabled");
    },
    complete: function(xhr) {
    	$("input[name='submit']").attr("disabled", false);
        var msg = xhr.responseText;//JSON.parse();
        if(msg > 0){
        	alert('添加成功');
        }else{
        	alert('添加失败' + msg);
        }
    }
});
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>