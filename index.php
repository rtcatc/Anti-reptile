<?php
function checkrobot($useragent=''){
	static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'search', 'lycos', 'robozilla');
	static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

	$useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
	if(strpos($useragent, 'https://') === false && dstrpos($useragent, $kw_browsers)) return false;
	if(dstrpos($useragent, $kw_spiders)) return true;
	return false;
}

function dstrpos($string, $arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

if(checkrobot()){
	exit('Are You Ghost?');}
?>
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
 $redis = new redis(); 
 $redis->connect('127.0.0.1', 6379);  
 $redis->auth("yourpasscode");
 $gettime = $redis->get($onlineip);
 if($gettime == null){
   $redis->set($onlineip,1);
   $redis->expire($onlineip,60);
 }else{
   if ($gettime <> -200){
     $gettime = $gettime + 1;
     $livetime = $redis->ttl($onlineip);
     $redis->set($onlineip,$gettime);  
     $redis->expire($onlineip,$livetime);}}
 $ips = $redis->keys('*.*');
 $howmany = count($ips);
 for ($x=0; $x<=$howmany; $x++){
   $frequency = $redis->get($ips[$x]);
     if ($frequency >= 20)
       {$redis->set($ips[$x],-200);
        $redis->expire($ips[$x],3600);}
   }
 $code200 = $redis->get($onlineip);
 if ($code200 == -200)
   {$redis->sadd("ips",$onlineip);
    exit('Since your visit is too ftequent,your IP has been blacklisted,please re-visit the page in an hour,thank you for your cooperation!');}
?>
