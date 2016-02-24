<?php Template::printHeader_(); ?>
<?php if (isset($acc_list)) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            
            
            $("#sbmtBtn").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#user").val() == "0") {
                    alert("Please select a valid user from the list");
                    return;
                }
                
                sel = $("#user").val()
                
                cnf = confirm("Do you wish to add reseller privileges to user: " + sel);
                
                if (cnf == false)
                    return;
                
                $("#reseller_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_setupreseller/" . $s_id . "/" ?>",
                    data: $("#add_priv_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_setupreseller/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>Add Reseller Privileges</b></p><hr/>
     <div id="reseller_div">
     <?php if (isset($acc_list)) :?>    
     <p><i class="icon-hand-right"></i> Select the account you wish to grant reseller privileges:</p>
     <form id="add_priv_frm">
         <p>&nbsp;<br/>User name<br>
         <select name="user" id="user">
             <option value="0">-- select --</option>
             <?php foreach ($acc_list as $acc) :?>
             <option value="<?= $acc ?>"><?= $acc ?></option>
             <?php endforeach; ?>
         </select>
         </p>
         <p>Make Owner?<br/>
         <input type="radio" name="makeowner" value="1"> &nbsp;Yes<br/>
         <input type="radio" name="makeowner" value="0" checked> &nbsp;No<br/>
         </p>
         <p><button id="sbmtBtn" type="submit" class="btn btn-success"><i class="icon-plus-sign icon-white"></i> Add Reseller Privileges</button></p><p>&nbsp;</p>
         <p class="alert"><span class="label label-important">NOTE</span> Users who own their accounts are able to modify them. This will allow them to 
             circumvent account creation limits you may have set up.</p>
     </form>
         
         
         
         <p>&nbsp;</p>
         
     </div>
        <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <?php elseif (isset($error)) :?>
         Network/API error
     <?php endif; ?>
         
     
     
     
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>