<!DOCTYPE html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7"> <![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>Energy Home</title>
        
        <meta name="description" content="">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cleartype" content="on">
        
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Energy Home">
        <meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
        <meta name="viewport"	content="width=320.1">
		<meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<link rel="apple-touch-icon" href="img/contact2.jpg" sizes="120x120">
		
       
<!-- iPad, portrait -->
<link href="img/splash3.png"
      media="(device-width: 768px) and (device-height: 1024px)
         and (orientation: portrait)
         and (-webkit-device-pixel-ratio: 1)"
      rel="apple-touch-startup-image">

<!-- iPhone 5 -->
<link href="img/splash2.png"
      media="(device-width: 320px) and (device-height: 568px)
         and (-webkit-device-pixel-ratio: 2)"
      rel="apple-touch-startup-image">

<!-- iPhone -->
<link href="img/splash.png"
      media="(device-width: 320px) and (device-height: 480px)
         and (-webkit-device-pixel-ratio: 1)"
      rel="apple-touch-startup-image">
        
        
        
        <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
       </head>
       <body>

<?php


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();
$local_ip = "109.90.224";
$iparr = split ("\.", $user_ip); 
$user_ip_clean = $iparr[0].".".$iparr[1].".".$iparr[2];
echo $local_ip;
echo "<br />";
echo $user_ip_clean;
echo "<br />";

if ($local_ip === $user_ip_clean) {
	echo 'JA';
} else {
	echo 'NEIN';
}
	
	
function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

if ($local_ip === $user_ip_clean) {
	redirect('http://192.168.192.6', false);
} else {
	redirect('http://109.90.224.73:1336', false);
}

?>

</body>
</html>
