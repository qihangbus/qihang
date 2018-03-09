<?php

function getURL($url){

	//初始化
	$ch = curl_init();
	//设置选项，包括URL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT,5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
	//释放curl句柄
	curl_close($ch);
	//返回获得的数据
	return $output;
}

/**
 * curl post提交
 */
function postURL($url, $post_data){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// post数据
	curl_setopt($ch, CURLOPT_POST, 1);
	// post的变量
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$output = curl_exec($ch);
	curl_close($ch);

	//返回获得的数据
	return $output;
}


/**
 *
 * 把数组按指定的个数分隔
 * @param array $array 要分割的数组
 * @param int $groupNum 分的组数
 */
function splitArray($array, $groupNum){
	if(empty($array)) return array();
	
	//数组的总长度
	$allLength = count($array);
	
	//个数
	$groupNum = intval($groupNum);
	
	//开始位置
	$start = 0;
	
	//分成的数组中元素的个数
	$enum = (int)($allLength/$groupNum);
	
	//结果集
	$result = array();
	
	if($enum > 0){

		//被分数组中 能整除 分成数组中元素个数 的部分
		$firstLength = $enum * $groupNum;
		$firstArray = array();
		for($i=0; $i<$firstLength; $i++){
			array_push($firstArray, $array[$i]);
			unset($array[$i]);
		}
		for($i=0; $i<$groupNum; $i++){
			
			//从原数组中的指定开始位置和长度 截取元素放到新的数组中
			$result[] = array_slice($firstArray, $start, $enum);
			
			//开始位置加上累加元素的个数
			$start += $enum;
		}
		//数组剩余部分分别加到结果集的前几项中
		$secondLength = $allLength - $firstLength;
		for($i=0; $i<$secondLength; $i++){
			array_push($result[$i], $array[$i + $firstLength]);
		}
	}else{
		for($i=0; $i<$allLength; $i++){
			$result[] = array_slice($array, $i, 1);
		}
	}
	return $result;
}

//中文字符串截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)  
    {  
  	if(function_exists("mb_substr")){  
        if($suffix)  
            return mb_substr($str, $start, $length, $charset);  
        else
            return mb_substr($str, $start, $length, $charset);  
    }elseif(function_exists('iconv_substr')) {  
        if($suffix)  
            return iconv_substr($str,$start,$length,$charset);  
        else
            return iconv_substr($str,$start,$length,$charset);  
    }  
	$re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
	      [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  
	$re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  
	$re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  
	$re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  
	preg_match_all($re[$charset], $str, $match);  
	$slice = join("",array_slice($match[0], $start, $length));  
	if($suffix) return $slice."";  
	return $slice;
}

//生成订单编号
function get_order_sn()
{
    mt_srand((double) microtime() * 1000000);
    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

//求二维数组的差集
function array_diff_assoc2_deep($array1, $array2) {
	$ret = array();
	foreach ($array1 as $k => $v) {    
	if (!isset($array2[$k])) $ret[$k] = $v;
	else if (is_array($v) && is_array($array2[$k])) $ret[$k] = array_diff_assoc2_deep($v, $array2[$k]);
	else if ($v !=$array2[$k]) $ret[$k] = $v;
	else
	{
		unset($array1[$k]);
	}
   
	}
	return $ret;
} 

//提醒日期设置
function damage($t)
{
    $arr = array();
    for($i=1;$i<=10;$i++){
        $d = $i*3 + 7;
        $m = date('Ymd',strtotime("$t +$d day"));
        array_push($arr,$m);
    }
    return $arr;
}

//导入
function import_csv($handle)
{
    $out = array();
    $n = 0;
    while($data = fgetcsv($handle,10000))
    {
        $num = count($data);
        for($i=0;$i<$num;$i++) 
        {
            $out[$n][$i] = $data[$i];
        }
        $n++;
    }
    return $out;
}

//导出
function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;
}  


function curlinit($url)
{
    $curl = curl_init();
      curl_setopt ($curl, CURLOPT_URL, $url);
      curl_setopt ($curl, CURLOPT_HEADER,0);
      curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
      curl_setopt ($curl, CURLOPT_TIMEOUT,5);
      $get_content = curl_exec($curl);
      curl_close ($curl);
      return $get_content;
}

function objectToArray($obj){
    $arr = is_object($obj) ? get_object_vars($obj) : $obj;
    if(is_array($arr)){
        return array_map(__FUNCTION__, $arr);
    }else{
        return $arr;
    }
}

function dealFiles($files) {
    $fileArray  = array();
    $n          = 0;
    foreach ($files as $key=>$file){
        if(is_array($file['name'])) {
            $keys       =   array_keys($file);
            $count      =   count($file['name']);
            for ($i=0; $i<$count; $i++) {
                $fileArray[$n]['key'] = $key;
                foreach ($keys as $_key){
                    $fileArray[$n][$_key] = $file[$_key][$i];
                }
                $n++;
            }
        }else{
           $fileArray[$key] = $file;
        }
    }
   return $fileArray;
}

//判断是否微信
function is_weixin()
{ 
    if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) 
    {
        return true;
    }  
    return false;
}


function curl_post_send_information($token,$vars,$second=120,$aHeader=array())  
{  
    $ch = curl_init();  
    //超时时间  
    curl_setopt($ch,CURLOPT_TIMEOUT,$second);  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);  
    //这里设置代理，如果有的话  
    curl_setopt($ch,CURLOPT_URL,'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token);  
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);  
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);  
    if( count($aHeader) >= 1 ){  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);  
    }  
    curl_setopt($ch,CURLOPT_POST, 1);  
    curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);  
    $data = curl_exec($ch);  
    if($data){  
        curl_close($ch);  
        return $data;  
    }  
    else {  
        $error = curl_errno($ch);  
        curl_close($ch);  
        return $error;  
    }  
} 


function update_access_token($options)
{
	$appid = $options['appid'];
	$appsecret = $options['appsecret'];
	
	//TODO: get the cache access_token
	$result = http_get('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret);
	if ($result)
	{
		$json = json_decode($result,true);
		if (!$json || isset($json['errcode'])) {
			return false;
		}
		$access_token = $json['access_token'];
		$expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;
		//TODO: cache access_token
		return $access_token;
	}
}

function sendTemplateMessage($access_token,$data){
	$result = http_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token,$data);
	
	if ($result)
	{
		$json = json_decode($result,true);
		if (!$json || !empty($json['errcode'])) {
			return false;
		}
		return $json;
	}
	return false;
}

function http_get($url){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);

	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}

function http_post($url,$param){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
	}
	if (is_string($param)) {
		$strPOST = $param;
	} else {
		$aPOST = array();
		foreach($param as $key=>$val){
			$aPOST[] = $key."=".urlencode($val);
		}
		$strPOST =  join("&", $aPOST);
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($oCurl, CURLOPT_POST,true);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}