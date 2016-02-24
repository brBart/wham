<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#modify_acct_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#user").val() == "0") {
                    alert("Please select a valid account from the list");
                    return;
                }
                
                if ($("#owner").val() == "0") {
                    alert("Please select a valid reseller from the list");
                    return;
                }
                
                $("#modify_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_modifyacctowner/" . $s_id . "/" ?>",
                    data: $("#modify_acct_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_modifyacctowner/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>Change Account Ownership</b> / <a href="<?= site_url() ?>/servers/viewserver/whm_modifyacctowner_multi/<?= $s_id?>/">Modify Multiple Accounts</a></p><hr/>
     
     <?php if (isset($error)) :?>
         <?= $error ?>
     <?php elseif(isset($reseller_list) && $reseller_list > 0 && isset($user_list) && $user_list > 0) :?>
      <div id="modify_div">
         <p><i class="icon-hand-right"></i> You can change the ownership of an account using this interface<br/>&nbsp;</p>
         <form id="modify_acct_form">
             <p>Account Name<br/>
             <select name="user" id="user">
                    <option value="0">-- select --</option>   
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
     
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none">working.. please be patient.. <img src='<?= base_url()?>includes/images/working.gif'></div>
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