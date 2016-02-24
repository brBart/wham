<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#create_sbmt").bind("click", function(e) {
                e.preventDefault();
                $("#new_account_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();
                
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_createacct/" . $s_id . "/" ?>",
                    data: $("#create_acct_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_createacct/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
                
            });
        });
    </script>
  </head>
<?php if (isset($package_list)) $no_of_packages = count($package_list); ?>  
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
              
                <p><b>Create A New Account</b></p><hr/>
     <div id="new_account_div"><form id="create_acct_form">           
     <table class="table table-striped">
        <tr>
            <td valign="top">Cpanel user name<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" maxlength="8" class="input-medium" id="user_name" name="user_name" value="" placeholder="Cpanel user name"></td>
        </tr>
        <tr>
            <td valign="top">Domain name<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" class="input-medium" id="domain_name" name="domain_name" value="" placeholder="maindomain.tld"></td>
        </tr>
        <tr>
            <td valign="top">Choose a package<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top">
                <select name="plan_name" id="plan_name">
                    <option value="0">-- select --</option>
                <?php if (isset($no_of_packages) && isset($package_list)) :?>    
                <?php for ($j = 0; $j < $no_of_packages; $j++) :?>
                    <option value="<?=$package_list[$j] ?>"><?=$package_list[$j] ?></option>
                <?php endfor; ?>
                <?php endif; ?>    
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top">Contact email<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" class="input-medium" id="contactemail" name="contactemail" value="" placeholder="email@domain.tld"></td>
        </tr>
        <tr>
            <td colspan="2"><p><sup><font size="3" color="red">*</font></sup> indicates a necessary field</p>
                  <p>&nbsp;</p><button class="btn btn-success" href="#" id="create_sbmt">
                <span class="btn-label"><i class="icon-file icon-white"></i> Create new account</span>
              </button><p>&nbsp;</p></td>
        </tr>
     </table></form>
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