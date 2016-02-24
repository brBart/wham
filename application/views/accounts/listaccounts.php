<?php Template::printHeader_(); ?>
    <style type="text/css" title="currentStyle">
            @import "<?= base_url()?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url()?>includes/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
               $('#example').dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "aaSorting": [[ 0, "asc" ]]
               });
               $("input[aria-controls=example]").css({"width": "120px", "height" : "12px"});
               $("select[aria-controls=example]").css({"width": "70px"});
            });
    </script>
  </head>
  <body>
      <?php Template::printTopMenu_('accounts'); ?>
      <?php Template::printSideBar_("list_accounts"); ?>
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
          <li class="active">List all accounts</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <p><b>List All Accounts</b></p><hr/>
     <table class="table table-hover table-striped table-condensed display"<?php if(count($accounts_list) > 10) :?> id="example"<?php endif; ?>>
	<thead>
            <tr><th>User</th><th>Domain</th><th class="hidden-phone">Server</th><th class="hidden-phone">IP Address</th><th class="hidden-phone">Owner</th><th class="hidden-phone hidden-tablet">Email</th></tr>
	</thead>
        
      
        <?php foreach($accounts_list as $account) :?>
            <tr>
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->user ?>/"><?= $account->user ?></a></td>
                <td><a target="_blank" href="http://<?= $account->domain ?>" target="_blank"><?= $account->domain ?></a></td>
                <td class="hidden-phone"><a href="<?= site_url() ?>/servers/viewserver/info/<?= $account->server_id ?>/"><?= $account->s_name ?></a></td>
                <td class="hidden-phone"><a href="http://<?= $account->ip ?>"><?= $account->ip ?></a></td>
                <td class="hidden-phone"><?php if ($account->owner == "root") :?><?= $account->owner ?><?php else :?><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $account->server_id ?>/<?= $account->owner ?>/"><?= $account->owner ?></a><?php endif; ?></td>
                <td class="hidden-phone hidden-tablet"><?= $account->email ?></td>
            </tr>
        <?php endforeach; ?>
        
     </table>
                <p>&nbsp;</p><p>&nbsp;</p>
                <p><span class="label">Note</span> Accounts belonging to INACTIVE server(s) will not be shown.
                </p>
     <p>&nbsp;</p><p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>