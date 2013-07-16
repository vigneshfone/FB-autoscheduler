<?php
//Always place this code at the top of the Page
ini_set('display_errors',0);
include_once 'usersClass.php';
session_start();
	if(!empty($_REQUEST)){
	$errmsg_arr = array();
	$errflag = false;
	$user = new User();
	
	if($_REQUEST['button'] == 'register'){
		//Sanitize the POST values
	 $login = $user->clean($_REQUEST['register']);
	 $email = $user->clean($_REQUEST['email']);
	 $password = $user->makeSaltedHash($_REQUEST['password'],'5');
		
	if($login == '' || empty($login)) {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '' || empty($password)) {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	if($email == '' || empty($email)) {
		$errmsg_arr[] = 'Email id missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		header("location:index.php?msg=error");
		exit();
	}
	
		if($result = $user->insertUser($login, $email, $password)) {
			$_SESSION['id'] = $result['userID'];
			//Put name in session
			$_SESSION['username'] = $result['username'];			
			
			//Close session writing
			session_write_close();
	
			//Redirect to user's page
			header("location: home.php");
			exit();
		}else {
			//If Login failed redirect to login page
 			header("location: index.php?msg=error");
			exit();
		}
	}
	
	if($_REQUEST['button'] == 'login'){
	//Sanitize the POST values
	 $login = $user->clean($_POST['login']);
	 $password = $user->clean($_POST['password']);
	 $password = $user->makeSaltedHash($_POST['password'],'5');
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location:index.php?msg=error");
		exit();
	}
	
		if($result = $user->checkUserNormal($login, $password)) {

					
			//Set session
			$_SESSION['id'] = $result['userID'];
			//Put name in session
			$_SESSION['username'] = $result['username'];
			session_write_close();
			//Redirect to user's page
			header("location: home.php");
			exit();
		}else {
			//If Login failed redirect to login page
			$_SESSION['ERRMSG_ARR'] = 'Invalid login credentials';
 			header("location: index.php?msg=error");
			exit();
		}
		
	}
	}
?>
<title>Fonearena Login</title>
<style type="text/css">
    #buttons
	{
	text-align:center
	}
    #buttons img,
    #buttons a img
    { border: none;}
	h1
	{
	font-family:Arial, Helvetica, sans-serif;
	color:#999999;
	}
	#overlay {
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
	z-index:-1;
}
#modal {
    position:absolute;
    border-radius:14px;
    padding:8px;
	 left: 28%;
     top: 25%;
	 visibility:hidden;
	 background:white;
	 z-index:1000;
}

#content {
    border-radius:8px;
    background:#fff;
    padding:20px;
}
#close {
    position:absolute;
    background:url(close.png) 0 0 no-repeat;
    width:24px;
    height:27px;
    display:block;
    text-indent:-9999px;
    top:0px;
    right:5px;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<?php
if($_GET['msg']=="error")
{
?>
<div style="padding:6px; background:#FDE0CE; color:#FD5E5E; font-weight:bold; border:solid 1px #FE9592; text-align:center;">Error!! <?php echo $_SESSION['ERRMSG_ARR'];?></div>
<div style="padding:6px; background:#FDE0CE; color:#FD5E5E; font-weight:bold; border:solid 1px #FE9592; text-align:center;"><a href="index.php">Login Again</a></div>
<?php
}elseif($_GET['msg']=="logout")
{
?><div style="background:#DCFDC8; border:solid 1px #A0EB70; color:#030; text-align:center; font-weight:bold; padding:4px;">Logged out successfully!!</div>
<?php
}else{
?>
<div style="margin:0 auto; padding:8px 4px; text-align:center; width:320px;">
<form method="post" action="index.php" name="loginForm">
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="20%" align="right" valign="middle">Username</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="login" type="text" id="login" placeholder="Enter Name" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password" placeholder="Enter your password" required />
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="button" id="button" value="login" />
    </label><label>
      <input type="button" name="register" id="register_button" value="register" />
    </label></td>
	 
  </tr>
</table>

</form>
</div>
<div id='overlay'></div>
<div id='modal'>
<form method="post" action="index.php" name="registerForm">
<table width="500" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="20%" align="right" valign="middle">Username</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="register" type="text" id="register" required /><span id="availability_status"></span>
    </label></td>
  </tr>
   <tr>
    <td width="20%" align="right" valign="middle">Email</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="email" type="email" id="email" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password1" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Re-Enter Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password2" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="button" id="button_reg" value="register" required />
    </label></td>
  </tr>
</table>

</form>
<a href="#" name="close" id="close">close</a>
</div>

<script>
$(document).ready(function(){
$('#register_button').click(function(){
	//alert("sdad");
	$('#modal').css("visibility","visible");
	$("#overlay").css({"z-index":10,"background":"rgba(0,0,0,0.5)"});
});
$('#close').click(function(){
	$('#modal').css("visibility","hidden");
	$("#overlay").css({"z-index":-1,"background":"none"});
});

$('#button_reg').click(function(){
var pwd1= $('#password1').val();
var pwd2= $('#password2').val();
if(pwd1=== pwd2){
//alert(pwd1+'---'+pwd2);
return true;
}else{
	alert("password donot match");
	return false;
}
});

$("#register").change(function()
{ //if theres a change in the username textbox

var username = $("#register").val();//Get the value in the username textbox
if(username.length > 3)//if the lenght greater than 3 characters
{
$("#availability_status").html('Checking availability...');

$.ajax({  //Make the Ajax Request
 type: "POST",
 url: "check-username.php",  //file name
 data: "username="+ username,  //data
 success: function(server_response){

 $("#availability_status").ajaxComplete(function(event, request){

 if(server_response == '0')
 {
 $("#availability_status").html('<font color="Green"> Available </font>  ');
  $("#button_reg").removeAttr("disabled");
 }
 else  if(server_response == '1')
 {
 $("#availability_status").html('<font color="red">Not Available </font>');
 $("#button_reg").attr("disabled", "disabled");
 }
 });
 }
 });
}
else
{
$("#availability_status").html('<font color="#cc0000">Username too short</font>');
}
return false;
});
});
</script>
<?php } ?>