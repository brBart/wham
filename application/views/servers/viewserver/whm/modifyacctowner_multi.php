<?php Template::printHeader_(); ?>
<script type="text/javascript">
    $(document).ready(function() {

        $("#sbmt_btn").bind("click", function(e) {
            e.preventDefault();

            last_user = $("#user :selected").last().val();

            $.each($("#user").val(), function(e,v) {
                $("#status_msg_div table").append("<tr><td><i class='icon-hand-right'></i> Modifying owner for user: " + v + "</td></tr>");
                $("#reseller_form [name=user]").val(v);
                $("#reseller_form [name=owner]").val($("#owner").val());

                $.ajax({
                    type: "POST",
                    async: false,
                    url: "<?=site_url() . "/servers/viewserver/whm_modifyacctowner/" . $s_id . "/" ?>",
                    data: $("#reseller_form").serialize(),
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

        $("#modify_acct_sbmt").bind("click", function(e) {
            e.preventDefault();
            
            if ($("#user :selected").length == "0") {
                alert("Please select a valid account from the list.");
                return;
            }

            if ($("#owner").val() == "0") {
                alert("Please select a valid owner from the list.");
                return;
            }

            cnf = confirm("You are about modify the owner for (" + $("#user :selected").length + ") accounts.\nDo you wish to proceed? \n");

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
              
                <p><b>Change Account Ownership (Multiple Accounts)</b></p><hr/>
     
     <?php if (isset($error)) :?>
         <?= $error ?>
     <?php elseif(isset($reseller_list) && $reseller_list > 0 && isset($user_list) && $user_list > 0) :?>
      <div id="modify_div">
         <p><i class="icon-hand-right"></i> You can change the ownership of an account using this interface<br/>&nbsp;</p>
         <form id="modify_acct_form">
             <p>Account Name<br/>
             <select name="user" id="user" multiple="multiple" size="15" class="input-xxlarge">   
                <?php foreach ($user_list as $user) :?>
                    <option value="<?= $user ?>"><?= $user ?></option>
                <?php endforeach; ?>
             </select></p>
             <p>New Owner<br/>
             <select name="owner" id="owner">
                    <option value="0">-- select --</option>
                    <option value="root">root</option> 
                <?php foreach ($reseller_list as $reseller) :?>
                    <option value="<?= $reseller ?>"><?= $reseller ?></option>
                <?php endforeach; ?>
             </select>
             </p>
             <p>&nbsp;<br/><button class="btn btn-success" href="#" id="modify_acct_sbmt">
                <span class="btn-label"><i class="icon-edit icon-white"></i> Change ownership</span>
              </button></p>
         </form>
         <p>&nbsp;</p>
     </div>
     
     <div id="status_msg_div" style="display:none">
             <table class="table table-striped"></table>
         </div>
     
     <form id="reseller_form">
             <input type="hidden" name="user" value="">
             <input type="hidden" name="owner" value="">
         </form>
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Changing Account Owner..</h3>
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
     <?php else :?>
                No accounts or resellers available right now.
     <?php endif; ?>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>