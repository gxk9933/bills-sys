<{$data_tables}>
<script type="text/javascript">
function confirm_bill(bill_id){
	if(confirm("确认该笔款项？")){
		$.ajax({
			type: "POST",
			url: "index.php?ctrl=bill&act=confirm_bill",
			data: "bill_id="+bill_id ,
			timeout: 20000,
			error: function(){alert('error'); },
			success: function(result){
			    if(result != 'success'){
					alert(result);
				}
			    $bill_table.fnDraw();
			}
		});
	}
	
}
</script>