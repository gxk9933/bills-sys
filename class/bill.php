<?php
class bill{
	function __construct(&$base){
        $this->base = $base;
        $this->bill_table = $base->mysqli;
        $this->bill_table->setTableName('bills');
    }
    
    
    function add($date, $title, $type, $money, $remark, $status){
    	//上传凭证
    	if($_FILES){
    		if($_FILES['file']['error'] > 0){
	    		switch($_FILES['file']['error']) {   
				    case 1:    
				        return '文件大小超出了服务器的空间大小';
					case 2:    
						return '要上传的文件大小超出浏览器限制';   
					case 3:
						return '文件仅部分被上传'; 
					case 4:
						return '没有找到要上传的文件';
					case 5:
						return '服务器临时文件夹丢失';
					case 6:
						return '文件写入到临时文件夹出错';
				}
    		}
	    	include(ROOT."/library/UploadFile.class.php");
	        $UploadFile = new UploadFile(1024*1024*20, null, '', 'writable/upload/'.date('Y-m').'/', 'upload_rename_rule');
	        $UploadFile -> upload();
	        $file_info = $UploadFile -> getUploadFileInfo();
	        $file_info = $file_info[0];
	        $file_path = trim($file_info['savepath'].$file_info['savename']);
    	}
        
    	//查询余额
    	$last = $this->bill_table->getList ('*', '1', 0, 1, 'bill_id DESC');
    	$remain_money = $type == 1? $money: -$money;
    	$remain_money = $remain_money + intval($last[0]['remain_money']);
    	
        $data = array(
        	'date' => $date,
        	'title' => $title,
        	'type' => $type,
        	'money' => $money,
        	'remain_money' => $remain_money,
        	'file' => $file_path,
        	'remark' => $remark,
        	'status' => $status,
        	'data' => '',
        	'add_user' => $_SESSION['bills_username'],
        	'created' => $this->base->current_time
        );
    	$result = $this->bill_table->insert($data);
    	return $result? 'success': 'faile';
    }
    
    function confirm_bill($bill_id){
    	$data = array(
    		'bill_id' => $bill_id,
    		'status' => 2,
    		'confirm_user' => $_SESSION['bills_username'],
    		'data' => serialize(array('confirm_time' => $this->base->current_time)),
    	);
    	
    	return $this->bill_table->update($data);
    }
    function getBill($bill_id){
    	$bill = $this->bill_table->getOneById($bill_id);
    	$bill['date_format'] = date('Y-m-d', $bill['date']);
    	return $bill;
    }
    
    function dataJson($conditions){
    	$page = $conditions['page']? $conditions['page']: 0;
        $count = $conditions['count']? $conditions['count']: DEFAULT_PAGE_COUNT;
        $from = $conditions['from']? $conditions['from']: $page*$count;
        $sortBy = $conditions['sort'] ? $conditions['sort'] : '`bill_id` DESC';
        $where = $conditions['where']? $conditions['where']: array();
        
        $data = $this->bill_table->getList('*', $where, $from, $count, $sortBy);
        $countThis = count($data);
        $countAll = $this->bill_table->count($conditions['where']);
        
        $json_arr = array(
            'sEcho' => intval($_GET['sEcho']),
            'iTotalRecords' => $countThis,
            "iTotalDisplayRecords" => $countAll
        );
        $aaData = array();
        foreach ($data as $row) {
        	$money_sign = $row['type'] == 1? "+": "-";
        	$money_color = $row['type'] == 1? "red": "green";
        	$edit = array();
        	$row['status'] != 2 && $_SESSION['bills_groupid'] == 1 && $edit[] = "<a onclick='confirm_bill({$row['bill_id']})' href='javascript:void(0)' class='underline'>确认款项</a>";
        	$row['file'] && $edit[] = "<a href='{$row['file']}' class='underline' target='_blank'>查看凭证</a>";
            $aaData[] = array(
                date('Y-m-d', $row['date']),
                $this->getType($row['type']),
                $row['title'],
                "<span class='money $money_color'>{$money_sign}{$row['money']}</span>",
                "<span class='money blue'>{$row['remain_money']}</span>",
                $row['remark'],
                $row['add_user'],
                $this->getStatus($row['status']),
                implode(' | ', $edit),
                date('Y-m-d H:i:s', $row['created']),
            );
        }
        $json_arr['aaData'] = $aaData;
        return json_encode($json_arr);
    }
    
	function getType($type=0){
		$types = array(
			1 => '收入',
			2 => '支出',
		);
		
		if(empty($type)){
			return $types;
		}else{
			return $types[$type];
		}
	}
	
	function getStatus($value){
		$status = array(1 => '待确认', 2 => '确认');
		if(empty($value)){
			return $status;
		}else{
			return $status[$value];
		}
	}
}