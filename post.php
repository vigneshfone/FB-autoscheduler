<?php

require_once 'functionsClass.php';

if(isset($_REQUEST['submit'])){

    $obj = new functionsClass();
	
	if(!empty($_REQUEST['userId'])){
        $userId = $_REQUEST['userId'];
    }
	
    if(!empty($_REQUEST['statusMsg'])){
        $statusMsg = $_REQUEST['statusMsg'];
    }else{
        $statusMsg = '';
    }
    if(!empty($_REQUEST['datetime'])){
        $scheduledTime =  date("Y-m-d H:i:s",strtotime($_REQUEST['datetime']));
    }  else {
        $scheduledTime = date("Y-m-d H:i:s");
    }
        
    if(isset($_REQUEST['isStatus'])){
        if(!empty($_REQUEST['statusLink'])){
                $statusLink = $_REQUEST['statusLink'];
            if(!empty($_REQUEST['statusImageLink'])){
                $statusImageLink = $_REQUEST['statusImageLink'];
            }else {
                $statusImageLink = '';
            }            
        }else {
               $statusLink = '';
               $statusImageLink = '';
        }
        $obj->insertStatusLinks($statusMsg, $statusLink, $statusImageLink, $scheduledTime,$userId);
        
    }else{      
        /** Check whether the Photo link is empty or not **/
        if(!empty($_REQUEST['photoLink'])){
            echo $url=  mysql_real_escape_string($_REQUEST['photoLink']);
            $obj->insertImage($statusMsg,$url, $scheduledTime,$userId);
        }else{
         /** If the image link is empty, it'll take image upload**/
         if(isset($_FILES['photoUpload']))
        {
            $uploadfile = UPLOAD_FOLDER.preg_replace('/\s+/', '-', basename($_FILES['photoUpload']['name']));
            if (file_exists(UPLOAD_FOLDER) && is_writable(UPLOAD_FOLDER)) {
                if (move_uploaded_file($_FILES['photoUpload']['tmp_name'], $uploadfile)) {
                    $size = getimagesize($uploadfile);
                    $type = $size['mime'];
                    if($type == "image/jpeg" || $type == "image/png")
                    {
                   //echo $uploadfile;
                    echo $url= $obj->FTPCopy($uploadfile,FTP_SERVER,FTP_USERNAME,FTP_PASSWORD);
                    $obj->insertImage($statusMsg,$url,$scheduledTime,$userId);
                    }
                    else
                    {
                       echo "Uploaded file is not a valid image";
                    }
                } else {
                    var_dump($_FILES);
                }
            }
            else {
                echo 'Upload directory is not writable, or does not exist.';
            }

        }
        }
    }
}

?>
