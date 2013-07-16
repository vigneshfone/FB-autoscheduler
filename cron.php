<?php

/*
 * This is cron file to schedule the facebook Sharing
 */

require_once 'src/facebook.php';
require_once 'functionsClass.php';
session_start();
//print_r($_SESSION);

$app_id = APP_ID;
$app_secret = APP_SECRET;
$app_url = $server='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//$app_url= APP_URL;
$scope = 'email,publish_actions,publish_stream,photo_upload,manage_pages,read_stream';
$queueRequest = new functionsClass();
$queue=$queueRequest->pushQueue();
if(!is_null($queue)){
$message = $queue['status_message'];
$links= $queue['status_link'];
$statusImageLink= $queue['status_image_link'];
$photoLink = $queue['photo_link'];
$id=$queue['id'];
$isStatus = $queue['is_status'];
}else {
    echo 'No records Found!';
    exit;
}
$facebook = new Facebook(array(
         'appId'  => $app_id,
         'secret' => $app_secret,
));


// Get the current user
//$user = $facebook->getUser();
$user = "1397234163";
//print_r($user);exit;
// If the user has not installed the app, redirect them to the Login Dialog
/* if (!$user) {
        $loginUrl = $facebook->getLoginUrl(array(
        'scope' => $scope,
	'methods'=> 'POST',
        'redirect_uri' => $app_url,
        ));

        print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
}else{
 */
    //$access_token = $facebook->getAccessToken();
    $access_token = $queueRequest->getAccessToken();
	//print($access_token['accesstok']);exit;
    $fanpage_token = '';
    $params = array('access_token' => $access_token['accesstok']);
    $fanpage = FANPAGE_ID;
    $accounts = $facebook->api('/'.$user.'/accounts', 'GET', $params);
    foreach($accounts['data'] as $account) {
            if( $account['id'] == $fanpage || $account['name'] == $fanpage ){
                    $fanpage_token = $account['access_token'];
            }
    }
    
    if (!empty($isStatus)){
        $status = "/feed";
    }  else {
        $status = "/photos";
    }
    
    //$links = 'http://bit.ly/18ULWyC';
   // $message = "Nvidia announces GPU licensing starting with Kepler, to compete with PowerVR \n\n".$links;
   try {
       
       
    $photoId = $facebook->api(''.$fanpage.$status,"POST",array(
                                        'access_token'=>$fanpage_token,
                                        'url'=>$photoLink,
                                        'message'=>$message,
                                        'link' => $links,
                                        'picture'=>$statusImageLink
                                        ));
        if ($photoId['id']){
            $queue=$queueRequest->makeArchieved($id);
        }
    }catch (Exception $e){
     echo 'failure'.$e->getMessage();
     }

    

//}

?>
