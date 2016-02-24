<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#restart_serv_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#confirm_string").val() != "I am about to reboot this server") {
                    alert("Please enter the correct confirmation string");
                } else {
                    
                    var res = confirm("Are you sure you wish to reboot this server?");
                    if (res) {
                        $("#restart_serv_div").hide();
                        $("#status_msg_div").hide();
                        $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                        $("#results_div").show();

                        $.ajax({
                            type: "POST",
                            url: "<?=site_url() . "/servers/viewserver/whm_reboot/" . $s_id . "/" ?>",
                            data: $("#restart_service_form").serialize(),
                            dataType: "text",
                            success: function(text) {
                                $("#status_msg_div").addClass("well").html(text).show();
                                $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_reboot/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                            }
                        });
                    }
                }
            });
        });
    </script>
  </head>  
  <body>
      <?php Template::printTopMenu_('servers'); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/servers/">Servers</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/servers/listservers/">List all servers</a>
            <span class="divider">/</span>
          </li>
          <li class="active"><?=$hostname ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <ul class="nav nav-tabs">
                    <li>
                        <a href="<?= site_url() . "/servers/viewserver/info/" . $s_id . "/" ?>">Server details</a> 
                    </li>
                    <li class="active">
                        <a href="<?= site_url() . "/servers/viewserver/whm/" . $s_id . "/" ?>"><b>Web Host Manager</b></a>
                    </li>
                </ul>
              
                <p><b>Reboot Server</b></p><hr/>
     <div id="restart_serv_div">
         <div class="alert">
            <a class="close" data-dismiss="alert">×</a>
            <span>    
                <h4><font color="red">WARNING!</font></h4>
                <u>YOU ARE ABOUT TO REBOOT THE SERVER</u><br/>&nbsp;<br/>Please use this option with proper care. It is not possible to undo your actions.</span>
            </div>
                
                <form id="restart_service_form">
                    <p>Force Reboot?<br/>&nbsp;<br/>
                    <input type="radio" name="force" value="0" checked> Graceful reboot<br/>
                    <input type="radio" name="force" value="1"> Forceful reboot <br/><u>NOTE: A forceful 
                        reboot may result in data loss if processes are still running when the server 
                        restarts.</u><br/>&nbsp;<br/>
                    
                    </p>
                    <p>Please type in the exact sentence given below into the textbox. This is 
                to confirm that you are well aware of what you are about to do.<br/>&nbsp;<br/>
                    <font color="green"><b>I am about to reboot this server</b></font><br/>
                    <input type="text" name="confirm_string" id="confirm_string">
                    </p>
                    <p>&nbsp;<br/><button id="restart_serv_sbmt" class="btn btn-success" href="#">
                <span class="btn-label"><i class="icon-retweet icon-white"></i> Reboot Server</span>
              </button></p>
                </form></div>
     <p>&nbsp;</p>
     <div id="results_div" style="display:block;"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>