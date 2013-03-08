<{include file="header.tpl"}>
<form method="post" id="business_edit_form" action="index.php?ctrl=business&act=edit_submit">
    <table class="itable"><tbody>
        <tr>
          <th>公司名称</th>
          <td>
          <input type="text" value="<{$business.company}>" name="company" />
          </td>
        </tr>
        
        <tr>
          <th>法人姓名</th>
          <td>
          <input type="text" value="<{$business.boss}>" name="boss" />
          </td>
        </tr>
        
        <tr>
          <th>电话</th>
          <td>
          <input type="text" value="<{$business.phone}>" name="phone" />
          </td>
        </tr>
        
        <tr>
          <th>QQ</th>
          <td>
          <input type="text" value="<{$business.qq}>" name="qq" />
          </td>
        </tr>
        <tr>
          <th>注册资金</th>
          <td>
          <input type="text" value="<{$business.funds}>" name="funds" />万元
          </td>
        </tr>
        <tr>
          <th>注册地址</th>
          <td>
          <input type="text" value="<{$business.address}>" name="address" class="width-long"/>
          </td>
        </tr>
        <tr>
          <th>接单员</th>
          <td>
          <input type="text" value="<{$business.jiedan}>" name="jiedan" />
          </td>
        </tr>
        <tr>
          <th>归属客服</th>
          <td>
          <input type="text" value="<{$business.kefu}>" name="kefu" />
          </td>
        </tr>
        <tr>
          <th>代理记账开始日期</th>
          <td>
          <input type="text" value="<{$business.start_time_format}>" name="start_time" class="datepicker"/>
          </td>
        </tr>
        <tr>
          <th>到期日期</th>
          <td>
          <input type="text" value="<{$business.end_time_format}>" name="end_time" class="datepicker"/>
          </td>
        </tr>
        <tr>
          <th>收费标准</th>
          <td>
          <input type="text" value="<{$business.cost}>" name="cost" />元/月
          </td>
        </tr>
        <tr>
          <th>付款类型</th>
          <td>
          <select name="cost_type">
          	<{foreach from=$cost_type key=key item=item}>
          		<option value="<{$key}>" <{if $business.cost_type==$key}>selected<{/if}>><{$item}></option>
          	<{/foreach}>
          </select>
          </td>
        </tr>
        <tr>
          <th>缴税类别</th>
          <td>
          <select name="tax_type">
          	<{foreach from=$tax_type key=key item=item}>
          		<option value="<{$key}>" <{if $business.tax_type==$key}>selected<{/if}>><{$item}></option>
          	<{/foreach}>
          </select>
          </td>
        </tr>
        <tr>
          <th>订单状态</th>
          <td>
          <select name="bill_status">
          	<{foreach from=$bill_status key=key item=item}>
          		<option value="<{$key}>" <{if $business.bill_status==$key}>selected<{/if}>><{$item}></option>
          	<{/foreach}>
          </select>
          </td>
        </tr>

        <tr>
          <th>申报情况</th>
          <td><textarea type="text" name="remark" style="width:400px;height:100px;"><{$business.remark}></textarea></td>
        </tr>
        <tr>
          <td><input type="hidden" name="bid" value="<{$business.bid}>"/>
          </td>
          <td><input type="submit" value="确定提交" id="business_edit_submit"/>
          <input type="reset" value="清空表单"/>
          </td>
        </tr>
       
      </tbody></table>
 </form>
 
<script type="text/javascript">
$('.datepicker').datepicker();

$('#business_edit_form').ajaxForm({
	beforeSend: function() {
		$("#business_edit_submit").attr("disabled", "disabled");
    },
    complete: function(xhr) {
    	$("#business_edit_submit").attr("disabled", false);
        var msg = xhr.responseText;//JSON.parse();
        if(msg == 'success'){
        	alert('成功');
        }else{
        	alert('失败:' + msg);
        }
    }
});
</script>
<{include file="footer.tpl"}>