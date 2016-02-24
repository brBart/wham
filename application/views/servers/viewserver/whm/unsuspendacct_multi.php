<?php Template::printHeader_(); ?>
<?php 
if (isset($account_list_table)) 
    $no_of_accts = count($account_list_table); 

?>  
<?php if (isset($account_list_table) && $no_of_accts > 0) :?>
    <style type="text/css" title="currentStyle">
            @import "<?= base_url() ?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url() ?>includes/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript">
        
        
        $(document).ready(function() {
            
            $("#sbmt_btn").bind("click", function(e) {
                e.preventDefault();
                
                last_user = $("#account_name :selected").last().val();
                
                $.each($("#account_name").val(), function(e,v) {
                    $("#status_msg_div table").append("<tr><td><i class='icon-hand-right'></i> Unsuspending user: " + v + "</td></tr>");
                    $("#hidden_form [name=account_to_unsuspend]").val(v);
                    
                    
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?=site_url() . "/servers/viewserver/whm_listsuspended/" . $s_id . "/" ?>",
                        data: $("#hidden_form").serialize(),
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
            
            $("#unsuspend_acct_select_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#account_name :selected").length == "0") {
                    alert("Please select a valid account from the list.");
                    return;
                }
                
                cnf = confirm("You are about unsuspend (" + $("#account_name :selected").length + ") accounts.\nDo you wish to proceed?");
                
                if (cnf == false)
                    return;
                
                $("#modify_div").hide();
                $("#status_msg_div").show();

                $("#myModal").removeData("modal").modal({
                    backdrop: 'static',
                    keyboard: false
                });


                setTimeout('$("#sbmt_btn").click()', 3000);
                    
                
                
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
              
                <p><b>Unsuspend Multiple Accounts</b></p><hr/>
     <div id="modify_div">
     <?php if (isset($account_list_table) && $no_of_accts > 0) :?>
         <form id="unsuspend_acct_form"><p>Select the accounts you wish to unsuspend:<br/></p>
             <p><select name="account_to_unsuspend" id="account_name" multiple="multiple" size="15" class="input-xxlarge">
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"><?=$detail ?></option>
                <?php endforeach; ?>
             </select></p>
             <p><button class="btn btn-success" href="#" id="unsuspend_acct_select_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Unsuspend accounts</span>
              </button></p>
         </form>
         
         <form id="hidden_form">
             <input type="hidden" name="account_to_unsuspend" value="">
         </form>
     <?php else :?>
         No suspended accounts exist on this server.
         
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
    <h3 id="myModalLabel">Unsuspending Accounts..</h3>
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