<?php
class control extends base{
	function __construct(){
		parent::__construct();
		$this->businessClass = $this->load('business');
	}
	
	function edit(){
		$bid = intval($_GET['bid']);
		if($bid){
			$business = $this->businessClass->getBusiness($bid);
			$this->smarty->assign('business', $business);
		}
		
		
		$this->smarty->assign('cost_type', $this->businessClass->cost_type());
		$this->smarty->assign('tax_type', $this->businessClass->tax_type());
		$this->smarty->assign('bill_status', $this->businessClass->bill_status());
		
		$this->smarty->display('business/edit.tpl');
	}
	
	function edit_submit(){
		
		$data = $_POST;
		$result = $this->businessClass->update($data);
		echo $result;

		
	}
	
	function items(){
		$searchFields = array(
            'keyword' => array('title' => '关键字'),
			'bill_status' => array('title' => '订单状态', 'type' => 'select', 'value' => array('0' => '全部状态') + $this->businessClass->bill_status()),
			'end_time' => array('title' => '到期时间', 'type' => 'year_month'),
		
	    );
	    
	    $showFields = array(
            'company' => array('title' => '公司名称'),
            'boss' => array('title' => '法人姓名'),
            'phone_qq' => array('title' => '电话（QQ）'),
            'cost' => array('title' => '收费标准'),
            'cost_type' => array('title' => '付款类型'),
            'tax_type' => array('title' => '缴税类别'),
            'end_time' => array('title' => '到期时间'),
    		'add_user' => array('title' => '记账员'),
            'bill_status' => array('title' => '订单状态'),
	    	'created' => array('title' => '操作'),
	    );
	    $ajaxSource = 'index.php?ctrl=business&act=items_data';
	    
	    $otherData = array(
	    	'id' => 'business_table',
	    	'iDisplayLength' => 25,
	    	'bStateSave' => false,
	    );
	    $data_tables = $this->theme_dataTables($searchFields, $showFields, $ajaxSource, $otherData, false);
	    
	    
	    $this->smarty->assign('data_tables', $data_tables);
	    $this->smarty->display('business/items.tpl');
	}
	
	function items_data(){
		$perPage = intval($_GET['iDisplayLength']);
		$pageStart = intval($_GET['iDisplayStart']);
		
		$where = array();
	    for($i = 0; $i <= 2; $i++){
	        if($_GET['sSearch_'.$i]){
	            $value = trim($_GET['sSearch_'.$i]);
	            switch ($i){
	                case 0:
	                    $where[] = "`company` LIKE '%{$value}%' OR `boss` LIKE '%{$value}%' OR `phone` LIKE '%{$value}%' OR `qq` LIKE '%{$value}%'";
	                    break;
	                case 1:
	                	$where[] = "`bill_status` = {$value}";
	                	break;
                    case 2:
                    	$arr = explode("|", $value);
                    	$year = $arr[0];
                    	$month = $arr[1];
                    	if($year && $month){
	                    	$gt = "{$year}-{$month}-01";
	                    	$lt = date("Y-m-d", strtotime("+1 month", strtotime($gt)));
	                        $where[] = "`end_time` >= '{$gt}' AND `end_time` < '$lt'";
                    	}
                        break;
	            }
	        }
	    }
		
		
		$conditions = array(
	        'where' => $where,
			'from' => $pageStart,
			'count' => $perPage,
		);
		
		$result = $this->businessClass->dataJson($conditions);
		echo $result;
	}
	
	
	function delete(){
		$bid = intval($_POST['bid']);
		if(empty($bid)){
			echo "$bid";
			return;
		}
	
		$result = $this->businessClass->deleteBusiness($bid);
		echo $result? 'success': 'repeat';
	}
	
}