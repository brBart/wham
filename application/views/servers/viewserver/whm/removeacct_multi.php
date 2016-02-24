<?php Template::printHeader_(); ?>
<?php if(isset($account_list)) :?> 
    <script type="text/javascript">
        
        
        $(document).ready(function() {
            
            $("#sbmt_btn").bind("click", function(e) {
                e.preventDefault();
                
                last_user = $("#account_name :selected").last().val();
                
                if ( $("#keepdns").attr("checked") == "checked" ) 
                    $("#terminate_acct_form [name=keepdns]").val("1");
                else 
                    $("#terminate_acct_form [name=keepdns]").val("");
                
                $.each($("#account_name").val(), function(e,v) {
                    $("#status_msg_div table").append("<tr><td><i class='icon-hand-right'></i> Removing user: " + v + "</td></tr>");
                    $("#terminate_acct_form [name=account_name]").val(v);
                    
                    
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?=site_url() . "/servers/viewserver/whm_removeacct/" . $s_id . "/" ?>",
                        data: $("#terminate_acct_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div table").append("<tr><td>" + text + "</td></tr>");
                            if (v == last_user) {
                                $("#close_btn").show();
                                $("#cur_acc").html("Task completed");
                                $('#myModal').modal('hide');
                            }
                        },
                        error: function() {
                            $("#status_msg_div table").append("<tr><td><font color='red'>*** Some error occured ***</font></td></tr>");
                            $("#close_btn").show();
                            $("#cur_acc").html("*** Some error occured ***");
                            $('#myModal').modal('hide');
                            return;
                        }
                    });

                });
            });
            
            $("#terminate_sbmt").bind("click", function(e) {
                e.preventDefault();
                var validUN, validMsg;
                
                if ($("#account_name :selected").length == "0") {
                    alert("Please select a valid account from the list.");
                    return;
                } else {
                    validUN = true;
                }
                
                if ($("#confirm_string").val() != "I wish to delete this account from my server. It is no longer required") {
                    alert("Please enter the proper confirmation string.");
                    return;
                } else {
                    validMsg = true;
                }
                
                cnf = confirm("You are about delete (" + $("#account_name :selected").length + ") accounts.\nDo you wish to proceed? \n\nTHIS ACTION CANNOT BE REVERSED.");
                
                if (cnf == false)
                    return;
                
                
                
                if (validUN == true && validMsg == true) {
                    $("#terminate_div").hide();
                    $("#status_msg_div").show();
                    
                    $("#myModal").removeData("modal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    

                    setTimeout('$("#sbmt_btn").click()', 3000);
                    
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
              
                <p><b>Terminate Multiple Accounts</b></p><hr/>
     <div id="terminate_div">
     <?php if(isset($account_list)) :?>    
         <form>
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
            <td valign="top">Which accounts do you wish to remove?<br/>&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" style="padding-bottom: 30px;">
                <select name="account_name" id="account_name" multiple="multiple" size="15" class="input-xxlarge">
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)?" selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                </select>
                
                <p><input type="checkbox" id="keepdns" value="1"> Keep DNS zone<br></p>
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
         
         <form id="terminate_acct_form">
             <input type="hidden" name="account_name" value="">
             <input type="hidden" name="keepdns" value="">
             <input type="hidden" name="confirm_string" value="I wish to delete this account from my server. It is no longer required">
         </form>
     <?php else :?>
         No accounts available to remove
     <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     
         <div id="status_msg_div" style="display:none">
             <table class="table table-striped"></table>
         </div>
     
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
      <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Removing Accounts..</h3>
  </div>
  <div class="modal-body">
    <p>Please do not close this browser window.</p>
    <p id="cur_acc" style="color:red"><b>It might take a few minutes for the process to complete. Kindly be patient</b></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" style="display:none" id="close_btn">Close</button>
    <button class="btn btn-primary" style="display:none" id="sbmt_btn">Start</button>
  </div>
</div>
    <?php Template::printFooter_(); ?>