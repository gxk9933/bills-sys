<?php
require(ROOT.'/writable/config.inc.php');
require_once (ROOT . '/includes/define.inc.php');


/**
 * 设置错误处理和异常处理的方法
 * 写入日志
 */
set_error_handler('_custom_error_handler');
set_exception_handler('_custom_exception_handler');
session_start();
header("Content-type: text/html;charset=UTF-8");
//根据配置文件设置时区
$timezone = defined('TIMEZONE') ? TIMEZONE : DEFAULT_TIMEZONE;
date_default_timezone_set($timezone);

//引入数据库mysqli类
require_once (ROOT . '/includes/database.inc.php');

function print_page () {
    require_once (ROOT . '/includes/router.inc.php');
}


function _addslashes ($string) {
    if (is_array($string)) {
        $keys = array_keys($string);
        foreach ($keys as $key) {
            $val = $string[$key];
            unset($string[$key]);
            $string[addslashes($key)] = _addslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}



//自定义php的错误处理方法
function _custom_error_handler ($errno, $errstr, $errfile, $errline) {
    //整理输出的内容
    if ($errno == E_NOTICE) return;
    $types = _error_levels();
    $severity_msg = $types[$errno] ? $types[$errno] : 'Unknown error';
    $message = "PHP {$severity_msg[0]} : $errstr in File $errfile on Line $errline";
    //错误保存到文件中
    write_error_log($message);
}

function _custom_exception_handler ($exception) {
    $errstr = $exception->getMessage();
    $backtrace = $exception->getTrace();
    $errline = $exception->getLine();
    $errfile = $exception->getFile();
    $message = "Exception : $errstr in File $errfile on Line $errline";
    write_error_log($message);
    echo 'there is some error in the log';
}

function write_error_log ($message, $type='error') {
    $time = date('Y-m-d H:i:s');
    $message = "[ {$time} ] {$message}\n";
    
    $dir = LOG_DIR.'/'.$type;
    xmkdir($dir);
    
    $file = $dir.'/' . date("Y-m") . '.log';
    error_log($message, 3, $file);
}


function _error_levels () {
    $types = array(E_ERROR => array('Error'), E_WARNING => array('Warning'), 
    E_PARSE => array('Parse error'), E_NOTICE => array('Notice'), 
    E_CORE_ERROR => array('Core error'), E_CORE_WARNING => array('Core warning'), 
    E_COMPILE_ERROR => array('Compile error'), 
    E_COMPILE_WARNING => array('Compile warning'), 
    E_USER_ERROR => array('User error'), E_USER_WARNING => array('User warning'), 
    E_USER_NOTICE => array('User notice'), E_STRICT => array('Strict warning'), 
    E_RECOVERABLE_ERROR => array('Recoverable fatal error'));
    // E_DEPRECATED and E_USER_DEPRECATED were added in PHP 5.3.0.
    if (defined('E_DEPRECATED')) {
        $types[E_DEPRECATED] = array('Deprecated function');
        $types[E_USER_DEPRECATED] = array('User deprecated function');
    }
    return $types;
}


function random ($length, $numeric = 0) {
    if ($numeric) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}


function chinese_substr ($text, $start, $length = NULL, $ex = 2) {
    $strlen = strlen($text);
    // Find the starting byte offset
    $bytes = 0;
    if ($start > 0) {
        // Count all the continuation bytes from the start until we have found
        // $start characters
        $bytes = - 1;
        $chars = - 1;
        while ($bytes < $strlen && $chars < $start) {
            $bytes ++;
            $c = ord($text[$bytes]);
            if ($c < 0x80 || $c >= 0xC0) {
                if ($c >= 0xC0) {
                    $chars += $ex;
                } else {
                    $chars ++;
                }
            }
        }
    } else if ($start < 0) {
        // Count all the continuation bytes from the end until we have found
        // abs($start) characters
        $start = abs($start);
        $bytes = $strlen;
        $chars = 0;
        while ($bytes > 0 && $chars < $start) {
            $bytes --;
            $c = ord($text[$bytes]);
            if ($c < 0x80 || $c >= 0xC0) {
                if ($c >= 0xC0) {
                    $chars += $ex;
                } else {
                    $chars ++;
                }
            }
        }
    }
    $istart = $bytes;
    
    // Find the ending byte offset
    if ($length === NULL) {
        $bytes = $strlen - 1;
    } else if ($length > 0) {
        // Count all the continuation bytes from the starting index until we have
        // found $length + 1 characters. Then backtrack one byte.
        $bytes = $istart;
        $chars = 0;
        while ($bytes < $strlen && $chars < $length) {
            $bytes ++;
            $c = ord($text[$bytes]);
            if ($c < 0x80 || $c >= 0xC0) {
                if ($c >= 0xC0) {
                    $chars += $ex;
                } else {
                    $chars ++;
                }
            }
        }
        $bytes --;
    } else if ($length < 0) {
        // Count all the continuation bytes from the end until we have found
        // abs($length) characters
        $length = abs($length);
        $bytes = $strlen - 1;
        $chars = 0;
        while ($bytes >= 0 && $chars < $length) {
            $c = ord($text[$bytes]);
            if ($c < 0x80 || $c >= 0xC0) {
                if ($c >= 0xC0) {
                    $chars += $ex;
                } else {
                    $chars ++;
                }
            }
            $bytes --;
        }
    }
    $iend = $bytes;
    
    return substr($text, $istart, max(0, $iend - $istart + 1));
}


function check_ip($ip){
    if(!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) {
        return false;
    }
    $ip_arr = explode('.', $ip);
    foreach($ip_arr as $num){
        if($num > 255) return false;
    }
    return true;
}


//随机生成用户指定个数的字符串
function create_rand_code ($num=6) { 
    for ($i = 0; $i < $num; $i ++) {
        $number = rand(0, 2);
        switch ($number) {
            case 0:
                $rand_number = rand(48, 57);
                break; //数字
            case 1:
                $rand_number = rand(65, 90);
                break; //大写字母
            case 2:
                $rand_number = rand(97, 122);
                break; //小写字母
        }
        $ascii = sprintf("%c", $rand_number);
        $ascii_number = $ascii_number . $ascii;
    }
    return $ascii_number;
}



//获取用户ip
function get_ip() {
    if ($ip = getenv('HTTP_CLIENT_IP'));
    elseif ($ip = getenv('HTTP_X_FORWARDED_FOR'));
    elseif ($ip = getenv('HTTP_X_FORWARDED'));
    elseif ($ip = getenv('HTTP_FORWARDED_FOR'));
    elseif ($ip = getenv('HTTP_FORWARDED'));
    else    $ip = $_SERVER['REMOTE_ADDR'];
    return  $ip;
}





function getJson($file){
	$content = file_get_contents(ROOT."/config/json/".$file);
	$pattern = "/\/\*(\s|\S)*?\*\/|\/\/.*?(\r\n|\n|\r)/";//过滤多行和单行注释
	$content = preg_replace($pattern, "", $content);
	$getJson = json_decode($content,true);
	return $getJson;
}

/**
 * @param $arr array 数据数组
 * @param $fileName string 生成的文件名
 * xxx\s123\iabc\n
 */
function fputexcel($fileName = 'excel', $arr, $type = 'array'){
	header("content-type:text/html; charset=utf-8");
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:attachment;filename=$fileName.xls");
	if($type == 'array'){
		$str = '';
		foreach($arr as $v1){
			$str .= '<tr>';
			foreach($v1 as $v2){
				if((string)$v2 == (string)intval($v2)){ // 数字
					if($v2 > 4294967296){
						$str .= '<td x:str class=xl2216681 nowrap>'.$v2.'</td>';
					}else{
						$str .= '<td x:num class=xl2216681 nowrap>'.$v2.'</td>';
					}
				}else{
					$str .= '<td x:str class=xl2216681 nowrap>'.$v2.'</td>';
				}
			}
			$str .= '</tr>';
		}
		$opt='
		    <html xmlns:o="urn:schemas-microsoft-com:office:office"
		    xmlns:x="urn:schemas-microsoft-com:office:excel"
		    xmlns="http://www.w3.org/TR/REC-html40">
		    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		    <html>
		    <head>
		    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
		    <style id="Classeur1_16681_Styles"></style>
		    </head>
		    <body>
		    <div id="Classeur1_16681" align=center x:publishsource="Excel">
		    <table border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse">
		    '.$str.'
		    </table>
		    </div>
		    </body>
		    </html>';
	}else{
		$opt = $arr;
	}
	die($opt);
}



function variable_get($name){
    static $variableAction;
    !$variableAction && $variableAction = new VariableAction();
    return $variableAction->variableGet($name);
}


function variable_set($name, $value){
    static $variableAction;
    !$variableAction && $variableAction = new VariableAction();
    return $variableAction->variableSet($name, $value);
}


function variable_delete($name){
    static $variableAction;
    !$variableAction && $variableAction = new VariableAction();
    return $variableAction->variableDelete($name);
}


function textareaStrToArr($str){
    $str = trim($str);
    $str = str_replace(';',',',$str);
    $str = str_replace('；',',',$str);
    $str = str_replace('，',',',$str);
    $str = str_replace(',',"\n", $str);
    $arr = explode("\n", $str);

    $data =  array();
    //防止在windows系统下数组中的元素出现\r的情况
    foreach($arr as $tmp){
        trim($tmp) && $data[] = trim($tmp);
    }
    return $data;
}


function xmkdir($dir){
    if(!file_exists($dir)){
        $result = mkdir($dir, 0777, true);
        $cmd = "chown -R www:www {$dir}";
        pclose(popen($cmd, "w"));
        return $result;
    }else{
        return true;
    }
}

function has_purview($ctrl, $act){
	$base = new base(false);
	require_once ROOT."/class/user.php";
	$userClass = new user($base);
    return $userClass->has_purview($ctrl, $act);
}

function upload_rename_rule(){
    static $count;
    $count ++;
    return 'file_'.time().'_'.$count;
}


class base {
    var $smarty;
	function __construct($page = true) {
		$this->base($page);
	}

    function base($page = true) {
    	$this->init_var();
    	
		    $this->init_db();		
			$this->init_template();
    	
	}
	
	function init_var() {
		$this->current_time = time();
		$cip = getenv('HTTP_CLIENT_IP');
		$xip = getenv('HTTP_X_FORWARDED_FOR');
		$rip = getenv('REMOTE_ADDR');
		$srip = $_SERVER['REMOTE_ADDR'];
		if($cip && strcasecmp($cip, 'unknown')) {
			$this->onlineip = $cip;
		} elseif($xip && strcasecmp($xip, 'unknown')) {
			$this->onlineip = $xip;
		} elseif($rip && strcasecmp($rip, 'unknown')) {
			$this->onlineip = $rip;
		} elseif($srip && strcasecmp($srip, 'unknown')) {
			$this->onlineip = $srip;
		}
		preg_match("/[\d\.]{7,15}/", $this->onlineip, $match);
		//获得用户ip
		$this->onlineip = $match[0] ? $match[0] : 'unknown';
	}
	
	function init_db(){
	    require_once ROOT.'/includes/database.inc.php';
	    $this->mysqli = new DbMysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, DB_NAME, MYSQLPORT);
	}
	
    function init_template () {
        require_once (ROOT . '/library/smarty/Smarty.class.php');
        $smarty = new Smarty();
        $smarty->debugging = false;
        $smarty->template_dir = ROOT . '/templates'; //设置模板目录
        $smarty->compile_dir = ROOT . '/templates_c'; //设置编译目录
        $smarty->left_delimiter = '<{';
        $smarty->right_delimiter = '}>';
        $smarty->php_handling = SMARTY_PHP_ALLOW;
        $this->smarty = $smarty;
    }
    
    
	function load($act) {
		static $acts;
	    if(empty($acts[$act])) {
	        require_once ROOT."/class/{$act}.php";
	        eval('$acts[$act] = new '.$act.'($this);');
	    }
	    return $acts[$act];
	}
	
	
	function theme_dataTables($searchFields, $showFields, $ajaxSource, $otherData=array(), $output=true){
	    if(!$this->_validSearchField($searchFields)){
	        echo 'search fields error';
	        return;
	    }
	    
	    
	    if(!$this->_validotherData($otherData)){
	        echo 'other data error';
	        return;
	    }
	    
	    if($output == false){
	        ob_start();
	    }
	    
	    $this->smarty->assign('searchFields', $searchFields);
	    $this->smarty->assign('showFields', $showFields);
	    $this->smarty->assign('ajaxSource', $ajaxSource);
	    $this->smarty->assign('otherData', $otherData);
	    $this->smarty->assign('aoColumns', $this->_render_aoColumns($showFields));
	    $this->smarty->display('common/data_table.htm');
	    
	    if($output == false){
	        $ret_val = ob_get_contents();
    	    ob_end_clean();
    	    return $ret_val;
	    }
	    
	}
	
	function _validSearchField(&$searchFields){
	    if(empty($searchFields))return true;
	    foreach($searchFields as $key=>$searchField){
	        empty($searchField['type']) && $searchField['type'] = 'input';
	        $types = array('input', 'date', 'range', 'select', 'range_date', 'range_time', 'year_month');
	        //类型限制
	        if(!in_array($searchField['type'], $types)){
	            return false;
	        }
	        
	        //如果是下拉菜单类型，必须定义下拉菜单的值
	        if($searchField['type'] == 'select' && empty($searchField['value'])){
	            return false;
	        }
	        
	        //默认宽度
	        !$searchField['input_width'] && $searchField['input_width'] = 120;
	        
	        if($searchField['type'] == 'range_time'){
	            $searchField['input_width'] < 200 && $searchField['input_width'] = 200;
	        }
	        
	        $searchFields[$key] = $searchField;
	    }
	    return true;
	}
	
	function _validotherData(&$otherData){
	    !$otherData['iDisplayLength'] && $otherData['iDisplayLength'] = 10;
	    $otherData['sortCol'] = intval($otherData['sortCol']);
	    !$otherData['sortDir'] && $otherData['sortDir'] = 'desc';
	    !$otherData['id'] && $otherData['id'] = 'dataTable';
	    if(!isset($otherData['bSort'])){
	        $otherData['bSort'] = "true";
	    } else{
	        $otherData['bSort'] = $otherData['bSort']? "true": "false";
	    }
	    return true;
	}
	
	function _render_aoColumns($showFields){
	    $aoColumns = array();
	    foreach($showFields as $item){
	        if($item['sortable'] == true){
	            $aoColumns[] = '{ "asSorting": [ "desc", "asc" ] }';
	        }else{
	            $aoColumns[] = '{ "bSortable": 0}';
	        }
	    }
	    return implode(",", $aoColumns);
	}
}