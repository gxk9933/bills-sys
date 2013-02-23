<?php /* Smarty version 2.6.27, created on 2013-02-20 22:51:42
         compiled from common/data_table.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'common/data_table.htm', 72, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all <?php echo $this->_tpl_vars['otherData']['tipsID']; ?>
" style="margin-bottom:8px; padding:10px; display:none;">
	<span style="float:right"><a class="close" href="#" onClick="$(this).parent().parent().slideUp();">X</a></span>
	<div id="TipsCont"></div>
</div>
<!-- 
使用方法：
1. 在$otherData中传入标识唯一的tipsID作为class
2. 需要tips数据时：
    $('.tipsID').slideDown();
    $('.tipsID').children("#TipsCont").html(html);
 -->

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="<?php echo $this->_tpl_vars['otherData']['id']; ?>
" style=" overflow:auto; zoom:1;">
	<div id="datatable_header">
	<?php echo $this->_tpl_vars['otherData']['header']; ?>

	</div>
	<div class="datatable_search" style="padding:5px;<?php if ($this->_tpl_vars['otherData']['datatable_search_style']): ?><?php echo $this->_tpl_vars['otherData']['datatable_search_style']; ?>
<?php endif; ?>">
	    <div class="search_content">
	    <?php if ($this->_tpl_vars['otherData']['searchHeader']): ?>
		    <div class="search_float">
		    <?php echo $this->_tpl_vars['otherData']['searchHeader']; ?>

		    </div>
	    <?php endif; ?>
	    <?php $_from = $this->_tpl_vars['searchFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
		    <div class="search_float search_field field_<?php echo $this->_tpl_vars['key']; ?>
" type="<?php echo $this->_tpl_vars['item']['type']; ?>
">
			    <?php echo $this->_tpl_vars['item']['title']; ?>
:&nbsp;&nbsp;
			    <?php if ($this->_tpl_vars['item']['type'] == 'input'): ?>
			        <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;">
		        <?php elseif ($this->_tpl_vars['item']['type'] == 'date'): ?>
                    <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;" <?php if ($this->_tpl_vars['item']['value']): ?>value="<?php echo $this->_tpl_vars['item']['value']; ?>
"<?php endif; ?> class="datepicker">&nbsp;&nbsp;
			    <?php elseif ($this->_tpl_vars['item']['type'] == 'range'): ?>
			        <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;">&nbsp;~&nbsp;
			        <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;">
		        <?php elseif ($this->_tpl_vars['item']['type'] == 'range_date'): ?>
		            <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;" class="datepicker">&nbsp;~&nbsp;
		            <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;" class="datepicker">
		        <?php elseif ($this->_tpl_vars['item']['type'] == 'range_time'): ?>
		            <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;" class="datetimepicker">&nbsp;~&nbsp;
		            <input <?php if ($this->_tpl_vars['item']['size']): ?>size="<?php echo $this->_tpl_vars['item']['size']; ?>
"<?php endif; ?> style="width:<?php echo $this->_tpl_vars['item']['input_width']; ?>
px;" class="datetimepicker">
			    <?php elseif ($this->_tpl_vars['item']['type'] == 'select'): ?>
			        <select>
				    <?php $_from = $this->_tpl_vars['item']['value']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
				    <option value="<?php echo $this->_tpl_vars['key']; ?>
"><?php echo $this->_tpl_vars['item']; ?>
</option>
				    <?php endforeach; endif; unset($_from); ?>
				    </select>
			    <?php endif; ?>
		    </div>
	    <?php endforeach; endif; unset($_from); ?>
	    <div style="float:left; padding:5px;">
	    <input type="button" class="datatable_search_button g-button" value="<?php if ($this->_tpl_vars['searchFields']): ?>搜索<?php else: ?>刷新<?php endif; ?>" />
	    </div>
	   	<?php if ($this->_tpl_vars['otherData']['searchFooter']): ?>
		<div class="search_float">
		    <?php echo $this->_tpl_vars['otherData']['searchFooter']; ?>

		</div>
	    <?php endif; ?>
	    <div style="clear:both;"></div>
	    </div>
	</div>

	<table cellpadding="0" cellspacing="0" border="0" class="table_list itable">
	    <thead>
	        <tr height="44px">
	        <?php $_from = $this->_tpl_vars['showFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
	            <th <?php if ($this->_tpl_vars['item']['width']): ?>width="<?php echo $this->_tpl_vars['item']['width']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['item']['title']; ?>
</th>
	        <?php endforeach; endif; unset($_from); ?>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
	            <td colspan="<?php echo ((is_array($_tmp=$this->_tpl_vars['showFields'])) ? $this->_run_mod_handler('count', true, $_tmp) : count($_tmp)); ?>
" class="dataTables_empty"></td>
	        </tr>
	    </tbody>
	
	    <tfoot>
	        <tr>
	            <?php $_from = $this->_tpl_vars['showFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
	            <th><?php echo $this->_tpl_vars['item']['title']; ?>
</th>
	            <?php endforeach; endif; unset($_from); ?>
	        </tr>
	    </tfoot>
	</table>
</div>
<script type="text/javascript">
$('.datepicker').datepicker();

var $<?php echo $this->_tpl_vars['otherData']['id']; ?>
 = $('#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .table_list').dataTable({
    "bProcessing": true,
    "bAutoWidth":<?php if ($this->_tpl_vars['otherData']['bAutoWidth'] == false): ?>false<?php else: ?>true<?php endif; ?>,
    "bServerSide": true,
    "iDisplayLength": <?php echo $this->_tpl_vars['otherData']['iDisplayLength']; ?>
, 
    "bLengthChange": true,
    "bStateSave": <?php if ($this->_tpl_vars['otherData']['bStateSave'] == true): ?>true<?php else: ?>false<?php endif; ?>,
    "bFilter": true,
    "sPaginationType": "full_numbers", 
    "sAjaxSource": "<?php echo $this->_tpl_vars['ajaxSource']; ?>
",
    "aaSorting": [[ <?php echo $this->_tpl_vars['otherData']['sortCol']; ?>
, "<?php echo $this->_tpl_vars['otherData']['sortDir']; ?>
" ]],
    'bSort' : <?php echo $this->_tpl_vars['otherData']['bSort']; ?>
,
    "oLanguage": {
    "sUrl": "style/js/dt_cn.txt"
    },
    "aoColumns": [
		<?php echo $this->_tpl_vars['aoColumns']; ?>

     ],
     "sDom": '<?php if ($this->_tpl_vars['otherData']['sDom']): ?><?php echo $this->_tpl_vars['otherData']['sDom']; ?>
<?php else: ?><"top"lp>rt<"bottom"ip><"clear"><?php endif; ?>'
});

$('#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .table_list tbody tr').live('mouseover', function () {
    $(this).addClass('row_selected');
}).live('mouseout', function () {
    $(this).removeClass('row_selected');
});


$('#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .datatable_search input').keydown( function (event) {
    if(event.keyCode==13){
    	$('#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .datatable_search_button').trigger("click");
    }
});

$('#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .datatable_search_button').click(function(){
	$(this).attr("disabled", "disabled");
	var data = new Array();
	$("#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .search_field").each(function(){
		var type = $(this).attr('type');
		var value = '';
		if(type == 'input' || type == 'select'){
			value = $(this).find(type).val();
		}else if(in_array(type, ['range', 'range_date', 'range_time'])){
			var range = new Array();
			$(this).find('input').each(function(){
				range.push($(this).val());
			});
			value = range.join('|');
		}else if(type == "date"){
			value = $(this).find("input").val();
		}
		
		data.push(value);
	});
	
	$<?php echo $this->_tpl_vars['otherData']['id']; ?>
.fnMultiFilter(data);
	<?php if ($this->_tpl_vars['otherData']['js']): ?><?php echo $this->_tpl_vars['otherData']['js']; ?>
<?php endif; ?>
	setTimeout('$("#<?php echo $this->_tpl_vars['otherData']['id']; ?>
 .datatable_search_button").attr("disabled", false);', 2000);
});
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>