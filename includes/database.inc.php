<?php
global $mysql_link, $query_times;

class DbMysqli {
    //$mysqli作为public，在本类中的方法满足不到的情况下，可以直接使用mysqli
    public $mysqli; 
    protected $result;
    protected $fields = array();
    protected $priKey = array();
    protected $tableName;

    function __construct($host, $user, $password, $db, $port){
        global $mysql_link;
        if ($mysql_link[$db]) {
            $this->mysqli = $mysql_link[$db];
        } else {
            
            $this->mysqli = new mysqli($host, $user, $password, $db, $port);
            $msg = mysqli_connect_errno();
            if ($msg) {
                exit("连接数据库失败，如果是初次装服请1分钟后刷新数据库");
            }
    
            $this->mysqli->set_charset("utf8");
            $mysql_link[$db] = $this->mysqli;
        }
    }

    function setTableName ($name) {
        $this->tableName = TABLE_PREFIX.$name;
    }

    function query ($sql, $args=array(), $unbuffered=false) {
        if($args){
            $this->_addslashes($args);
            foreach ($args as $key => $value) {
                $key[0] == '@' &&  $args[$key] = check_plain($value);
            }
            $sql = strtr($sql, $args);
        }
        
        
        global $query_times;
        $query_times++;
        
        if($unbuffered == false){
            $resultmode = MYSQLI_STORE_RESULT;
        }else{
            $resultmode = MYSQLI_USE_RESULT;
        }
        
        $this->result = $this->mysqli->query($sql, $resultmode);
        
        if (! $this->result) {
             write_error_log("error sql:$sql");
        }
        return $this->result;
    }

    function fetch_array () {
        if ($this->result) return $this->result->fetch_array();
    }

    function fetch_object () {
        if ($this->result) return $this->result->fetch_object();
    }

    function fetch_assoc () {
        if ($this->result) return $this->result->fetch_assoc();
    }

