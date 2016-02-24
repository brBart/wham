<?php 
if (Mysession::getVar('username') == "admin") {
    $sql = "SELECT * FROM settings WHERE w_option = ? AND w_val = ?";
    $query = $this->db->query($sql, array("email_alerts", "TRUE"));

    if ($query->num_rows() == 1)
        $email_alerts_ = TRUE;
}
?>
<!-- printsidebar -->
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
        <form class="form-search hidden-desktop"  method="post" action="<?= site_url() ?>/welcome/searchall/">
            <div class="input-append">
                <input type="text" name="query" class="span9 search-query" placeholder="Search anything..">
                <button type="submit" class="btn"><i class="icon-search"></i></button>
            </div>
        </form>
        <div class="well sidebar-nav hidden-phone">
        <ul class="nav nav-list">
            
            <li class="nav-header">Servers</li>
            
            <li<?= ($sel == "list_all_servers")?' class="active"':"" ?>><a href="<?php echo site_url() . '/servers/listservers/' ?>">List All Servers</a></li>
            <?php if (TRUE == Essentials::checkWhamUserRole("add_server")) :?><li<?= ($sel == "add_new_server")?' class="active"':"" ?>><a href="<?php echo site_url() . '/servers/addserver/' ?>">Add A New Server</a></li><?php endif; ?>
            <li<?= ($sel == "list_all_dcs")?' class="active"':"" ?>><a href="<?php echo site_url() . '/servers/listdcs/' ?>">List All Data Centers</a></li>
            <?php if (TRUE == Essentials::checkWhamUserRole("add_dc")) :?><li<?= ($sel == "add_new_dc")?' class="active"':"" ?>><a href="<?php echo site_url() . '/servers/adddc/' ?>">Add A New Data Center</a></li><?php endif; ?>
            <li<?= ($sel == "serversearch")?' class="active"':"" ?>><a href="<?php echo site_url() . '/servers/serversearch/' ?>">Search ...</a></li>
            
            <li class="nav-header">Accounts</li>
            
            <li<?= ($sel == "list_accounts")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/listaccounts/' ?>">List All Accounts</a></li>
            <?php if (TRUE == Essentials::checkWhamUserRole("add_account")) :?><li<?= ($sel == "add_account")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/addaccount/' ?>">Create A New Account</a></li><?php endif; ?>
            <?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?><li<?= ($sel == "modify_account")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/modifyaccount/' ?>">Modify Account</a></li><?php endif; ?>
            <?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?><li<?= ($sel == "terminate_account")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/terminateaccount/' ?>">Terminate Account</a></li><?php endif; ?>
            <li<?= ($sel == "list_all_duplicates")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/listduplicates/' ?>">List All Duplicate Accounts</a></li>
            <li<?= ($sel == "list_all_suspended")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/listsuspended/' ?>">List All Suspended Accounts</a></li>
            <li<?= ($sel == "accountsearch")?' class="active"':"" ?>><a href="<?php echo site_url() . '/accounts/accountsearch/' ?>">Search ...</a></li>
            
            <li class="nav-header">Utilities</li>
            
            <li<?= ($sel == "whois")?' class="active"':"" ?>><a href="<?php echo site_url() . '/utilities/whois/'; ?>">WHOIS Lookup</a></li>
            <li<?= ($sel == "rblcheck")?' class="active"':"" ?>><a href="<?php echo site_url() . '/utilities/rblcheck/'; ?>">Check RBL</a></li>
            <li<?= ($sel == "dnsreport")?' class="active"':"" ?>><a href="<?php echo site_url() . '/utilities/dnsreport/'; ?>">DNS Report</a></li>
            <li class="nav-hidden<?= ($sel == "mxlookup")?' active':"" ?>"><a href="<?php echo site_url() . '/utilities/mxlookup/'; ?>">MX Lookup</a></li>
            <li class="nav-hidden<?= ($sel == "maildiag")?' active':"" ?>"><a href="<?php echo site_url() . '/utilities/maildiagnostics/'; ?>">Mail Diagnostics</a></li>
            <li class="nav-hidden<?= ($sel == "portscan")?' active':"" ?>"><a href="<?php echo site_url() . '/utilities/portscan/'; ?>">Port Scan Tool</a></li>
            
            <li class="nav-header nav-hidden">Settings</li>
            
            <?php if(Mysession::getVar("username") == "admin") :?><li class="nav-hidden<?= ($sel == "wham_settings")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/wham_settings/'; ?>">WHAM! Settings</a></li><?php endif; ?>
            <?php if(isset($email_alerts_) && $email_alerts_ == TRUE) :?><li class="nav-hidden<?= ($sel == "email_settings")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/email_settings/'; ?>">Email Settings</a></li><?php endif; ?>
            <li class="nav-hidden<?= ($sel == "firewall_settings")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/firewall_settings/'; ?>">Configure Firewall</a></li>
            <li class="nav-hidden<?= ($sel == "event_log")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/event_log/'; ?>">WHAM! Event Log</a></li>
            <li class="nav-hidden<?= ($sel == "u_and_r")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/user_roles/'; ?>">Users And Roles</a></li>
            <li class="nav-hidden<?= ($sel == "reset_pwd")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/reset_passwd/'; ?>">Reset Password</a></li>
            <?php if(Mysession::getVar("username") != "admin") :?><li class="nav-hidden<?= ($sel == "user_pref")?' active':"" ?>"><a href="<?php echo site_url() . '/settings/user_pref/'; ?>">User Preferences</a></li><?php endif; ?>
        </ul>
        </div><!--/.well -->
        <span class="pull-right hidden-phone"><i class="icon-chevron-down"></i> <a href="#" id="more_nav">more..</a> &nbsp;</span>
        <span class="hidden-phone"><br/>&nbsp;<br/></span>
    </div><!--/span-->
    <div class="span9">
<!-- printsidebar ends -->
