<?php Template::printHeader_(); ?>
  </head> 
  <body>
      <?php Template::printTopMenu_('accounts'); ?>
      <?php Template::printSideBar_("terminate_account"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/accounts/">Accounts</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Terminate account</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <p><b>Terminate Account</b></p><hr/>
                
     <table class="table table-striped table-condensed display">
	<thead>
            <tr><th>User</th><th>Domain</th><th class="hidden-phone">Server</th><th class="hidden-phone">IP Address</th><th class="hidden-phone hidden-tablet">Owner</th><th>Action</th></tr>
	</thead>
        <tbody>
        <?php foreach($accounts_list as $account) :?>
            <tr style="text-align:center">
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->user ?>/"><?= $account->user ?></a></td>
                <td><a href="http://<?= $account->domain ?>" target="_blank"><?= $account->domain ?></a></td>
                <td class="hidden-phone"><a href="<?= site_url() ?>/servers/viewserver/info/<?= $account->server_id ?>/"><?= $account->s_name ?></a></td>
                <td class="hidden-phone"><a href="http://<?= $account->ip ?>"><?= $account->ip ?></a></td>
                <td class="hidden-tablet hidden-phone"><?php if ($account->owner == "root") :?><?= $account->owner ?><?php else :?><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->owner ?>/"><?= $account->owner ?></a><?php endif; ?></td>
                <td><a href="<?= site_url() ?>/servers/viewserver/whm_removeacct/<?= $account->server_id ?>/<?= $account->user ?>/">terminate</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
     </table><p><?= $this->pagination->create_links(); ?></p><p>&nbsp;</p>
                <p><span class="label">Note</span> Accounts belonging to INACTIVE server(s) will not be shown.
                </p>
     <p>&nbsp;</p><p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>