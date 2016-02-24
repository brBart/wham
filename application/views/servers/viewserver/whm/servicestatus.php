<?php Template::printHeader_(); ?>
  </head>
<?php if (isset($service_list)) $no_of_services = count($service_list); ?>  
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
              
                <p><b>Service Status</b></p><hr/>
     <?php if (isset($service_list)) :?>           
     <table class="table table-hover table-striped table-condensed">
	<thead>
            <tr><th>Name</th><th>Installed</th><th>Enabled</th><th>Monitored</th><th>Running</th><th class="hidden-phone">Description</th></tr>
	</thead>
        <tbody>
      <?php for($i=0; $i<$no_of_services; $i++) :?>
            <tr style="text-align: center">
                <td><?= $service_list[$i]["name"] ?></td>
                <td><img src="<?= base_url()?>includes/images/<?= ($service_list[$i]["installed"] == 1)? "tick":"cross" ?>.png"></td>
                <td><img src="<?= base_url()?>includes/images/<?= ($service_list[$i]["enabled"] == 1)? "tick":"cross" ?>.png"></td>
                <td><img src="<?= base_url()?>includes/images/<?= ($service_list[$i]["monitored"] == 1)? "tick":"cross" ?>.png"></td>
                <td><?php if (isset($service_list[$i]["running"]) && $service_list[$i]["running"] == 1) :?><img src="<?= base_url()?>includes/images/tick.png"><?php endif;?></td>
                <td class="hidden-phone"><i><?= $service_list[$i]["display_name"] ?></i></td>
            </tr>
       
      <?php endfor; ?>
        </tbody>
     </table><p>&nbsp;</p>
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