    /**
     * 插入数据
     * 返回insert_id或者true为插入成功
     */
    function insert ($data) {
        $this->_addslashes($data);
        
        $this->_mustSetTableName();
        if (empty($data)) {throw new Exception('No data, what do I insert');}
        //获取表字段
        $this->getFields();
        
        $insert_fields_arr = $insert_value_arr = array();
        foreach ($data as $k => $v) {
            if (in_array($k, $this->fields) && !empty($v)) {
                $insert_fields_arr[] = "`$k`";
                $insert_value_arr[] = "'$v'";
            }
        }
        
        $insert_fields_str = implode(', ', $insert_fields_arr);
        $insert_value_str = implode(', ', $insert_value_arr);
        
        $sql = "INSERT INTO `{$this->tableName}` ({$insert_fields_str}) VALUES ($insert_value_str)";
        write_error_log($sql);
        $this->query($sql);
        
        if ($this->result && $this->mysqli->affected_rows > 0) {
            if ($this->mysqli->insert_id) {
                return $this->mysqli->insert_id;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function _addslashes(&$string){
        $magic_quotes_gpc = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc();
        if (!$magic_quotes_gpc) {
            $string = _addslashes($string);
        }
    }
    
    function batchInsert ($data) {
        $this->_addslashes($data);
        
        $this->_mustSetTableName();
        if (empty($data)) {throw new Exception('No data, what do I insert');}
        //获取表字段
        $this->getFields();
        
        $insert_fields_str = $insert_value_str = '';
        $insert_value_str_arr = array();
        foreach ($data as $tmp_data) {
            $insert_fields_arr = $insert_value_arr = array();
            foreach ($tmp_data as $k => $v) {
                if (in_array($k, $this->fields)) {
                    $insert_fields_arr[] = "`$k`";
                    $insert_value_arr[] = "'$v'";
                }
            }
            $insert_fields_str = implode(', ', $insert_fields_arr);
            $insert_value_str = implode(', ', $insert_value_arr);
            $insert_value_str_arr[] = "($insert_value_str)";
        }
        
        $insert_value_str_arr_str = implode(', ', $insert_value_str_arr);
        
        $sql = "INSERT INTO `{$this->tableName}` ({$insert_fields_str}) VALUES $insert_value_str_arr_str";
        $this->query($sql);
        
        if ($this->result && $this->mysqli->affected_rows > 0) {
            if ($this->mysqli->insert_id) {
                return $this->mysqli->insert_id;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    //如果数组$data中包含有主键，并且更新条件为主键，则不需要传入$where参数
    function update ($data, $where = array()) {
        $this->_addslashes($data);
        
        $this->_mustSetTableName();
        if (empty($data)) {throw new Exception('No data, what do I update');}
        
        $this->getFields();
        $where_str = $this->_render_where($where, $where ? 0 : 1, $data);
        if (empty($where_str)) {throw new Exception(
            'what is the conditions in update function ');}
        
        $set_fields_str = $this->_render_set_fields($data);
        $sql = "UPDATE `{$this->tableName}` SET $set_fields_str WHERE $where_str";
        return $this->query($sql);
    }

    function replace($data){
        $this->_addslashes($data);
        
        $this->_mustSetTableName();
        if (empty($data)) {
            throw new Exception('No data, what do I update');
        }
        $set = array();
        foreach($data as $k=>$v){
            $set[] = "`{$k}` = '{$v}'";
        }
        $sql = "REPLACE INTO `{$this->tableName}` SET ". implode(' , ', $set);
        return $this->query($sql);
    }
    
    function _render_set_fields ($data) {
        $set_fields_arr = array();
        foreach ($data as $k => $v) {
            //不更新主键
            if (in_array($k, $this->fields) &&
             ! in_array($k, $this->priKey)) {
                $set_fields_arr[] = " `$k` = '$v' ";
            }
        }
        
        $set_fields_str = implode(', ', $set_fields_arr);
        return $set_fields_str;
    }

    /**
     * $where 可以有以下几种写法
     * $where = " `name` ='test' ";
     * $where = array(" `id` =1 ", " `name` ='test' ");
     * $where = array('id' => 1,  'name' => 'test');
     * $where = array(array('id', 1),  array('name', '%test%', 'like'));
     */
    function _render_where ($where = null, $set_default = 0, $data = null) {
        if ($set_default && $this->priKey && $data) {
            foreach ($this->priKey as $k) {
                if (isset($data[$k])) {
                    $where[] = array($k, $data[$k]);
                }
            }
        }
        $conditions = array();
        $where_str = '';
        if (is_string($where)) {
            $where_str = $where;
        } elseif (is_array($where)) {
            foreach ($where as $k => $arr) {
                if (is_string($arr) || is_numeric($arr)) {
                    $str = $arr;
                    if(is_string($k) && !is_numeric($k)){
                        $this->_addslashes($str);
                        $str = "`{$k}` = '{$str}'";
                    }
                    $conditions[] = $str;
                } elseif (is_array($arr)) {
                    $tmp_count = count($arr);
                    if ($tmp_count == 1) {
                        $conditions[] = $arr[0];
                    } elseif ($tmp_count == 3) {
                        $this->_addslashes($arr[1]);
                        $conditions[] = "`{$arr[0]}` {$arr[2]} '{$arr[1]}'";
                    } elseif ($tmp_count == 2) {
                        
                        $this->_addslashes($arr[1]);
                        $conditions[] = "`{$arr[0]}` = '{$arr[1]}'";
                    }
                }
            
            }
            $where_str = implode(' AND ', $conditions);
        }
        
        return $where_str;
    }

    //删除数据
    function delete ($where = array()) {
        $this->_mustSetTableName();
        if (empty($where)) {return false;}
        
        $where_str = $this->_render_where($where);
        $sql = "DELETE FROM `{$this->tableName}` WHERE $where_str ";
        return $this->query($sql);
    }

    function deleteById ($id) {
        $this->_mustSetTableName();
        $where = $this->_render_where_from_id($id);
        return $this->delete($where);
    }

    function getOneById ($id) {
        $where = $this->_render_where_from_id($id);
        return $this->getOne($where);
    }

    function _render_where_from_id ($id) {
        $this->_addslashes($id);
        
        $where = array();
        $this->getFields();
        if ($id > 0 && count($this->priKey) == 1) {
            $where[] = array($this->priKey[0], $id);
        }
        return $where;
    }

    function _mustSetTableName () {
        if (empty($this->tableName)) {throw new Exception(
            'No tableName, you must setTableName() first');}
    }

    //获得一个数据列表
    function getList ($fields_arr = null, $where = null, $from = 0, $count = 0, $orderby = '') {
        $this->_mustSetTableName();
        //整理要查找的字段
        if(empty($fields_arr)){
            $fields_arr = '*';
        }
        
        if (!is_array($fields_arr)) {
            $fields_str = $fields_arr;
        } else {
            foreach ($fields_arr as $v) {
                $tmp_fields_arr[] = "`$v`";
            }
            $fields_str = implode(', ', $tmp_fields_arr);
        }
        
        //整理查询条件
        $where_str = $this->_render_where($where);
        if ($where_str == '') {
            $where_str = '1';
        }
        
        if (! empty($orderby)) {
            $where_str .= " ORDER BY $orderby";
        }
        if ($count > 0) {
            $where_str .= " LIMIT $from, $count";
        }
        
        $sql = "SELECT $fields_str FROM `{$this->tableName}` WHERE $where_str ";
        $this->query($sql);
        $data = array();
        while ($row = $this->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    //获得一条数据
    function getOne ($where = array()) {
        $list = $this->getList('*', $where);
        return $list[0];
    }

    //获得表字段，如果设置参数$full=true，则会返回表字段的完整信息，否则只会返回表字段名称的数组
    function getFields ($full = false) {
        if (! empty($this->fields) && $full==false) {return $this->fields;}
        if (! empty($this->full_fields) && $full==false) {
            return $this->full_fields;
        }
        $this->result = null;
        if (empty($this->tableName)) {throw new Exception(
            'No tableName, you must setTableName() first');}
        $sql = "SHOW FULL COLUMNS FROM `{$this->tableName}`";
        $this->query($sql);
        
        while ($row = $this->fetch_assoc()) {
            if ($full) {
                $this->full_fields[] = $row;
            } else {
                $this->fields[] = $row['Field'];
            }
            
            if ($row['Key'] == 'PRI') {
                $this->priKey[] = $row['Field'];
            }
        }
        return $full? $this->full_fields: $this->fields;
    }
    
    function count ($where = null, $distinct_field=null) {
        //整理查询条件
        $where_str = $this->_render_where($where);
        if (empty($where_str)) {
            $where_str = '1';
        }
        
        if($distinct_field){
            $count = "COUNT(DISTINCT(`{$distinct_field}`))";
        }else{
            $count = "COUNT(*)";
        }
        $sql = "SELECT {$count} AS count_data FROM `{$this->tableName}` WHERE $where_str ";
        $this->query($sql);
        $data = array();
        $row = $this->fetch_assoc();
        return $row['count_data'];
    }
    
    function sum ($field, $where = null) {
        //整理查询条件
        $where_str = $this->_render_where($where);
        if ($where_str == '') {
            $where_str = '1';
        }
    
        $sql = "SELECT SUM(`$field`) AS sum_data FROM `{$this->tableName}` WHERE $where_str ";
        $this->query($sql);
        $data = array();
        $row = $this->fetch_assoc();
        return $row['sum_data'];
    }
    
    function array2Str($arr){
        $tmp_arr = array();
        foreach($arr as $tmp){
            $tmp_arr[] = "'{$tmp}'";
        }
        $str = implode(",", $tmp_arr);
        return $str;
    }
}