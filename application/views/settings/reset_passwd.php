<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#admin_pwd_sbmt").bind("click", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/reset_passwd/" ?>",
                    data: $("#admin_pwd_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#admin_pwd_msg").html(text);
                        if (text == "Admin password has been changed successfully.") {
                            $("#old_pwd").val("");
                            $("#new_pwd").val("");
                            $("#new_pwd_cnf").val("");
                        }
                    }
                });
            });
        });
    </script>
  </head>
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_("reset_pwd"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/settings/">Settings</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Reset password</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <table border="0" width="100%">
                   <tr>
                       <td><b>Change Admin Password</b><hr/></td>
                   </tr>
                   <tr>
                       <td style="font-style: italic"><form id="admin_pwd_frm">
                           Current Password<br/>
                           <input type="password" placeholder="Current password" name="old_pwd" id="old_pwd"><br/>
                           New Password<br/>
                           <input type="password" placeholder="New password" name="new_pwd" id="new_pwd"><br/>
                           Confirm New Password<br/>
                           <input type="password" placeholder="Confirm new password" name="new_pwd_cnf" id="new_pwd_cnf">
                           <br/>&nbsp;<br/>
                            <button class="btn btn-success" href="#" id="admin_pwd_sbmt">
                            <span class="btn-label"><i class="icon-edit icon-white"></i> Change Password</span>
                            </button><br/>&nbsp;<br/><span id="admin_pwd_msg" style="font-style: normal; color: red"></span>&nbsp;<br/>
                       </form>
                       </td>
                   </tr>
               </table>
                  <p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>