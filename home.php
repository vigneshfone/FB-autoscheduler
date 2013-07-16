<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_erors',0);
include_once 'style.php';
session_start();
//print_r($_SESSION);
if(isset($_SESSION['id'])){
/** Messages **/
define('SUCCESS_MSG', "Succesfully Posted and Scheduled!");
define('FAILURE_MSG', "Oops! Change a few things up and try submitting again!");
?>

<div class="hero-unit span10">
<a href="logout.php" class="btn btn-large btn-danger" style="float:right">Logout!</a>
    <h2>Facebook Auto Sharing!</h2>
    <h3>Welcome <?php echo $_SESSION['username'];?>!</h3>
    <?php if(isset($_REQUEST['status'])){ ?>
    <div class="alert alert-<?php echo $_REQUEST['status']; ?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><?php if($_REQUEST['status'] == 'success'){
                    echo SUCCESS_MSG;
                }  else {
                    echo FAILURE_MSG;
                }
        
        ?></h4>
    </div>
    <?php } ?>
    <div class="row-fluid span10" style="margin-top: 21px;">
    <form class="form-horizontal" name="share" id="share" action="post.php" method="post" enctype="multipart/form-data">
       <div class="row-fluid">
       <div class="span8">
        <div class="control-group">
        <label class="control-label">Select Status or Photo:</label>
            <div class="controls">
            <div class="switch" data-on="info" data-off="danger" data-on-label="Status" data-off-label="Photo" id="high">
                <input type="checkbox" id="isStatus" name="isStatus" value="1" checked/>
            </div>
            </div>
        </div>
       </div>
       </div>
        <div class="row-fluid">
             <div class="control-group">
            <label class="control-label">Type the Status Message:</label>
                <div class="controls">
                <textarea rows="5" class="span5" name="statusMsg" id="statusMsg" required="required"></textarea>
                </div>
            </div>
        </div>
        <div class="row-fluid" id="status">                   
            
            <div class="control-group">
            <label class="control-label">Type the URL for the Share:</label>
                <div class="controls">
                <input class="input-xlarge" type="text" name="statusLink" id="statusLink" placeholder="Link or Short URL">
                </div>
            </div>
            
            <div class="control-group">
            <label class="control-label">Type the Image URL for the link:</label>
                <div class="controls">
                <input class="input-xxlarge" type="text" name="statusImageLink" id="statusImageLink" placeholder="Image URL">
                </div>
            </div>
            
        </div>
        <div class="row-fluid" id="photo" style="display: none">
           
            <div class="control-group">
            <label class="control-label">Type the Image URL to upload:</label>
                <div class="controls">
                 <input class="input-xxlarge" type="text" name="photoLink" id="photoLink" placeholder="Image URL">
                </div>
            </div>
            
            
            <div class="control-group">
            <label class="control-label">Select the Image Path to upload:</label>
                <div class="controls">
                <input class="input-xlarge" type="file" name="photoUpload" id="photoUpload">
                </div>
            </div>
          
        </div>
        <div class="control-group">
            <label class="control-label">Select the Date and Time to Share:</label>
            <div class="controls">
            <div id="datetimepicker1" class="input-append date">
              <input data-format="dd/MM/yyyy hh:mm:ss" type="text" name="datetime" required="required"></input>
              <span class="add-on">
                <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
              </span>
            </div>
            </div>
         </div>
        <div class="control-group">
        <div class="controls">            
			<input type="hidden" name="userId" value="<?php echo $_SESSION['id'];?>"/>
            <button type="submit" name="submit" id="submit" class="btn btn-large btn-success">Share!</button>
			<a href="details.php" class="btn btn-large btn-info">View Scheduling</a>
            </div>
        </div>
    </form>
    </div>
</div>
</div>
<script type = "text/javascript">
$(document).ready(function()
{
$('button#submit').click(function(){
var ext = $('#photoUpload').val().split('.').pop().toLowerCase();
if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
    alert('Invalid extension! Use images!');
	return false;
}
});
var switcher = "on";
$('#high').on('switch-change', function (e, data) {
    if(data.value == true){
		switcher = "on";
                //alert(data.value);
		$('#photo').slideUp(500).hide();
                $('#status').slideDown(500);
	}else{
		$('#status').slideUp(500).hide();
                $('#photo').slideDown(500);		
		switcher = "off";
               // alert(data.value);
	}
});
var minDate = new Date(); // Our minimum

$('#datetimepicker1').datetimepicker({
      format: 'MM/dd/yyyy hh:mm:ss',
      language: 'en',
      pick12HourFormat: true,
      maskInput: true
    }).on('changeDate', function(ev){
    if (ev.date.valueOf() < minDate.valueOf()){
        /* Handle previous date */
        alert('Select new Date! No expired Dates!');
        ev.preventDefault();
        return false;
    }
});

});
</script>
<?php
}else{
	header("location: index.php");
}
?>
</body>
</html>