<?php
class control extends base{
	function __construct(){
		parent::__construct();
		$this->billClass = $this->load('bill');
	}
	
	function edit(){
		$bill_id = intval($_GET['bill_id']);
		if($bill_id){
			$bill = $this->billClass->getBill($bill_id);
			$this->smarty->assign('bill', $bill);
		}
		
		$this->smarty->display('bill/edit.tpl');
	}
	
	function edit_submit(){
		$bill_id = intval($_POST['bill_id']);
		$date = strtotime($_POST['date']);
		$type = intval($_POST['type']);
		$title = trim($_POST['title']);
		$money = doubleval($_POST['money']);
		$remark = trim($_POST['remark']);
		$status = trim($_POST['status']);
		
		if(empty($date)){$msg = '请填写日期';}
		elseif(empty($type)){$msg = '款项类别错误';}
		elseif(empty($title)){$msg = '请填写款项';}
		elseif(empty($money)){$msg = '请填写发生金额';}
		elseif(empty($status)){$msg = '状态选择错误';}
		
		if(!empty($msg)){
			echo $msg;
			return;
		}
		
		if($bill_id){
		
		}else{
			$result = $this->billClass->add($date, $title, $type, $money, $remark, $status);
			echo $result;
		}
		
	}
	
	function bills(){
		
		$status = array('' => '全部状态', 1 => '待确认', 2 => '确认');
		$searchFields = array(
	            'keywords' => array('title' => '关键字'),
	            'status' => array('title' => '状态', 'type' => 'select', 'value' => $status),
	    );
	    
	    $showFields = array(
            'date' => array('title' => '款项发生日期'),
            'type' => array('title' => '款项类别'),
            'title' => array('title' => '款项名称'),
            'money' => array('title' => '发生金额'),
            'remain_money' => array('title' => '余额'),
            'remark' => array('title' => '备注'),
            'add_user' => array('title' => '记账员'),
    		'status' => array('title' => '状态'),
            'op' => array('title' => '操作'),
	    	'created' => array('title' => '添加时间'),
	    );
	    $ajaxSource = 'index.php?ctrl=bill&act=bills_data';
	    
	    $otherData = array(
	    	'id' => 'bill_table',
	    	'iDisplayLength' => 25,
	    	'bStateSave' => true,
	    );
	    $data_tables = $this->theme_dataTables($searchFields, $showFields, $ajaxSource, $otherData, false);
	    
	    
	    $this->smarty->assign('data_tables', $data_tables);
	    $this->smarty->display('bill/bills.tpl');
	}
	
	function bills_data(){
		$perPage = intval($_GET['iDisplayLength']);
		$pageStart = intval($_GET['iDisplayStart']);
		
		$where = array();
	    for($i = 0; $i <= 1; $i++){
	        if($_GET['sSearch_'.$i]){
	            $value = trim($_GET['sSearch_'.$i]);
	            switch ($i){
	                case 0:
	                    $where[] = "`title` LIKE '%{$value}%' OR `remark` LIKE '%{$value}%'";
	                    break;
                    case 1:
                        $where[] = array('status', $value);
                        break;
	            }
	        }
	    }
		
		
		$conditions = array(
	        'where' => $where,
			'from' => $pageStart,
			'count' => $perPage,
		);
		
		$result = $this->billClass->dataJson($conditions);
		echo $result;
	}
	
	
	function confirm_bill(){
		$bill_id = intval($_POST['bill_id']);
		if(empty($bill_id)){
			echo "error";
			return;
		}
	
		$result = $this->billClass->confirm_bill($bill_id);
		echo $result? 'success': 'repeat';
	}
	
}