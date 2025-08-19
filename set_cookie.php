<?php 
	require_once '../../secured/assets/config/config.php';
	require_once 'functions.php';
	require_once 'variables.php';

	if (isset($_GET['selection'])) {$typeRequest = $_GET['selection'];}else{$typeRequest = '';}
	$cookieTime = (float )$systemData['cookieLife'];
//	echo $systemData['cookieLife']."<br/>";
//	die($cookieTime);
	switch ($typeRequest) {
		case 'all':
			setCookieValue('cookiesAccept', 'all', $cookieTime);
			break;
		case 'necessary':
			setCookieValue('cookiesAccept', 'necessary', $cookieTime);
			break;
	}

	if (isset($_SERVER['HTTP_REFERER'])) {
		$refURL = $_SERVER['HTTP_REFERER'];
	} else {
		$refURL = '';
	}

	$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $refURL;
	$separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
	$url = $refURL;

//	echo $url . $separator . '<hr/>';
//	echo $url . '<br/>';
//	echo 'Request Value: '. $_COOKIE['cookiesAccept'] . '<br/>';
//	die('Request Value: '. $typeRequest);
//	die(getCookieValue('cookiesAccept'));


//	setcookie('cookiesAccept', 'ALL', time()+3600, '/');

	header('Location: ' . $url);
	exit;



//	$url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
//	. $_SERVER['HTTP_HOST']
//	. $refURL;
//	$separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
//	die($url . $separator);
//	header('Location: ' . $url . $separator . 'cookie_check=1');
//	exit;
?>