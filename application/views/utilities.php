<?php Template::printHeader_(); ?>
  </head>
  <body>
      <?php Template::printTopMenu_('utilities'); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Utilities</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
                
                <ul>
                  <li><a href="<?php echo site_url() . '/utilities/whois/'; ?>">WHOIS Lookup</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/rblcheck/'; ?>">Check RBL</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/dnsreport/'; ?>">DNS Report</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/mxlookup/'; ?>">MX Lookup</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/maildiagnostics/'; ?>">Mail Diagnostics</a></li>
                  <li><a href="<?php echo site_url() . '/utilities/portscan/'; ?>">Port Scan Tool</a></li>
                </ul>
              
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>