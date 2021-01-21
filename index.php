
<?php
//Get Functions
//define('__ROOT__', dirname(dirname(__FILE__)));
//require_once(__ROOT__.'/wagonsroll/src/settings.php'); 
require 'src/wagonsroll/wagonsroll_autoloader.php';
?>
<!doctype html>
<html lang="en">
 <head>
  <title>Wagons Roll</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/wagonsroll.css" rel="stylesheet" />

  
 </head>
 <body>

<?php

  // Parse with sections
  $ini_array = parse_ini_file("wagonsroll.ini",true);

  //Queue to use
  $queueID = $ini_array['queue_info']['email'];

 // $configTemplate = file_get_contents('assets/configTemplate.html', true);

?>


 
   <form name="add_post" method="post">
    <center>
      <table style="width:95%;">
      <tr>
       <td style="width:13%;">
 
      <div class="container box">
          <div class="form-group" align="left">
           <input type="button" valign="top" name="post_button" id="post_button"  value="Update" class="btn btn-info" />
             </div>
        
         
            <input type="checkbox" id="queueOpen" />
            <label for="queueOpen">Open Process</label>
          <br>
            <input type="checkbox" id="archive" />
            <label for="archive">Archive Session</label>
          </div>
      
       
      
        <div class="container box">
            <div id="queueID">Queue <br><i>
                <?php echo $queueID; ?>
                </i>
            </div>

          <div id="postCountDown"></div>
        </div>
       </td>
       
       <td style="width:40%;">
         <div id="systembox">
            <div id="systemStatus">
              
            </div>
         </div>
      </td>

       <td style="width:40%;">
        <div id="banner">
          <img src="assets/banner.png" alt="Wagons Roll By FinTechllc.com">
        </div>
      </td>
     </tr>
   </table>
 </center>


<!--    <div class="form-group">
     <textarea name="post_name" id="post_name" class="form-control" rows="3"></textarea>
    </div>
-->
   </form>

   <ul class="nav nav-tabs my-tab">
    <li class="active"><a data-toggle="tab" href="#home">Queue</a></li>
    <li><a data-toggle="tab" href="#settings">Settings</a></li>
    <li><a data-toggle="tab" href="#setup">Setup & Validate</a></li>
  </ul>
   

  <div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <h3>Queue</h3>
     <div id="load_posts"></div>
  </div>
  <div id="settings" class="tab-pane fade">

    
    <div class="container settingwrapper">
      <h4><div class="col-md-10">Settings</div>

      </h4>
    </div>

    <div>
      <div id="queueStatus"></div>
    </div>

    <div>
      Wagons Roll To External System Mapping
    </div>
        <?php
        //echo $configTemplate;
         echo '<pre>' .  print_r($ini_array,true) . '</pre>';
        ?>

    </div>
    <div id="setup" class="tab-pane fade">
      <h3>System Setup</h3>
      <p>Run System Updates for Changes and Database Changes</p>
      <input type="button" valign="top" name="system_check" id="system_check"  value="System Check" class="btn btn-info" />
      <input type="button" valign="top" name="system_update" id="system_update"  value="System Update" class="btn btn-info" />
      <div id="systemReport"></div>
    </div>

  </div> <!--Tab Content end -->
  
 
  </div>
 </body>
</html>



<script>
$(document).ready(function(){
var timeWait = 30
var timeLeft = timeWait;
//var timerId = setInterval(countdown, 1000);
var elem = document.getElementById('postCountDown');
var queueData = document.getElementById('load_posts');
var queueOpen = $("#queueOpen").is(":checked");
elem.innerHTML = 'Queue Closed';




//elem.innerHTML = 'Updating in <br><i>' + timeLeft + '</i> seconds';

function countdown() {
  var queueOpen = $("#queueOpen").is(":checked");
        if (timeLeft == -1) { //times up
        
            if (queueOpen) {
              $('#load_posts').load("queue.php");
              elem.innerHTML = 'Queue Updating';
            }else{ //Not Open
              $('#load_posts').unload("queue.php");
               elem.innerHTML = 'Queue Closed<br><i>' + timeLeft + '</i> seconds';
            }
            timeLeft = timeWait;
        } else { //Times not up
           //$('#load_posts').unload("queue.php");
           if (queueOpen) {
            elem.innerHTML = 'Queue Recheck in <br><i>' + timeLeft + '</i> seconds';
           }else{
              elem.innerHTML = 'Queue Closed';
              queueData.innerHTML = '';
              //$('#load_posts').unload("queue.php");
              clearInterval(timerId);
           }
          
            timeLeft--;
        }
}




   $('#post_button').click(function(){
      var post_name = $('#post_name').val();
      var queueOpen = $("#queueOpen").is(":checked");
      
      if (queueOpen) {
        $('#load_posts').load("queue.php").fadeIn("slow");
        clearInterval(timerId);
        var timerId = setInterval(countdown, 1000);
      }else{
       $('#load_posts').unload("queue.php");
        clearInterval(timerId);
        
      }



        //trim() is used to remover spaces
        if($.trim(post_name) != '')
        {
         $.ajax({
          url:"post.php",
          method:"POST",
          data:{post_name:post_name},
          dataType:"text",
          success:function(data)
          {
           $('#post_name').val("");
          }
         });
        }


   }); //sUBMIT



//Check System Status
$('#systemStatus').load("src/wagonsroll/setup.php");

//Check System Status
$('#queueStatus').load("src/wagonsroll/settings.php");


//Validate the system table files are correct version and format
$('#system_check').click(function(){
  
      $.ajax({
          url:"src/wagonsroll/setup.php",
          method:"POST",
          data:{
            function: "verify"
          },
          dataType:"text",
          success:function(data)
          {
           
           $('#systemReport').append(data);
          }
         });
      
  });

//Create and Update the system
$('#system_update').click(function(){
  
      $.ajax({
          url:"src/wagonsroll/setup.php",
          method:"POST",
          data:{
            function: "update"
          },
          dataType:"text",
          success:function(data)
          {
           
           $('#systemReport').append(data);
          }
         });
      
  });



}); //Document Ready
 




</script>