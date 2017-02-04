<title>Kill IP</title>
Die Die Die<br>
Your IP:
<?php
 if(getenv('HTTP_CLIENT_IP')) {
   $onlineip = getenv('HTTP_CLIENT_IP');
 } elseif(getenv('HTTP_X_FORWARDED_FOR')) {
   $onlineip = getenv('HTTP_X_FORWARDED_FOR');
 } elseif(getenv('REMOTE_ADDR')) {
   $onlineip = getenv('REMOTE_ADDR');
 } else {
   $onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
 }
 echo $onlineip;
 $redis = new redis(); 
 $redis->connect('127.0.0.1', 6379);  
 $redis->auth("yourpasscode");
 $ipis = $redis->keys('*.*');
 $redis->set($onlineip,-200);
 $redis->expire($onlineip,3600);
 $redis->sadd("ips",$onlineip);
    if(in_array($onlineip,$ipis))
      {
       exit('<br>Too young Too simple Too naive');
      }
?>
