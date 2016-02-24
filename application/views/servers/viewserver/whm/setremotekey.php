<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#save_changes_sbmt").bind("click", function(e) {
                e.preventDefault();
                if ($("#user_name").val().trim() == "") {
                    alert("Please enter a valid user name");
                } else if ($("#password").val().trim() == "") {
                    alert("Please enter a valid password");
                } else if ($("#password").val().length < 5) {
                    alert("Password should atleast be more than 5 characters long.");
                } else if ($("#remote_key").val().length < 250) {
                    alert("Remote key does not seem to be a valid one.");
                } else {
                    $("#remote_key_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_setremotekey/" . $s_id . "/" ?>",
                        data: $("#remote_key_frm").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_setremotekey/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
          <li>
            <a href="<?php echo site_url(); ?>/servers/viewserver/info/<?= $s_id; ?>/"><?=$hostname ?></a>
            <span class="divider">/</span>
          </li>
          <li class="active">Configure WHM remote key</li>
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
              
                <p><b>Configure WHM Remote Key</b></p><hr/>
     <div id="remote_key_div">
     <form id="remote_key_frm">
         <p>WHM Username<br/>
         <input type="text" name="user_name" id="user_name" value="<?= $user_name ?>">
             </p>
             <p>WHM Passsword<br/>
         <input type="password" name="password" id="password" value="<?= $password ?>">
             </p>
             <p>WHM Remote Key<br/>
                 <textarea rows="15" style="width:360px" name="remote_key" id="remote_key"><?= $remote_key ?></textarea></p>
             <hr/>
             <p><button class="btn btn-success" href="#" id="save_changes_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Save</span>
              </button></p>
         </form>
      
         <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
         <p><i class="icon-hand-right"></i> <font color="red">A word about Passwords and Remote Keys</font></p>
         <p>
             <ul>
                 <li>Passwords and Remote Keys that you enter here are saved in encrypted format</li>
                 <li>WHAM! strictly uses Remote Key API authentication for CPanel/WHM servers</li>
                 <li>WHAM! uses this password for one purpose - to enable quick, direct and password-less access to Cpanel / WHM interface from WHAM!</li>
             </ul>
         </p>
     </div>
     
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