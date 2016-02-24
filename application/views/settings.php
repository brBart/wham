<?php Template::printHeader_(); ?>
  </head>
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Settings</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
                
                <ul>
                  <?php if(Mysession::getVar("username") == "admin") :?><li><a href="<?php echo site_url() . '/settings/wham_settings/'; ?>">WHAM! Settings</a></li>
                  <?php if(isset($email_alerts) && $email_alerts == TRUE) :?><li><a href="<?php echo site_url() . '/settings/email_settings/'; ?>">Email Settings</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/settings/firewall_settings/'; ?>">Configure Firewall</a></li>
                  <li><a href="<?php echo site_url() . '/settings/event_log/'; ?>">WHAM! Event Log</a></li>
                  <li><a href="<?php echo site_url() . '/settings/user_roles/'; ?>">Users And Roles</a></li>
                  <li><a href="<?php echo site_url() . '/settings/reset_passwd/'; ?>">Reset Password</a></li>
                  <?php if(Mysession::getVar("username") != "admin") :?><li><a href="<?php echo site_url() . '/settings/user_pref/'; ?>">User Preferences</a></li><?php endif; ?>
                </ul>
              
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
