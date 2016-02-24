<?php Template::printHeader_(); ?>
<?php if (isset($reseller_list)) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            
            
            $("#sbmtBtn").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#user").val() == "0") {
                    alert("Please select a valid reseller from the list");
                    return;
                }
                
                
                cnf = confirm("Do you wish to unsuspend the reseller: " + $("#user").val());
                
                if (cnf == false)
                    return;
                
                $("#reseller_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_unsuspendreseller/" . $s_id . "/" ?>",
                    data: $("#suspendForm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_unsuspendreseller/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
                
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
              
                <p><b>Unsuspend A Reseller</b></p><hr/>
     <div id="reseller_div">
     <?php if (isset($reseller_list)) :?>    
     <form id="suspendForm">
         <p>&nbsp;<br/>Reseller to Unsuspend:<br/>
         <select name="user" id="user">
             <option value="0">-- select --</option>
             <?php foreach($reseller_list as $reseller) :?>
             <option value="<?= $reseller ?>"><?= $reseller ?></option>
             <?php endforeach; ?>
         </select>
         </p>
         <p>Unuspend all sub accounts for this user? (time consuming if reseller has many accounts)<br/>
         <input type="radio" name="unsuspend_all_sub_accounts" value="yes"> &nbsp; Yes<br/>
         <input type="radio" name="unsuspend_all_sub_accounts" value="no" checked> &nbsp; No</p>
         <p><button id="sbmtBtn" type="success" class="btn btn-success"><i class="icon-refresh icon-white"></i> Unsuspend Reseller</button></p>
     </form>
         <p>&nbsp;</p><p>&nbsp;</p>
         
     <?php elseif (isset($no_resellers)) :?>
         No reseller accounts exist on this server.
     <?php elseif (isset($error)) :?>
         Network/API error
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