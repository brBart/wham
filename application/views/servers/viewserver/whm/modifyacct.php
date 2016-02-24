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
                    url: "<?=site_url() . "/servers/viewserver/whm_modifyacct/" . $s_id . "/" ?>",
                    data: $("#modify_acct_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_modifyacct/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });    
            });
            <?php else :?>
            $("#modify_acct_select_sbmt").bind("click", function(e) {
                if ($("#account_to_change").val() == "0") {
                    e.preventDefault();
                    alert("Please select an account from the list");
                } else {
                    $("#modify_acct_select_sbmt").hide();
                    $("#status_msg_div").show();
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
              
                <p><b>Modify Account</b></p><hr/>
     <div id="modify_div">
     <?php if (isset($account_selected)) :?>
         <form id="modify_acct_form">           
     <table class="table table-striped">
        <tr>
            <td class="col1" valign="top">Cpanel username<input type="hidden" name="user" value="<?= $account_selected["user"] ?>"></td>
            <td class="col2" valign="top"><input type="text" name="newuser" id="newuser" value="<?= $account_selected["user"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Domain</td>
            <td class="col2" valign="top"><input type="text" name="domain" id="domain" value="<?= $account_selected["domain"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max POP accounts</td>
            <td class="col2" valign="top"><input type="text" name="MAXPOP" id="MAXPOP" value="<?= $account_selected["maxpop"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max FTP accounts</td>
            <td class="col2" valign="top"><input type="text" name="MAXFTP" id="MAXFTP" value="<?= $account_selected["maxftp"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max SQL databases</td>
            <td class="col2" valign="top"><input type="text" name="MAXSQL" id="MAXSQL" value="<?= $account_selected["maxsql"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max mailing lists</td>
            <td class="col2" valign="top"><input type="text" name="MAXLST" id="MAXLST" value="<?= $account_selected["maxlst"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max sub-domains</td>
            <td class="col2" valign="top"><input type="text" name="MAXSUB" id="MAXSUB" value="<?= $account_selected["maxsub"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max parked domains</td>
            <td class="col2" valign="top"><input type="text" name="MAXPARK" id="MAXPARK" value="<?= $account_selected["maxparked"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td class="col1" valign="top">Max add-on domains</td>
            <td class="col2" valign="top"><input type="text" name="MAXADDON" id="MAXADDON" value="<?= $account_selected["maxaddons"] ?>" class="input-medium"></td>
        </tr>
        <tr>
            <td colspan="2"><p>&nbsp;</p>
                  <button class="btn btn-success" href="#" id="modify_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Modify account</span>
              </button><p>&nbsp;</p></td>
        </tr>
     </table></form>
     <?php else :?>
         <p>Select the account you wish to modify</p>
         <form action="<?= site_url()?>/servers/viewserver/whm_modifyacct/<?= $s_id?>/" method="post">
             <select name="account_to_change" id="account_to_change">
                    <option value="0">-- select --</option>
                <?php if (isset($account_list)) :?>    
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= (isset($sel_acc) && $sel_acc == $account)?" selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select>
             <p>&nbsp;<br/><button class="btn btn-success" href="#" id="modify_acct_select_sbmt">
                <span class="btn-label"><i class="icon-edit icon-white"></i> Modify account</span>
              </button></p>
         </form>
     <?php endif; ?>
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