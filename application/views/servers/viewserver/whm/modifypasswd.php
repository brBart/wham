<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if (isset($account_selected)) :?>
            $("#modify_sbmt").bind("click", function(e) {
                e.preventDefault();
                $("#modify_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_modifypasswd/" . $s_id . "/" ?>",
                    data: $("#modify_acct_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_modifypasswd/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });    
            });
            <?php else :?>
            $("#modify_acct_select_sbmt").bind("click", function(e) {
                if ($("#account_to_change").val() == "0") {
                    e.preventDefault();
                    alert("Please select an account from the list");
                }
            });
            <?php endif; ?>
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
              
                <p><b>Modify Password</b></p><hr/>
     <div id="modify_div">
     <?php if (isset($account_selected)) :?>
         <form id="modify_acct_form">           
             <input type="hidden" name="user" value="<?= $account_selected->user ?>">
             Enter new password for user <b><font color="green">"<?= $account_selected->user ?>"</font></b> :<br/>&nbsp;<br/>
             <input type="text" class="input-medium" id="pass" name="pass">
             <hr/></p>
                  <button class="btn btn-success" href="#" id="modify_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Change password</span>
              </button>
         </form>
     <?php else :?>
         <p>Select the account for which you wish to change password</p>
         <form action="<?= site_url()?>/servers/viewserver/whm_modifypasswd/<?= $s_id?>/" method="post">
             <select name="account_to_change" id="account_to_change">
                    <option value="0">-- select --</option>
                <?php if (isset($account_list)) :?>
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)? " selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select><hr/>
             <p><button class="btn btn-success" href="#" id="modify_acct_select_sbmt">
                <span class="btn-label">Next <i class="icon-hand-right icon-white"></i></span>
              </button></p>
         </form>
     <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>