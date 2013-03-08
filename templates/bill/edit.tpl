<{include file="header.tpl"}>
<form method="post" id="bill_edit_form" action="index.php?ctrl=bill&act=edit_submit" enctype="multipart/form-data">
    <table class="itable"><tbody>
        <tr>
          <th>日期</th>
          <td>
          <input type="text" value="<{$bill.date_format}>" name="date" class="datepicker"/>
          </td>
        </tr>
        
        <tr>
          <th>款项类别</th>
          <td>
          <label><input type="radio" name="type" value="1" <{if $bill.type == 1}>checked<{/if}>/>收入</label>
          <label><input type="radio" name="type" value="2" <{if $bill.type == 2}>checked<{/if}>/>支出</label>
          </td>
        </tr>
        <tr>
          <th>款项名称</th>
          <td><input type="text" value="<{$bill.title}>" name="title" class="width-long"/></td>
        </tr>
        <tr>
          <th>发生金额</th>
          <td>
          <input type="text" value="<{$bill.money}>" name="money" onkeypress="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onkeyup="if(!this.value.match(/^[\+\-]?\d*?\.?\d*?$/))this.value=this.t_value;else this.t_value=this.value;if(this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?)?$/))this.o_value=this.value" onblur="if(!this.value.match(/^(?:[\+\-]?\d+(?:\.\d+)?|\.\d*?)?$/))this.value=this.o_value;else{if(this.value.match(/^\.\d+$/))this.value=0+this.value;if(this.value.match(/^\.$/))this.value=0;this.o_value=this.value}" />
          </td>
        </tr>
        <tr>
          <th>上传凭证</th>
          <td><input type="file" name="file"/>(20M)</td>
        </tr>
        <tr>
          <th>上传进度</th>
          <td>
<div class="progress">
    <div class="bar"></div >
    <div class="percent">0%</div >
</div>
<style type="text/css">
.progress { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>
          </td>
        </tr>
        <tr>
          <th>备注</th>
          <td><textarea type="text" name="remark" style="width:400px;height:100px;"><{$bill.remark}></textarea></td>
        </tr>
        <tr>
          <td>
          <input value="1" name="status" type="hidden"/></td>
          <td><input type="submit" value="确定提交" id="bill_edit_submit"/></td>
        </tr>
       
      </tbody></table>
 </form>
 
<script type="text/javascript">
$('.datepicker').datepicker();
var bar = $('.bar');
var percent = $('.percent');
$('#bill_edit_form').ajaxForm({
	beforeSend: function() {
		//upload
		var percentVal = '0%';
        bar.width(percentVal)
        percent.html(percentVal);

        
		$("#bill_edit_submit").attr("disabled", "disabled");
    },
    uploadProgress: function(event, position, total, percentComplete) {
    	//upload
        var percentVal = percentComplete + '%';
        bar.width(percentVal)
        percent.html(percentVal);
    },
    complete: function(xhr) {
        //upload
    	var percentVal = '100%';
        bar.width(percentVal)
        percent.html(percentVal);

        
    	$("#bill_edit_submit").attr("disabled", false);
        var msg = xhr.responseText;//JSON.parse();
        if(msg == 'success'){
        	alert('添加成功');
        }else{
        	alert('添加失败:' + msg);
        }
    }
});
</script>
<{include file="footer.tpl"}>