<?php Template::printHeader_(); ?>
  </head>
  <body>
    <?php Template::printTopMenu_('home'); ?>
    <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Home</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <p>Welcome to
                  <font color="green">
                    <b>Web Host Account Manager</b>
                  </font>!</p>
                <hr>
                <p></p>
              
            </div>
            <div class="row-fluid">
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="<?php echo site_url() . '/welcome/servers/'; ?>"><i class="icon-tasks"></i> <b>Servers</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?php echo site_url() . '/servers/listservers/' ?>">List All Servers</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_server")) :?><li><a href="<?php echo site_url() . '/servers/addserver/' ?>">Add A New Server</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/servers/listdcs/' ?>">List All Data Centers</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_dc")) :?><li><a href="<?php echo site_url() . '/servers/adddc/' ?>">Add A New Data Center</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/servers/serversearch/' ?>">Search ...</a></li>
                </ul>
                <p></p>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="<?php echo site_url() . '/welcome/settings/'; ?>"><i class="icon-wrench"></i> <b>Settings</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if(Mysession::getVar("username") == "admin") :?><li><a href="<?php echo site_url() . '/settings/wham_settings/'; ?>">WHAM! Settings</a></li>
                  <?php if(isset($email_alerts) && $email_alerts == TRUE) :?><li><a href="<?php echo site_url() . '/settings/email_settings/'; ?>">Email Settings</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/settings/firewall_settings/'; ?>">Configure Firewall</a></li>
                  <li><a href="<?php echo site_url() . '/settings/event_log/'; ?>">WHAM! Event Log</a></li>
                  <li><a href="<?php echo site_url() . '/settings/user_roles/'; ?>">Users And Roles</a></li>
                  <li><a href="<?php echo site_url() . '/settings/reset_passwd/'; ?>">Reset Password</a></li>
		<?php endif; ?>
                  <?php if(Mysession::getVar("username") != "admin") :?><li><a href="<?php echo site_url() . '/settings/user_pref/'; ?>">User Preferences</a></li><?php endif; ?>
                </ul>
                <p></p>
              </div>
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="<?php echo site_url() . '/welcome/accounts/'; ?>"><i class="icon-th-list"></i> <b>Accounts</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?php echo site_url() . '/accounts/listaccounts/' ?>">List All Accounts</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_account")) :?><li><a href="<?php echo site_url() . '/accounts/addaccount/' ?>">Create A New Account</a></li><?php endif; ?>
                  <?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?><li><a href="<?php echo site_url() . '/accounts/modifyaccount/' ?>">Modify Account</a></li><?php endif; ?>
                  <?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?><li><a href="<?php echo site_url() . '/accounts/terminateaccount/' ?>">Terminate Account</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/accounts/listduplicates/' ?>">List All Duplicate Accounts</a></li>
                  <li><a href="<?php echo site_url() . '/accounts/listsuspended/' ?>">List All Suspended Accounts</a></li>
                  <li><a href="<?php echo site_url() . '/accounts/accountsearch/' ?>">Search ...</a></li>
                </ul>
                <p></p>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="<?php echo site_url() . '/welcome/help/'; ?>"><i class="icon-book"></i> <b>Help</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="#doc" data-toggle="modal">Documentation</a></li>
                  <li><a href="#bug" data-toggle="modal">Report A Bug</a></li>
                </ul>
                <p></p>
              </div>
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="<?php echo site_url() . '/welcome/utilities/'; ?>"><i class="icon-shopping-cart"></i> <b>Utilities</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?php echo site_url() . '/utilities/whois/'; ?>">WHOIS Lookup</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/rblcheck/'; ?>">Check RBL</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/dnsreport/'; ?>">DNS Report</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/mxlookup/'; ?>">MX Lookup</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/maildiagnostics/'; ?>">Mail Diagnostics</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/portscan/'; ?>">Port Scan Tool</a></li>
                </ul>
                <p></p>
              </div>
            </div>
            </div>
            </div>
          </div>
        </div>
      
      
      <div id="doc" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="doc" aria-hidden="true">
  <div class="modal-header">
    <h3 id="doc">Documentation</h3>
  </div>
  <div class="modal-body">
    <p class="lead">WHAM! is still in its infancy.</p>
    <p>Please visit <a href="http://www.whamcp.com/wiki/" target="_blank">whamcp.com/wiki/</a> for documentation and support.</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-success" data-dismiss="modal" aria-hidden="true">OK</button>
  </div>
</div>
      
      
      <div id="bug" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="bug" aria-hidden="true">
  <div class="modal-header">
    <h3 id="bug">Report A Bug</h3>
  </div>
  <div class="modal-body">
    <p class="lead">WHAM! is still in its infancy.</p>
    <p>Please visit <a href="http://support.whamcp.com" target="_blank">support.whamcp.com</a> to report any bugs. </p>
    <p>You can also drop in an email about any issues you experience in WHAM! to <a href="mailto:support@whamcp.com">support@whamcp.com</a> and we will quick to assist.</p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-success" data-dismiss="modal" aria-hidden="true">OK</button>
  </div>
</div>
    
    <?php Template::printFooter_(); ?>
