<?php Template::printHeader_(); ?>
<?php if(isset($package_list) &&  count($package_list) > 0) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#terminate_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#pkg").val() == "0") {
                    alert("Please select a valid package from the list.");
                } else if ($("#confirm_string").val() != "I wish to delete this package from my server") {
                    alert("Please enter the exact confirmation string in order to proceed further.");
                } else {
                    $("#terminate_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_killpkg/" . $s_id . "/" ?>",
                        data: $("#terminate_pkg_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_killpkg/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
                }
                
            });
        });
    </script>
<?php endif; ?>
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
              
                <p><b>Delete A Package</b></p><hr/>
     <div id="terminate_div">
     <?php if(isset($package_list) && count($package_list) > 0) :?>
     <form id="terminate_pkg_form">
         <div class="alert">
            <a class="close" data-dismiss="alert">×</a>
        <span>    
            <h4><font color="red">WARNING!</font></h4>
            Please use this option with proper care. It is not possible to undo your actions.</span>
        </div>
     <table class="table table-striped">
        <tr>
            <td valign="top">Which package do you wish to remove?
            <p><br/>
                <select name="pkg" id="pkg">
                    <option value="0">-- select --</option>
                <?php foreach ($package_list as $pkg) :?>
                    <option value="<?= $pkg->name ?>"<?= (isset($sel_pkg) && $sel_pkg == $pkg->name)?" selected":"" ?>><?=$pkg->name ?></option>
                <?php endforeach; ?>
                </select>
            </p></td>
        </tr>
        <tr>
            <td valign="top">
                Please type in the exact sentence given below into the textbox. This is 
                to confirm that you are well aware of what you are about to do.<p>&nbsp;<br/>
                    <font color="green"><b>I wish to delete this package from my server</b></font>
                    <p><input type="text" name="confirm_string" id="confirm_string"></p>
                    <button class="btn btn-success" href="#" id="terminate_sbmt">
                <span class="btn-label"><i class="icon-minus-sign icon-white"></i> Delete package</span>
              </button><p>&nbsp;</p>
            </td>
        </tr>
        <tr>
     </table></form>
     <?php else :?>
         No packages available to remove.
     <?php endif; ?>
         <p>&nbsp;</p>
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