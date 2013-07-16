<?php

/**
 * Description of functionsClass
 *
 * @author Vignesh M
 */
include_once 'constants.php';
class functionsClass {
    //put your code here
    public function __construct()
    {
    $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('Oops connection error -> ' . mysql_error());
    mysql_select_db(DB_DATABASE, $connection)  or die('Database error -> ' . mysql_error());
    
    }
    
    public function insertImage($statusMsg,$url,$scheduledTime,$userId){
        
        $sql = "INSERT INTO `share` (`id`, `status_message`, `photo_link`, `date_time`, `is_status`, `is_queued`,`user_id`,`created`) VALUES (NULL, '{$statusMsg}','{$url}', '{$scheduledTime}', '0', '1','{$userId}',NOW());";
        
        if($results = mysql_query($sql)){
            $status ="success";
        }else{            
            $status ="error";
        }
        header("Location: home.php?status=$status");

    }
    
    public function insertStatusLinks($statusMsg, $statusLink, $statusImageLink, $scheduledTime,$userId){
         $sql = "INSERT INTO `share` (`id`, `status_message`, `status_link`, `status_image_link`, `date_time`, `is_status`, `is_queued`,`user_id`,`created`) VALUES (NULL, '{$statusMsg}','{$statusLink}', '{$statusImageLink}','{$scheduledTime}', '1', '1','{$userId}',NOW());";
         if($results = mysql_query($sql)){            
            $status = 'success';
        }else{            
            $status ="error";
        }
        header("Location: home.php?status=$status");
    }

    public function queuedRequest(){
        $sql = "SELECT * FROM `share` sh join `users` us on sh.user_id=us.userID where `is_queued` = 1 ORDER BY `date_time` asc";
        $results = mysql_query($sql);
	$data=array();
	if(is_resource($results)){
            $count = mysql_num_rows($results);
            if($count){
                for($i=0;$i<$count;$i++){
                $data[] = mysql_fetch_assoc($results);
                }
            }
        }
        return $data;
    }

    public function archievedRequest(){
         $sql = "SELECT * FROM `share` sh join `users` us on sh.user_id=us.userID where `is_queued` = 0 ORDER BY `date_time` desc LIMIT 5";
        $results = mysql_query($sql);
	$data=array();
	if(is_resource($results)){
            $count = mysql_num_rows($results);
            if($count){
                for($i=0;$i<$count;$i++){
                $data[] = mysql_fetch_assoc($results);
                }
            }
        }
        return $data;
    }
    
    public function pushQueue(){
        //$sql = "SELECT * FROM `share` where `is_queued` = 1 ORDER BY `date_time` asc LIMIT 1";
        $sql = "SELECT * FROM `share` WHERE `is_queued` = 1 AND `date_time` BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 10 MINUTE) ORDER BY `date_time` asc LIMIT 1";
        $results = mysql_query($sql);	
	if(is_resource($results)){
            $count = mysql_num_rows($results);
            if($count){
               $data = mysql_fetch_assoc($results);
               
            }  else {
                $data = NULL;
            }
        }
        return $data;
    }
	
	 public function getAccessToken(){
       $sql = "SELECT accesstok FROM `accesstoken` where `sno` = 1 LIMIT 1";
       
        $results = mysql_query($sql);	
	if(is_resource($results)){
            $count = mysql_num_rows($results);
            if($count){
               $data = mysql_fetch_assoc($results);
               
            }  else {
                $data = NULL;
            }
        }
        return $data;
    }
	
    public function makeArchieved($id){
        $sql = "UPDATE `share` SET `is_queued` = '0' WHERE `id` = $id";
        mysql_query($sql);
        $affRows = mysql_affected_rows();
        echo "Records affected: " . $affRows;

    }
	
	public function setAccessToken($accessToken){
        $sql = "update accesstoken set accesstok='".$accessToken."' where sno='1'";
        mysql_query($sql);
        $affRows = mysql_affected_rows();
        echo "Records affected: " . $affRows;

    }

    public function FTPCopy($source,$ftp_server,$ftp_user_name,$ftp_user_pass)
    {

    // set up basic connection
    $conn_id = ftp_connect($ftp_server);

    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

    // turn ftp passive mode on
    ftp_pasv($conn_id, true);
    // check connection
    if ((!$conn_id) || (!$login_result)) {
        echo "FTP connection has failed!";
        exit;
    }
    else {
     //   echo "Connected to for user";
        // upload the file
    $upload = ftp_put($conn_id, FTP_UPLOAD_FOLDER.$source, $source, FTP_BINARY);

    // check upload status
    if (!$upload) {
        echo "FTP upload has failed!";
    } else {
        //echo "Uploaded";
        $url="http://faindia.org/".$source;
        
    }
    }
    // close the FTP stream
    ftp_close($conn_id);
    return $url;
    }
    
}

?>
