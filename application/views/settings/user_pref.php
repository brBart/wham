<?php Template::printHeader_(); ?>
<style type="text/css">
    .thead {
        padding-left: 10px;
        border-bottom: gray thin inset;
        font-weight: bold;
        color: green;
    }
    
    .osmall {
        margin-top: 5px;
        width: 100px;
    }
    td {
        padding-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        $("#pwd_sbmt").bind("click", function(e) {
            e.preventDefault();
            var th = $(this);
            if (th.hasClass("disabled") != "false") {
                th.addClass("disabled");
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/reset_passwd/" ?>",
                    data: $("#pwd_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#pwd_msg").html(text);
                        th.removeClass("disabled");
                    }
                });
            }
        });
        
        $("#settings_sbmt_btn").bind("click", function(e) {
            e.preventDefault();
            var th = $(this);
            if (th.hasClass("disabled") != "false") {
                th.addClass("disabled");
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/user_pref/save/" ?>",
                    data: $("#locale_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#result").html(text);
                        th.removeClass("disabled");
                    }
                });
            }
        });
    });
</script>
  </head>
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_("user_pref"); ?>
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
          <li class="active">User Preferences</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <table border="0" width="100%">
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Modify Password</td>
                   </tr>
                   <tr>
                       <td style="font-style: italic" colspan="2"><form id="pwd_frm"><br/>
                           Current Password<br/>
                           <input type="password" placeholder="Current password" name="old_pwd" id="old_pwd"><br/>
                           New Password<br/>
                           <input type="password" placeholder="New password" name="new_pwd" id="new_pwd"><br/>
                           Confirm New Password<br/>
                           <input type="password" placeholder="Confirm new password" name="new_pwd_cnf" id="new_pwd_cnf">
                           <br/>&nbsp;<br/>
                            <button class="btn btn-success" href="#" id="pwd_sbmt">
                            <span class="btn-label"><i class="icon-edit icon-white"></i> Change Password</span>
                            </button><br/>&nbsp;<br/><span id="pwd_msg" style="font-style: normal; color: red">&nbsp;</span>&nbsp;<br/>
                       </form>
                       </td>
                   </tr>
               </table>
               <form id="locale_frm">
               <table border="0" width="100%">
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Locale Settings</td>
                   </tr>
                   <tr style="background-color: snow">
                       <td colspan="2"><i style="color: gray">Time zone</i><br/><?= timezone_menu($timezone); ?></td>
                   </tr>
                   <tr>
                       <td colspan="2">Date and time will be displayed as per the timezone selected.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Daylight saving</i></td>
                       <td width="20%">
                           <input type="radio" name="daylight" value="TRUE"<?= ($daylight == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="daylight" value="FALSE"<?= ($daylight == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Configure Daylight saving option.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Display sidebar on..</i></td>
                       <td width="20%">
                           <input type="radio" name="sidebar" value="LEFT"<?= ($sidebar == "LEFT")?" checked":"" ?>> &nbsp; Left<br/>
                           <input type="radio" name="sidebar" value="RIGHT"<?= ($sidebar == "RIGHT")?" checked":"" ?>> &nbsp; Right
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Set whether you want the sidebar to be displayed on left or right side of the page.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Sidebar view</i></td>
                       <td width="20%">
                           <input type="radio" name="sidebar_view" value="compact"<?= ($sidebar_view == "compact")?" checked":"" ?>> &nbsp; Compact<br/>
                           <input type="radio" name="sidebar_view" value="expand"<?= ($sidebar_view == "expand")?" checked":"" ?>> &nbsp; Expand
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Select "<b>Compact</b>" for a sidebar with lesser options. Selecting "<b>Expand</b>" will display all available options.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr>
                       <td class="thead" colspan="2">&nbsp;</td>
                   </tr>
                   <tr>
                       <td colspan="2">
                           <button class="btn btn-success" href="#" id="settings_sbmt_btn">
                            <span class="btn-label"><i class="icon-lock icon-white"></i> Save Settings</span>
                           </button><br/>&nbsp;
                           <p id="result" style="color:red">&nbsp;</p>
                       </td>
                   </tr> 
               </table>
               </form>
                  <p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>