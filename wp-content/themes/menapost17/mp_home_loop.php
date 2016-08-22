<?php global $deviceType;
$cat1=2; $cat2=3; $cat3=11;
$google_agents = array('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)','Googlebot/2.1 (+http://www.google.com/bot.html)');
if($deviceType == "mobile"){$cat1=2; $cat2=0; $cat3=0;}
else if($deviceType == "tablet"){$cat1=2; $cat2=3; $cat3=10;}
?>
<?php include ('views/columnHeadersHome.php'); ?>
<?php if(is_home() && array_search($_SERVER['HTTP_USER_AGENT'], $google_agents) === false && strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') === false) {
	require_once __DIR__.'/views/homepage.php';
} else {
	require_once __DIR__.'/inc/botdataqueries.php';
	require_once __DIR__.'/views/botpage.php';
} ?>


