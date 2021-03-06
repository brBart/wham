<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#suspend_acct_select_sbmt").bind("click", function(e) {
                e.preventDefault();
                if ($("#account_to_suspend").val() == "0") {
                    alert("Please select an account from the list");
                } else {
                    $("#suspend_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_suspendacct/" . $s_id . "/" ?>",
                        data: $("#account_to_suspend_frm").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_suspendacct/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>Suspend An Account</b> / <a href="<?= site_url() ?>/servers/viewserver/whm_suspendacct_multi/<?= $s_id?>/">Suspend Multiple Accounts</a></p><hr/>
     <div id="suspend_div">
     <?php if (isset($account_list) && count($account_list) > 0) :?>
         <p>Select the account you wish to suspend
         <form id="account_to_suspend_frm">
             <select name="account_to_suspend" id="account_to_suspend">
                    <option value="0">-- select --</option>
                <?php if (isset($account_list)) :?>
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)? " selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select></p>
             <p>Reason for suspension<br/><input type="text" name="reason" value="" placeholder="Why are you suspending?"></p>
             <hr/>
             <p><button class="btn btn-success" href="#" id="suspend_acct_select_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Suspend account</span>
              </button></p>
         </form>
      <?php else :?>
         <p>Unable to find any accounts that could be suspended.</p>
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