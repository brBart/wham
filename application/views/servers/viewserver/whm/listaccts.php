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
                        <a href="<?= site_url() . "/servers/viewserver/whm/" . $s_id . "/" ?>"><b>Web Host Manager</b></a>
                    </li>
                </ul>
              
                <p><b>List Accounts</b></p><hr/>
     <?php if (isset($account_list) && $account_list != FALSE && count($account_list) > 0) :?>           
     <table class="table table-hover table-striped table-condensed display"<?= (count($account_list) > 10)?' id="example"':"" ?>>
	<thead>
            <tr><th>User</th><th>Domain</th><th class="hidden-phone">IP Address</th>
                <th class="hidden-phone">Owner</th><th class="hidden-phone">Email</th>
                <th class="hidden-phone">Used</th><th class="hidden-phone hidden-tablet">Quota</th></tr>
	</thead>
        <tbody>
      <?php for($i=0; $i < count($account_list); $i++) :?>
            <tr>
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $s_id ?>/<?= $account_list[$i]->user ?>/"><?= $account_list[$i]->user ?></a></td>
                <td><a target="_blank" href="http://<?= $account_list[$i]->domain ?>"><?= $account_list[$i]->domain ?></a></td>
                <td class="hidden-phone"><a target="_blank" href="http://<?= $account_list[$i]->ip ?>"><?= $account_list[$i]->ip ?></a></td>
                <td class="hidden-phone"><?php if ($account_list[$i]->owner == "root") :?><?= $account_list[$i]->owner ?><?php else :?><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $s_id ?>/<?= $account_list[$i]->owner ?>/"><?= $account_list[$i]->owner ?></a><?php endif; ?></td>
                <td class="hidden-phone"><?= $account_list[$i]->email ?></td>
                <td class="hidden-phone"><?= $account_list[$i]->diskused ?></td>
                <td class="hidden-phone hidden-tablet"><?= $account_list[$i]->disklimit ?></td>
            </tr>
      <?php endfor; ?>
        </tbody>
     </table>
     <?php else :?>
       No accounts found.
     <?php endif; ?>
     <p>&nbsp;</p><p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>