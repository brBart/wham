<?php Template::printHeader_(); ?>
<script type="text/javascript">
    $(document).ready(function() {

        $("#sbmt_btn").bind("click", function(e) {
            e.preventDefault();

            last_user = $("#account_to_suspend :selected").last().val();

            $.each($("#account_to_suspend").val(), function(e,v) {
                $("#status_msg_div table").append("<tr><td><i class='icon-hand-right'></i> Suspending user: " + v + "</td></tr>");
                $("#suspend_form [name=account_to_suspend]").val(v);
                $("#suspend_form [name=reason]").val($("#reason").val());

                $.ajax({
                    type: "POST",
                    async: false,
                    url: "<?=site_url() . "/servers/viewserver/whm_suspendacct/" . $s_id . "/" ?>",
                    data: $("#suspend_form").serialize(),
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

        $("#suspend_acct_select_sbmt").bind("click", function(e) {
            e.preventDefault();
            
            if ($("#account_to_suspend :selected").length == "0") {
                alert("Please select a valid account from the list.");
                return;
            }

            if ($("#reason").val().trim().length == 0) {
                alert("Please enter a valid reason for suspension.");
                return;
            }

            cnf = confirm("You are about suspend (" + $("#account_to_suspend :selected").length + ") accounts.\nDo you wish to proceed? \n");

            if (cnf == false)
                return;


            $("#suspend_div").hide();
            $("#status_msg_div").show();

            $("#myModal").removeData("modal").modal({
                backdrop: 'static',
                keyboard: false
            });


            setTimeout('$("#sbmt_btn").click()', 3000);

            

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
              
                <p><b>Suspend Multiple Accounts</b></p><hr/>
     <div id="suspend_div">
     <?php if (isset($account_list) && count($account_list) > 0) :?>
         <p>Select the account you wish to suspend
         <form id="account_to_suspend_frm">
             <select name="account_to_suspend" id="account_to_suspend" multiple="multiple" size="15" class="input-xxlarge">
                <?php if (isset($account_list)) :?>
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"><?=$detail ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select></p>
             <p>Reason for suspension<br/><input type="text" name="reason" id="reason" value="" placeholder="Why are you suspending?"></p>
             <hr/>
             <p><button class="btn btn-success" href="#" id="suspend_acct_select_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Suspend selected accounts</span>
              </button></p>
         </form>
      <?php else :?>
         <p>Unable to find any accounts that could be suspended.</p>
      <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     <div id="status_msg_div" style="display:none">
             <table class="table table-striped"></table>
         </div>
     
     <form id="suspend_form">
             <input type="hidden" name="account_to_suspend" value="">
             <input type="hidden" name="reason" value="">
         </form>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Suspending User..</h3>
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
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>