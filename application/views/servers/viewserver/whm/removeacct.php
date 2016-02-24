<?php Template::printHeader_(); ?>
<?php if(isset($account_list)) :?> 
    <script type="text/javascript">
        $(document).ready(function() {
            $("#terminate_sbmt").bind("click", function(e) {
                e.preventDefault();
                var validUN, validMsg;
                
                if ($("#account_name").val() == "0") {
                    alert("Please select a valid account from the list.");
                } else {
                    validUN = true;
                }
                
                if ($("#confirm_string").val() != "I wish to delete this account from my server. It is no longer required") {
                    alert("Please enter the proper confirmation string.");
                } else {
                    validMsg = true;
                }
                
                if (validUN == true && validMsg == true) {
                    $("#terminate_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_removeacct/" . $s_id . "/" ?>",
                        data: $("#terminate_acct_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_removeacct/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>Terminate Account</b> / <a href="<?= site_url() ?>/servers/viewserver/whm_removeacct_multi/<?= $s_id?>/">Terminate Multiple Accounts</a></p><hr/>
     <div id="terminate_div">
     <?php if(isset($account_list)) :?>    
         <form id="terminate_acct_form">
     <table border="0" width="100%">
        <tr>
            <td>
            <div class="alert">
            <a class="close" data-dismiss="alert">×</a>
            <span>
                <h4><font color="red">WARNING!</font></h4>
                Please use this option with proper care. It is not possible to undo your actions.</span>
            </div>
            </td>
        </tr>
        <tr>
            <td valign="top">Which account do you wish to remove?</td>
        </tr>
        <tr>
            <td valign="top" style="padding-bottom: 30px;">
                <select name="account_name" id="account_name">
                    <option value="0">-- select --</option>
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)?" selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                </select>
                
                <p>
                    <input type="checkbox" name="keepdns" value="1"> Keep DNS zone<br>
                </p>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding-bottom: 10px;">
                Please type in the exact sentence given below into the textbox. This is 
                to confirm that you are well aware of what you are about to do.<p>&nbsp;<br/>
                    <font color="green"><b>I wish to delete this account from my server. It is no longer 
                    required</b></font>
            </td>
        </tr>
        <tr>
            <td valign="top" style="padding-bottom: 20px;">
                <input type="text" name="confirm_string" id="confirm_string">
            </td>
        </tr>
        <tr>
            <td colspan="2"><p><hr/></p>
                  <button class="btn btn-success" href="#" id="terminate_sbmt">
                <span class="btn-label"><i class="icon-trash icon-white"></i> Terminate account</span>
              </button></td>
        </tr>
     </table></form>
     <?php else :?>
         No accounts available to remove
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