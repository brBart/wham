<?php Template::printHeader_(); ?>
  </head>
  
  <body>
      <?php Template::printTopMenu_('accounts'); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Accounts</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              <ul>
                  <li><a href="<?php echo site_url() . '/accounts/listaccounts/' ?>">List All Accounts</a></li>
                  <?php if (TRUE == Essentials::checkWhamUserRole("add_account")) :?><li><a href="<?php echo site_url() . '/accounts/addaccount/' ?>">Create A New Account</a></li><?php endif; ?>
                  <?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?><li><a href="<?php echo site_url() . '/accounts/modifyaccount/' ?>">Modify Account</a></li><?php endif; ?>
                  <?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?><li><a href="<?php echo site_url() . '/accounts/terminateaccount/' ?>">Terminate Account</a></li><?php endif; ?>
                  <li><a href="<?php echo site_url() . '/accounts/listduplicates/' ?>">List All Duplicate Accounts</a></li>
                  <li><a href="<?php echo site_url() . '/accounts/listsuspended/' ?>">List All Suspended Accounts</a></li>
                  <li><a href="<?php echo site_url() . '/accounts/accountsearch/' ?>">Search ...</a></li>
                </ul>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>