<?php
	/*
	 * 服务端执行mysql连接 
	 */
 
	$server 	= isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : exit("数据库连接数据不全");
	$username 	= isset($_POST['username']) && !empty($_POST['username']) ? $_POST['username'] : exit("数据库连接数据不全");
	$password 	= isset($_POST['password']) ? $_POST['password'] : exit("数据库连接数据不全");
	$sql		= isset($_POST['sql']) && !empty($_POST['sql']) ? $_POST['sql'] : exit("数据库连接数据不全");
	
	$con = mysql_connect($server,$username,$password) or die("数据库出错: " . mysql_error());
	echo mysql_query($sql,$con);
	//echo mysql_num_rows($result);
	mysql_close($con);
?>