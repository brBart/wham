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
          <li>
            <a href="<?php echo site_url(); ?>/welcome/servers/">Servers</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/servers/listservers/">List all servers</a>
            <span class="divider">/</span>
          </li>
          <li class="active"><?=$hostname ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <ul class="nav nav-tabs">
                    <li>
                        <a href="<?= site_url() . "/servers/viewserver/info/" . $s_id . "/" ?>">Server details</a> 
                    </li>
                    <li class="active">
                        <a href="#"><b>Web Host Manager</b></a>
                    </li>
                </ul>
              <?php if (isset($load_txt)) :?>
                <p class="alert alert-success"><i class="icon-info-sign"></i> <small><b>Load Average: <?=$load_txt ?></b></small></p>
              <?php endif; ?>
                <p>&nbsp;</p>
                
              <div class="row-fluid">
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-th-list"></i> <b>Accounts</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listaccts", $apis_available)) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_listaccts/" . $s_id . "/"?>">List Accounts</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "createacct", $apis_available) && TRUE == Essentials::checkWhamUserRole("add_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_createacct/" . $s_id . "/"?>">Create A New Account</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "modifyacct", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-account", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifyacct/" . $s_id . "/"?>">Modify Account</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "changepackage", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-account", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_updownacct/" . $s_id . "/"?>">Upgrade/Downgrade Account</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "removeacct", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "kill-acct", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_removeacct/" . $s_id . "/"?>">Terminate Account</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "editquota", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "quota", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifyquota/" . $s_id . "/"?>">Quota Modification</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "passwd", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "passwd", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifypasswd/" . $s_id . "/"?>">Modify Password</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "setsiteip", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_setsiteip/" . $s_id . "/"?>">Change Site's IP Address</a></li><?php endif; ?><?php endif; ?>
                    
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "setupreseller", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifyacctowner/" . $s_id . "/"?>">Change Account Ownership</a></li><?php endif; ?><?php endif; ?>
                    
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listsuspended", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "suspend-acct", $priv_list)) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_listsuspended/" . $s_id . "/"?>">List Suspended Accounts</a></li>
                    <?php if(TRUE == Essentials::checkWhamUserRole("modify_account")) :?><li><a href="<?= site_url() . "/servers/viewserver/whm_suspendacct/" . $s_id . "/"?>">Suspend An Account</a></li><?php endif; ?><?php endif; ?>
                </ul>
                <p></p>
                <?php if (TRUE == Essentials::checkWhamUserRole("add_account")) :?>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-globe"></i> <b>DNS Functions</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "adddns", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "create-dns", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("add_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_adddns/" . $s_id . "/"?>">Add A DNS Zone</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "adddns", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-dns", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_editdns/" . $s_id . "/"?>">Edit DNS Zone</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "killdns", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "kill-dns", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_killdns/" . $s_id . "/"?>">Delete A DNS Zone</a></li><?php endif; ?><?php endif; ?>
                </ul><p></p>
                <?php endif; ?>
                <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listips", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-bookmark"></i> <b>Manage IPs</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_listips/" . $s_id . "/"?>">List All IP Addresses</a></li>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "addip", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("edit_server")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_addip/" . $s_id . "/"?>">Add New IP Address</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "delip", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("edit_server")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_delip/" . $s_id . "/"?>">Remove An IP Address</a></li><?php endif; ?><?php endif; ?>
                </ul><p></p>
                <?php endif; ?>
              </div>
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-hdd"></i> <b>Packages</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listpkgs", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-pkg", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_listpkgs/" . $s_id . "/"?>">List All Packages</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "addpkg", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "add-pkg", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_addpkg/" . $s_id . "/"?>">Add A Package</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "killpkg", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-pkg", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_killpkg/" . $s_id . "/"?>">Delete A Package</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "editpkg", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "edit-pkg", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_editpkg/" . $s_id . "/"?>">Edit A Package</a></li><?php endif; ?>
                </ul>
                <p></p>
                
                <?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-th-list"></i> <b>Multi-Account Functions</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "removeacct", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "kill-acct", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("delete_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_removeacct_multi/" . $s_id . "/"?>">Terminate Multiple Accounts</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "editquota", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "quota", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifyquota_multi/" . $s_id . "/"?>">Quota Modification</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "setsiteip", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_setsiteip_multi/" . $s_id . "/"?>">Change Site's IP Address</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "setupreseller", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_modifyacctowner_multi/" . $s_id . "/"?>">Change Account Ownership</a></li><?php endif; ?><?php endif; ?>
                    <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listsuspended", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "suspend-acct", $priv_list)) :?>
                    <?php if(TRUE == Essentials::checkWhamUserRole("modify_account")) :?><li><a href="<?= site_url() . "/servers/viewserver/whm_suspendacct_multi/" . $s_id . "/"?>">Suspend Multiple Accounts</a></li>
                    <li><a href="<?= site_url() . "/servers/viewserver/whm_unsuspendacct_multi/" . $s_id . "/"?>">Unsuspend Multiple Accounts</a></li><?php endif; ?><?php endif; ?>
                </ul>
                <p></p>
                <?php endif; ?>
                
                
                <?php if(TRUE === Cpanelwhm::hasApi($s_id, "listresellers", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-tags"></i> <b>Reseller Functions</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_listresellers/" . $s_id . "/"?>">List All Resellers</a></li>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "setupreseller", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("edit_server") || TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_setupreseller/" . $s_id . "/"?>">Add Reseller Privileges</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "unsetupreseller", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("edit_server") || TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_suspendreseller/" . $s_id . "/"?>">Suspend A Reseller</a></li><?php endif; ?><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "unsuspendreseller", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?><?php if (TRUE == Essentials::checkWhamUserRole("edit_server") || TRUE == Essentials::checkWhamUserRole("modify_account")) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_unsuspendreseller/" . $s_id . "/"?>">Unsuspend Reseller</a></li><?php endif; ?><?php endif; ?>
                </ul><p></p>
                <?php endif; ?>
              </div>
              <div class="span4">
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-off"></i> <b>Server Administration</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_serverinfo/" . $s_id . "/"?>">Server Info</a></li>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_servicestatus/" . $s_id . "/"?>">Service Status</a></li>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "configureservice", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_configureservice/" . $s_id . "/"?>">Configure Service</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "restartservice", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_restartservice/" . $s_id . "/"?>">Restart A Service</a></li><?php endif; ?>
                  <?php if(TRUE === Cpanelwhm::hasApi($s_id, "reboot", $apis_available) || TRUE === Cpanelwhm::hasPriv($s_id, "all", $priv_list)) :?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_reboot/" . $s_id . "/"?>">Reboot Server</a></li><?php endif; ?>
                </ul>
                <p></p>
                <ul class="nav nav-tabs">
                  <li class="active">
                    <a href="#"><i class="icon-wrench"></i> <b>WHAM!</b></a> 
                  </li>
                </ul>
                <p></p>
                <ul>
                  <?php if (TRUE == Essentials::checkWhamUserRole("edit_server")) :?><li><a href="<?= site_url() . "/servers/viewserver/whm_setremotekey/" . $s_id . "/"?>">Configure WHM Remote Key</a></li><?php endif; ?>
                  <li><a href="<?= site_url() . "/servers/viewserver/whm_syncserverinfo/" . $s_id . "/"?>">Sync Server Info In WHAM!</a></li>
                </ul>
                <p>&nbsp;</p>
              </div>
            </div>
                
            </div>
            
          </div>
        </div>
          <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>