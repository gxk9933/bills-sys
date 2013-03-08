<?php
class business{
	function __construct(&$base){
        $this->base = $base;
        $this->business_table = $base->mysqli;
        $this->business_table->setTableName('business');
    }
    
    function cost_type($id=0){
    	static $types;
    	if(empty($types)){
	    	$types = array();
	    	for($i=1; $i<=12; $i++){
	    		$types[$i] = "{$i}月";
	    	}
    	}
    	if($id && $types[$id]){return $types[$id];}
    	return $types;
    }
    
    function tax_type($id=0){
    	$types = array(
    		1 => '小规模纳税人',
    		2 => '一般纳税人',
    		3 => '定额税',
    	);
    	
    	if($id && $types[$id]){return $types[$id];}
    	return $types;
    }
    
    function bill_status($id=0){
    	static $types;
    	
    	if(empty($types)){
	    	$types = array();
	
	    	for($y = 2013; $y <= 2018; $y++){
	    		for($m = 1; $m<=12; $m++){
	    			$types["{$y}{$m}"] = "{$y}年{$m}月份已经申报";
	    		}
	    	}
    	}
    	
    	if($id && $types[$id]){return $types[$id];}
    	return $types;
    	
    }
    

    function update($data){
    	$bid = intval($data['bid']);
        $new_data = array(
        	'company' => trim($data['company']),
        	'boss' => trim($data['boss']),
        	'phone' => trim($data['phone']),
        	'qq' => trim($data['qq']),
        	'funds' => intval($data['funds']),
        	'address' => trim($data['address']),
        
        	'jiedan' => trim($data['jiedan']),
        	'kefu' => trim($data['kefu']),
        
        	'start_time' => trim($data['start_time'])? trim($data['start_time']): date('Y-m-d'),
        	'end_time' => trim($data['end_time'])? trim($data['end_time']): date('Y-m-d'),
        
        	'cost' => intval($data['cost']),
	        'cost_type' => intval($data['cost_type']),
	        'tax_type' => intval($data['tax_type']),
        	'bill_status' => intval($data['bill_status']),
        
        	'remark' => trim($data['remark']),
        	'add_user' => $_SESSION['bills_username'],
        	'created' => $this->base->current_time
        );
        if($bid){
        	$new_data['bid'] = $bid;
        	$result = $this->business_table->update($new_data);
        }else{
        	$result = $this->business_table->insert($new_data);
        }
    	
    	return $result? 'success': 'faile';
    }

    
    function dataJson($conditions){
    	$page = $conditions['page']? $conditions['page']: 0;
        $count = $conditions['count']? $conditions['count']: DEFAULT_PAGE_COUNT;
        $from = $conditions['from']? $conditions['from']: $page*$count;
        $sortBy = $conditions['sort'] ? $conditions['sort'] : '`bid` DESC';
        $where = $conditions['where']? $conditions['where']: array();
        
        $data = $this->business_table->getList('*', $where, $from, $count, $sortBy);
        $countThis = count($data);
        $countAll = $this->business_table->count($conditions['where']);
        
        $json_arr = array(
            'sEcho' => intval($_GET['sEcho']),
            'iTotalRecords' => $countThis,
            "iTotalDisplayRecords" => $countAll
        );
        $aaData = array();
        
        foreach ($data as $row) {
        	$edit = array("<a href='index.php?ctrl=business&act=edit&bid={$row['bid']}'>编辑</a>");
        	$_SESSION['bills_groupid'] == 1 && $edit[] = "<a href='javascript:void()' onclick='delete_row({$row['bid']})'>删除</a>";
        	
            $aaData[] = array(
	            $row['company'],
	            $row['boss'],
	            $row['phone']."({$row['qq']})",
	            $row['cost']."元/月",
	            $this->cost_type($row['cost_type']),
	            $this->tax_type($row['tax_type']),
	            substr($row['end_time'], 0, 10),
	            $row['add_user'],
	            $this->bill_status($row['bill_status']),
                implode(' | ', $edit),
            );
        }
        $json_arr['aaData'] = $aaData;
        return json_encode($json_arr);
    }
	function getBusiness($bid){
    	$row = $this->business_table->getOneById($bid);
    	$row['start_time_format'] = substr($row['start_time'], 0, 10);
    	$row['end_time_format'] = substr($row['end_time'], 0, 10);
    	return $row;
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
    
    function deleteBusiness($bid){
    	return $this->business_table->deleteById($bid);
    }
	
}