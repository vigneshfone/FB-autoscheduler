<?php
include_once 'style.php';
require_once 'src/facebook.php';
require_once 'functionsClass.php';
session_start();
//print_r($_SESSION);
if(isset($_SESSION['id'])){
$app_id = APP_ID;
$app_secret = APP_SECRET;
$app_url = $server='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$scope = 'email,publish_actions,publish_stream,photo_upload,manage_pages,read_stream';

$facebook = new Facebook(array(
         'appId'  => $app_id,
         'secret' => $app_secret,
));

// Get the current user
$user = $facebook->getUser();

// If the user has not installed the app, redirect them to the Login Dialog
if (!$user) {
        $loginUrl = $facebook->getLoginUrl(array(
        'scope' => $scope,
		'methods'=> 'POST',
        'redirect_uri' => $app_url,
        ));

        print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
}else{
	$facebook->setExtendedAccessToken();
	$access_token=$facebook->getAccessToken();
	echo $access_token;
	$obj = new functionsClass();
	$obj->setAccessToken($access_token);
	
}

}else{
	header("location: index.php");
}
?>