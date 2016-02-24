<?php Template::printHeader_(); ?>
    <style type="text/css" title="currentStyle">
            @import "<?= base_url()?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url()?>includes/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
               $('#example').dataTable({
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": false,
                    "bInfo": false,
                    "bAutoWidth": true,
                    "aaSorting": [[ 0, "asc" ]]
               });
            });
    </script>
  </head>
<?php if (isset($ip_list)) $no_of_ips = count($ip_list); ?>  
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
              
                <p><b>List IP Addresses</b></p><hr/>
     <?php if (isset($ip_list)) :?>           
     <table class="table table-hover table-striped table-condensed display"<?php if ($no_of_ips > 10) :?> id="example"<?php endif; ?>>
	<thead>
            <tr><th>Interface</th><th>IP Address</th><th>Active</th><th>In Use</th><th>Remove</th></tr>
	</thead>
        <tbody>
      <?php for($i=0; $i<$no_of_ips; $i++) :?>
            <tr style="text-align: center; <?= ($ip_list[$i]["mainaddr"] == 1)?"font-weight: bold; color: red":"";?>">
                <td><?= $ip_list[$i]["if"] ?></td>
                <td><?= $ip_list[$i]["ip"] ?></td>
                <td><img src="<?= base_url()?>/includes/images/<?= ($ip_list[$i]["active"] == 1)? "tick":"cross" ?>.png"></td>
                <td><img src="<?= base_url()?>/includes/images/<?= ($ip_list[$i]["used"] == 1)? "tick":"cross" ?>.png"></td>
                <td>
                <?php if ($ip_list[$i]["removable"] == 1 && TRUE == Essentials::checkWhamUserRole("edit_server")) :?><a href="<?= site_url() ?>/servers/viewserver/whm_delip/<?= $s_id ?>/<?= $ip_list[$i]["ip"] ?>/">remove</a><?php endif; ?>
                </td>
            </tr>
       
      <?php endfor; ?>
        </tbody>
     </table><p>&nbsp;</p>
     <p>&nbsp; &nbsp; Note: Server's main IP is displayed in <font color="red">red</font>.</p>
     <?php else :?>
     &nbsp; &nbsp; API/Network error
     <?php endif; ?>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>