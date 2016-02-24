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
        $("#settings_sbmt_btn").bind("click", function(e) {
            e.preventDefault();
            var th = $(this);
            if (th.hasClass("disabled") != "false") {
                th.addClass("disabled");
                $.ajax({
                    type: "POST",
                    url: "<?= site_url() ?>/settings/wham_settings/save/",
                    data: $("#settings_frm").serialize(),
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
      <?php Template::printSideBar_("wham_settings"); ?>
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
          <li class="active">WHAM! settings</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <form id="settings_frm">
               <table border="0" width="100%">
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Basic Settings</td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Add servers that has privileged or reserved IPs</i></td>
                       <td width="20%">
                           <input type="radio" name="allow_priv_reserve_ips" value="TRUE"<?= ($allow_priv_reserve_ips == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="allow_priv_reserve_ips" value="FALSE"<?= ($allow_priv_reserve_ips == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Set this option to <b>True</b> in order to add servers with main IP which is part of privileged
                           or reserved networks. To know more about reserved ip addresses, click <a 
                               href="http://http://en.wikipedia.org/wiki/Reserved_IP_addresses" 
                               target="_blank">here</a>.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Display Server's load average</i></td>
                       <td width="20%">
                           <input type="radio" name="show_load_avg" value="TRUE"<?= ($show_load_avg == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="show_load_avg" value="FALSE"<?= ($show_load_avg == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Setting this option to <b>True</b> will display a server's load average. NOTE: Shows 
                           real-time result, might slow down the time taken to load pages.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Logging</td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Enable logging of events</i></td>
                       <td width="20%">
                           <input type="radio" name="logging" value="TRUE"<?= ($logging == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="logging" value="FALSE"<?= ($logging == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">If set to <b>True</b>, WHAM! will track and log events when it 
                           happen. Useful for monitoring user actions in multi-user environments.<br/>&nbsp;
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2"><i>Select the events you wish to log</i><br/>&nbsp;<br/>
                           <input type="checkbox" name="log_login_attempts"<?= ($log_all_login_attempts == "TRUE")?" checked":"" ?>> &nbsp; Log all login / logout attempts<br/>
                           <input type="checkbox" name="log_server_actions"<?= ($log_actions == "TRUE")?" checked":"" ?>> &nbsp; Log all actions on servers that result in changes (account creation, modification, api calls etc)<br/>&nbsp;<br/>
                       </td>
                   </tr>
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Security</td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">WHAM! Firewall enabled?</i></td>
                       <td width="20%">
                           <input type="radio" name="restrict_access" value="TRUE"<?= ($firewall == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="restrict_access" value="FALSE"<?= ($firewall == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Enable WHAM! firewall to add extra layer of security. Firewall options can be later configured at WHAM! -> Settings -> Configure Firewall<br/>&nbsp;
                       </td>
                   </tr>
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Email notifications</td>
                   </tr>
                   <tr style="background-color: snow">
                       <td width="80%"><i style="color: gray">Do you wish to receive email notifications</i></td>
                       <td width="20%">
                           <input type="radio" name="email_notify" value="TRUE"<?= ($email_alerts == "TRUE")?" checked":"" ?>> &nbsp; True<br/>
                           <input type="radio" name="email_notify" value="FALSE"<?= ($email_alerts == "FALSE")?" checked":"" ?>> &nbsp; False
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2">Set this option to <b>True</b> in order to receive alerts via email
                           <br/>&nbsp;
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2"><i>Email address</i><br/>
                           <input type="text" name="email_address" placeholder="Email address" value="<?= $send_email_to ?>"><br/>&nbsp;<br/>
                           <i>Cc to: (leave blank if not required)</i><br/>
                           <input type="text" name="cc_email_address" placeholder="Cc: Email address" value="<?= $send_email_cc ?>"><br/>
                       </td>
                   </tr>
                   <tr>
                       <td colspan="2"><i>Select the events for which you wish to receive notifications</i><br/>&nbsp;<br/>
                           <input type="radio" name="email_login_attempts" value="all"<?= ($notify == "all")?" checked":"" ?>> &nbsp; Notify all login attempts<br/>
                           <input type="radio" name="email_login_attempts" value="failonly"<?= ($notify == "failonly")?" checked":"" ?>> &nbsp; Notify failed login attempts only<br/>&nbsp;<br/>
                           <input type="checkbox" name="email_ip_whitelist_frm_url"<?= ($notify_whitelist_from_url == "TRUE")?" checked":"" ?>> &nbsp; Notify when an IP is added to whitelist <abbr title="See WHAM! -> Settings -> Configure Firewall -> Firewall -> Whitelist URL for more..">via Whitelist URL</abbr><br/>&nbsp;<br/>
                       </td>
                   </tr>
                   <tr>
                       <td class="thead" colspan="2"><i class="icon-hand-right"></i> Locale Settings <i>(can be over-ridden by individual WHAM! user settings)</i></td>
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
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>