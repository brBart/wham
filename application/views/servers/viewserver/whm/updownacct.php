<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            
            $("#modify_acct_select_sbmt").bind("click", function(e) {
                e.preventDefault();
                if ($("#account_to_change").val() == "0") {
                    e.preventDefault();
                    alert("Please select an account from the list");
                    return;
                } 
                
                if ($("#new_pkg").val() == "0") {
                    e.preventDefault();
                    alert("Please select a valid package from the list");
                    return;
                } 
                $("#modify_div").hide();
                $("#status_msg_div").show();
                $("#results_div").show();
                
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_updownacct/" . $s_id . "/" ?>",
                    data: $("#modify_acct_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_updownacct/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
                
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
              
                <p><b>Upgrade/Downgrade Account</b></p><hr/>
     <div id="modify_div">
         <p>Select the account you wish to upgrade/downgrade</p>
         <form id="modify_acct_form">
             <select name="account_to_change" id="account_to_change">
                    <option value="0">-- select --</option>
                <?php if (isset($account_list)) :?>    
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)?" selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select>
             <p>Assign package for the account</p>
             <select name="new_pkg" id="new_pkg">
                    <option value="0">-- select --</option>
                <?php if (isset($pkglist)) :?>    
                <?php foreach ($pkglist as $pkg) :?>
                    <option value="<?= $pkg ?>"><?= $pkg ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select>
             <p>&nbsp;<br/><button class="btn btn-success" href="#" id="modify_acct_select_sbmt">
                <span class="btn-label"><i class="icon-edit icon-white"></i> Upgrade/downgrade account</span>
              </button></p>
         </form>
     
         <p>&nbsp;</p>
     </div>
     
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none">working.. please be patient.. <img src='<?= base_url()?>includes/images/working.gif'></div>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>