<?php Template::printHeader_(); ?>
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
          <li class="active">Servers</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
                
                <ul>
                  <li><a href="<?php echo site_url() . '/servers/listservers/' ?>">List All Servers</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_server")) :?><li><a href="<?php echo site_url() . '/servers/addserver/' ?>">Add A New Server</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/servers/listdcs/' ?>">List All Data Centers</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_dc")) :?><li><a href="<?php echo site_url() . '/servers/adddc/' ?>">Add A New Data Center</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/servers/serversearch/' ?>">Search ...</a></li>
                </ul>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
   <?php Template::printFooter_(); ?>   