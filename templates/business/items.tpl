<{$data_tables}>
<script type="text/javascript">
function delete_row(bid){
	if(confirm("确定删除？")){
		$.ajax({
			type: "POST",
			url: "index.php?ctrl=business&act=delete",
			data: "bid="+bid ,
			timeout: 20000,
			error: function(){alert('error'); },
			success: function(result){
			    if(result != 'success'){
					alert(result);
				}
			    $business_table.fnDraw();
			}
		});
	}
	
}
</script>