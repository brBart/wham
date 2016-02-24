<?php Template::printHeader_(); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#save_sbmt').bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/",
                        data: $("#firewall_mode_frm").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#result").html(text);
                            th.removeClass("disabled");
                        }
                    });
                }
            });
            
            $("#add_to_whitelist_sbmt").bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#add_ip_whitelist_result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/add_to_whitelist/",
                        data: $("#ipwhitelist_add_frm").serialize(),
                        dataType: "json",
                        success: function(json) {
                            $("#add_ip_whitelist_result").html(json.message);
                            if (json.status == 1) {
                                msg = "<tr><td class='w_ip_td'>" + json.ip + "</td><td class='hidden-phone'>" + 
                                    json.comment + "</td><td class='hidden-phone'>" + json.time + "</td></tr>";
                                $("#w_ip_tbody").append(msg);
                                msg2 = "<option value='" + json.ip + "'>" + json.ip + "</option>";
                                $('#ipwhitelist_remove_frm select').append(msg2);
                            }
                            th.removeClass("disabled");
                            $("#ipwhitelist_add_frm input").val("");
                        }
                    });
                }
            });
            
            $("#remove_from_whitelist_sbmt").bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#rm_ip_whitelist_result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/remove_from_whitelist/",
                        data: $("#ipwhitelist_remove_frm").serialize(),
                        dataType: "json",
                        success: function(json) {
                            $("#rm_ip_whitelist_result").html(json.message);
                            if (json.status == 1) {
                                var ip = json.ip;
                                $('#ipwhitelist_remove_frm select option:contains(' + ip + ')').remove();
                                $('.w_ip_td:contains(' + ip + ')').parent().remove();
                            }
                            th.removeClass("disabled");
                        }
                    });
                }
            });
            
            
            
            
            $("#add_to_blacklist_sbmt").bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#add_ip_blacklist_result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/add_to_blacklist/",
                        data: $("#ipblacklist_add_frm").serialize(),
                        dataType: "json",
                        success: function(json) {
                            $("#add_ip_blacklist_result").html(json.message);
                            if (json.status == 1) {
                                msg = "<tr><td class='b_ip_td'>" + json.ip + "</td><td class='hidden-phone'>" + 
                                    json.comment + "</td><td class='hidden-phone'>" + json.time + "</td></tr>";
                                $("#b_ip_tbody").append(msg);
                                msg2 = "<option value='" + json.ip + "'>" + json.ip + "</option>";
                                $('#ipblacklist_remove_frm select').append(msg2);
                            }
                            th.removeClass("disabled");
                            $("#ipblacklist_add_frm input").val("");
                        }
                    });
                }
            });
            
            $("#remove_from_blacklist_sbmt").bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#rm_ip_blacklist_result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/remove_from_blacklist/",
                        data: $("#ipblacklist_remove_frm").serialize(),
                        dataType: "json",
                        success: function(json) {
                            $("#rm_ip_blacklist_result").html(json.message);
                            if (json.status == 1) {
                                var ip = json.ip;
                                $('#ipblacklist_remove_frm select option:contains(' + ip + ')').remove();
                                $('.b_ip_td:contains(' + ip + ')').parent().remove();
                            }
                            th.removeClass("disabled");
                        }
                    });
                }
            });
            
            $('#whitelisturl_sbmt').bind("click", function(e) {
                e.preventDefault();
                var th = $(this);
                if (th.hasClass("disabled") != "false") {
                    th.addClass("disabled");
                    $("#whitelisturl_result").html('&nbsp;');
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/firewall_settings/whitelist_url/",
                        data: $("#whitelisturl_frm").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#whitelisturl_result").html(text);
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
      <?php Template::printSideBar_("firewall_settings"); ?>
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
          <li class="active">Configure firewall</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <p><b>Firewall Settings</b><hr/></p>
            <?php if (isset($firewall) && $firewall == "FALSE") :?>
            <div class="alert alert-success">Firewall is currently OFF. Turn it On first <a href="<?= site_url() ?>/settings/wham_settings/">here</a></div>
            <?php else :?>
                  <p>&nbsp;</p>
              <ul class="nav nav-tabs">
                  <li class="active"><a href="#readme" data-toggle="tab">Read This First</a></li>
                  <li><a href="#firewall" data-toggle="tab">Firewall</a></li>
              </ul>    
              <div class="tab-content">
                <div class="tab-pane active" id="readme"><br/>
                    <p><span class="label label-important">Important</span> Before 
                        activating any firewall mode, or making any other changes, please read 
                    and understand how WHAM! firewall works.</p>
                    <p class="text-error">DO NOT END UP BLOCKING YOURSELF, SO BE PATIENT AND CONTINUE 
                    READING..<hr/></p>
                    <p>&nbsp;</p>
                    <dl>
                        <dt><i class="icon-hand-right"></i> What is WHAM! Firewall?</dt>
                        <dd>
                            <p><br/>WHAM! Firewall is a security feature included in Web Host Account Manager (WHAM!) that allows 
                            <abbr title="'admin' user has full access in WHAM. You are reading this content because you have logged in as 'admin'">admin</abbr> 
                            user to restrict access to WHAM! from certain IPs or an IP range.<br/>&nbsp;</p>
                            <p class="muted"><span class="label label-success"><i class="icon-info-sign icon-white"></i></span> What WHAM! Firewall Is</p>
                            <ul>
                                <li>A security feature meant only for WHAM!</li>
                                <li>It restricts access to WHAM! resources (including pages, as well as remote api calls)</li>
                            </ul>
                            <p class="muted"><br/><span class="label label-warning"><i class="icon-minus-sign icon-white"></i></span> What WHAM! Firewall Is NOT</p>
                            <ul>
                                <li>An alternative for <abbr title="The standard firewall in Linux distros">iptables</abbr></li>
                                <li>An alternative for <abbr title="Config Server Firewall">CSF</abbr> / <abbr title="Advanced Policy Firewall">APF</abbr> or any other firewall.</li>
                            </ul>
                            <div class="alert alert-success">Blocking an ip or network in WHAM! firewall does not prevent 
                                it from accessing various services running on your server like http / mail / ftp etc.</div>
                        </dd>
                    </dl>
                    <dl>
                        <dt><i class="icon-hand-right"></i> WHAM! Firewall Modes</dt>
                        <dd>
                            <p><br/>WHAM! Firewall works in either of the two modes:</p>
                            <ul>
                                <li>ALLOW ALL, BLOCK FEW</li>
                                <li>BLOCK ALL, ALLOW FEW</li>
                            </ul>
                            <p>When firewall mode is set to <span class="text-warning">ALLOW ALL, BLOCK FEW</span>, WHAM! will allow 
                            connections from all ips / networks except the ones that are listed in <b>IP Blacklist</b>. IP Blacklist is 
                            a list of ips and ranges that can be configured by admin user and is available in the <i>Firewall</i> tab on this page.</p>
                            <p>And when firewall mode is set to <span class="text-warning">BLOCK ALL, ALLOW FEW</span>, WHAM! will block 
                            connections from all ips / networks. Only the ones that are listed in <b>IP Whitelist</b> will be able to access WHAM!. 
                            IP Whitelist is a list of ips and ranges that can be configured by admin user and is available in the <i>Firewall</i> tab on this page.</p>
                        </dd>
                    </dl>
                    <div class="alert alert-info">When firewall is set to <span class="text-success">ALLOW ALL, BLOCK FEW</span> mode, the only list that matters is <b>IP Blacklist</b>.</div>
                    <div class="alert alert-warning">When firewall is set to <span class="text-success">BLOCK ALL, ALLOW FEW</span> mode, you need to make changes in <b>IP Whitelist</b>.</div>
                    
                    <p>&nbsp;</p>
                </div>
                <div class="tab-pane" id="firewall">
                    <p>&nbsp;</p>
                    Choose your desired firewall mode:<br/>
                    <form id="firewall_mode_frm">
                    <select name="firewall_mode">
                        <option value="none"<?= (isset($current_mode) && $current_mode == "none")?" selected":"" ?>>none</option>
                        <option value="ALLOW_ALL_BLOCK_FEW"<?= (isset($current_mode) && $current_mode == "ALLOW_ALL_BLOCK_FEW")?" selected":"" ?>>ALLOW ALL, BLOCK FEW</option>
                        <option value="BLOCK_ALL_ALLOW_FEW"<?= (isset($current_mode) && $current_mode == "BLOCK_ALL_ALLOW_FEW")?" selected":"" ?>>BLOCK ALL, ALLOW FEW</option>
                    </select>
                    <p id="result" class="text-error">&nbsp;</p>    
                    <p><button class="btn btn-info" href="#" id="save_sbmt">
                    <span class="btn-label"><i class="icon-ok icon-white"></i> Save</span>
                    </button><hr/></p>
                    </form>
                    <div class="tabbable tabs-below">
                    <div class="tab-content">
                        <div class="tab-pane active" id="ipwhitelist_div">
                            <p><b>IP Whitelist</b><hr/><span class="label label-important">Note</span> 
                            IP Whitelist is only checked for when firewall mode is <b class="text-warning">BLOCK ALL, ALLOW FEW</b>
                            </p><p>&nbsp;</p>
                            <table class="table table-striped table-condensed">
                                <thead><tr><th>IP Address</th><th class="hidden-phone">Comment</th><th class="hidden-phone">Added On</th></tr></thead>
                                <tbody id="w_ip_tbody">
                                    <?php if (isset($w_ips_list)) :?>
                                    <?php foreach($w_ips_list as $row) :?>
                                    <tr><td class="w_ip_td"><?= $row->a_ip; ?></td><td class="hidden-phone"><?= $row->a_comment ?></td><td class="hidden-phone"><?= Essentials::toLocalTime($row->a_dateofa) ?></td></tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table><p>&nbsp;</p>
                            <div class="row-fluid">
                                <div class="span6">
                                    <table class="table table-striped table-condensed">
                                        <thead><tr><th><i class="icon-hand-right"></i> Add New IP</th></tr></thead>
                                        <tbody>
                                        <tr><td>
                                        <form id="ipwhitelist_add_frm">
                                            <p>IP Address<br/><input type="text" maxlength="50" name="ip_address"></p>
                                            <p>Comment<br/><input type="text" maxlength="40" name="comment"></p>
                                            <p><button class="btn btn-success" type="submit" id="add_to_whitelist_sbmt"><i class="icon-plus-sign icon-white"></i> Add to IP Whitelist</button></p><p id="add_ip_whitelist_result" style="color:red">&nbsp;</p>
                                            <p class="muted">Usage e.g.:<br/><i class="icon-hand-right"></i> <b class="text-warning">65.98.44.100</b> will add it to whitelist<br/><i class="icon-hand-right"></i> <b class="text-warning">65.98.44.0</b> will add range <i>65.98.44.0 - 65.98.44.255</i> to whitelist</p>
                                        </form>
                                        </td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="span6">
                                    <table class="table table-striped table-condensed">
                                        <thead><tr><th><i class="icon-hand-right"></i> Remove IP</th></tr></thead>
                                        <tbody>
                                            <tr><td><form id="ipwhitelist_remove_frm">
                                            <p>Select the IP you wish to remove</p>
                                            <p><select name="ip_address"><option value="0">-- select --</option><?php if (isset($w_ips_list)) :?><?php foreach($w_ips_list as $row) :?><option value="<?= $row->a_ip ?>"><?= $row->a_ip ?></option><?php endforeach; ?><?php endif; ?></select></p>
                                            <p><button class="btn btn-success" type="submit" id="remove_from_whitelist_sbmt"><i class="icon-minus-sign icon-white"></i> Remove from IP Whitelist</button></p><p id="rm_ip_whitelist_result" style="color:red">&nbsp;</p>
                                            </form></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <div class="tab-pane" id="ipblacklist_div">
                            <p><b>IP Blacklist</b><hr/><span class="label label-important">Note</span> 
                            IP Blacklist is only checked for when firewall mode is <b class="text-warning">ALLOW ALL, BLOCK FEW</b>
                            </p><p>&nbsp;</p>
                            <table class="table table-striped table-condensed">
                                <thead><tr><th>IP Address</th><th class="hidden-phone">Comment</th><th class="hidden-phone">Added On</th></tr></thead>
                                <tbody id="b_ip_tbody">
                                    <?php if (isset($b_ips_list)) :?>
                                    <?php foreach($b_ips_list as $row) :?>
                                    <tr><td class="b_ip_td"><?= $row->b_ip; ?></td><td class="hidden-phone"><?= $row->b_comment ?></td><td class="hidden-phone"><?= Essentials::toLocalTime($row->b_dateofa) ?></td></tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table><p>&nbsp;</p>
                            <div class="row-fluid">
                                <div class="span6">
                                    <table class="table table-striped table-condensed">
                                        <thead><tr><th><i class="icon-hand-right"></i> Add New IP</th></tr></thead>
                                        <tbody>
                                        <tr><td>
                                        <form id="ipblacklist_add_frm">
                                            <p>IP Address<br/><input type="text" maxlength="50" name="ip_address"></p>
                                            <p>Comment<br/><input type="text" maxlength="40" name="comment"></p>
                                            <p><button class="btn btn-success" type="submit" id="add_to_blacklist_sbmt"><i class="icon-plus-sign icon-white"></i> Add to IP Blacklist</button></p><p id="add_ip_blacklist_result" style="color:red">&nbsp;</p>
                                            <p class="muted">Usage e.g.:<br/><i class="icon-hand-right"></i> <b class="text-warning">65.98.44.100</b> will add it to blacklist<br/><i class="icon-hand-right"></i> <b class="text-warning">65.98.44.0</b> will add range <i>65.98.44.0 - 65.98.44.255</i> to blacklist</p>
                                        </form>
                                        </td></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="span6">
                                    <table class="table table-striped table-condensed">
                                        <thead><tr><th><i class="icon-hand-right"></i> Remove IP</th></tr></thead>
                                        <tbody>
                                            <tr><td><form id="ipblacklist_remove_frm">
                                            <p>Select the IP you wish to remove</p>
                                            <p><select name="ip_address"><option value="0">-- select --</option><?php if (isset($b_ips_list)) :?><?php foreach($b_ips_list as $row) :?><option value="<?= $row->b_ip ?>"><?= $row->b_ip ?></option><?php endforeach; ?><?php endif; ?></select></p>
                                            <p><button class="btn btn-success" type="submit" id="remove_from_blacklist_sbmt"><i class="icon-minus-sign icon-white"></i> Remove from IP Blacklist</button></p><p id="rm_ip_blacklist_result" style="color:red">&nbsp;</p>
                                            </form></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="tab-pane" id="whitelisturl_div">
                            <p><b>Whitelist URL</b><hr/><span class="label label-important">Note</span> 
                            Whitelist URL is only available when firewall mode is <b class="text-warning">BLOCK ALL, ALLOW FEW</b>
                            <div class="alert alert-error">The whitelist URL and password that you configure here should be kept safe from wrong hands!</div>
                            </p><p>&nbsp;</p>
                            <form id="whitelisturl_frm">
                            <p><i class="icon-hand-right"></i> Whitelist URL<br/>
                            <span class="text-success"><?= site_url() ?>/settings/whitelist/allow/  <input type="text" name="whitelist_url" value="<?= $whitelist_url ?>"> /</span><br/>
                            To whitelist the current IP, simply visit this URL via browser and authenticate using the password (below).<br/>&nbsp;
                            </p>
                            <p><i class="icon-hand-right"></i> Whitelist Password<br/>
                            <input type="password" name="whitelist_passwd" value="<?= $whitelist_passwd ?>"><br/>
                            From a non-authorized IP, access the whitelist URL and enter this password to gain access to WHAM!<br/>&nbsp;
                            </p>
                            <p><button class="btn btn-success" type="submit" id="whitelisturl_sbmt"><i class="icon-pencil icon-white"></i> Save changes</button></p><p id="whitelisturl_result" style="color:red">&nbsp;</p><p>&nbsp;</p>
                            </form>
                        </div>
                        
                    </div> <!-- /tab-content -->
                    
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#ipwhitelist_div" data-toggle="tab">IP Whitelist</a></li>
                        <li><a href="#ipblacklist_div" data-toggle="tab">IP Blacklist</a></li>
                        <li><a href="#whitelisturl_div" data-toggle="tab">Whitelist URL</a></li>
                    </ul>
                    
                    
                    </div> <!-- /tabbable -->
                </div>
              </div>
              <?php endif; ?>
                  <p>&nbsp;</p><p>&nbsp;</p>
            </div>       
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
