<?php Template::printHeader_(); ?>
<script type="text/javascript">
        
        
        $(document).ready(function() {
            
            $("#sbmt_btn").bind("click", function(e) {
                e.preventDefault();
                
                last_user = $("#account_name :selected").last().val();
                
                $.each($("#account_name").val(), function(e,v) {
                    $("#status_msg_div table").append("<tr><td><i class='icon-hand-right'></i> Modifying quota for user: " + v + "</td></tr>");
                    $("#quota_form [name=user]").val(v);
                    $("#quota_form [name=quota]").val($("#quota").val());
                    
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "<?=site_url() . "/servers/viewserver/whm_modifyquota/" . $s_id . "/" ?>",
                        data: $("#quota_form").serialize(),
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
            
            $("#quota_sbmt").bind("click", function(e) {
                e.preventDefault();
                var validUN, validQuota;
                
                if ($("#account_name :selected").length == "0") {
                    alert("Please select a valid account from the list.");
                    return;
                } else {
                    validUN = true;
                }
                
                if ($("#quota").val() != "unlimited" && isNaN($("#quota").val())) {
                    alert("Please enter a valid number for quota.");
                    return;
                } else {
                    validQuota = true;
                }
                
                cnf = confirm("You are about modify quota for (" + $("#account_name :selected").length + ") accounts.\nDo you wish to proceed? \n");
                
                if (cnf == false)
                    return;
                
                
                
                if (validUN == true && validQuota == true) {
                    $("#modify_div").hide();
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
              
                <p><b>Quota Modification (Multiple Accounts)</b></p><hr/>
    <?php if (isset($account_list) && count($account_list) > 0) :?>
     <div id="modify_div">
     
         <p>Select the account(s) to modify its quota</p>
         <form>
             <select name="account_name" id="account_name" multiple="multiple" size="15" class="input-xxlarge">
                
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"><?=$detail ?></option>
                <?php endforeach; ?>
                
             </select>
             <p>&nbsp;<br/>New Quota<br/>
             <input type="text" name="quota" id="quota"> MB</p>
             <p><button class="btn btn-success" href="#" id="quota_sbmt">
                <span class="btn-label"><i class="icon-ok icon-white"></i> Modify Quota</span>
              </button></p>
         </form>
         <p>&nbsp;</p>
     </div>
     
     <div id="status_msg_div" style="display:none">
             <table class="table table-striped"></table>
         </div>
    <?php else :?>
                No accounts present.
    <?php endif; ?>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
      <form id="quota_form">
             <input type="hidden" name="user" value="">
             <input type="hidden" name="quota" value="">
         </form>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Modifying Account Quota..</h3>
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