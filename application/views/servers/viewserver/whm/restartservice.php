<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#restart_serv_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#service").val() == "0") {
                    alert("Please select a valid service from the list.");
                } else {
                    $("#restart_serv_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_restartservice/" . $s_id . "/" ?>",
                        data: $("#restart_service_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_restartservice/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
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
              
                <p><b>Restart Service</b></p><hr/>
     <div id="restart_serv_div">
                <p>This option enables you to restart services<br/>&nbsp;</p>
                <form id="restart_service_form">
                    <p>Service Name<br/>
                    <select id="service" name="service">
                        <option value="0">-- select --</option>
                        <option value="named">DNS Server</option>
                        <option value="interchange">Interchange Server</option>
                        <option value="ftpd">FTP Server</option>
                        <option value="httpd">Apache Web Server</option>
                        <option value="imap">IMAP Server</option>
                        <option value="pop">POP3 Server</option>
                        <option value="exim">Exim Mail</option>
                        <option value="mysql">MySQL Server</option>
                        <option value="postgresql">PostgreSQL Server</option>
                        <option value="sshd">SSH Daemon</option>
                        <option value="tomcat">Tomcat Server</option>
                    </select>
                    </p>
                    <p>&nbsp;<br/><button id="restart_serv_sbmt" class="btn btn-success" href="#">
                <span class="btn-label"><i class="icon-refresh icon-white"></i> Restart Service</span>
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