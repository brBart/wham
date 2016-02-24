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
            <a href="<?php echo site_url(); ?>/servers/listdcs/">List all data centers</a>
            <span class="divider">/</span>
          </li>
          <li class="active"><?=$dc_data->dc_name ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <ul class="nav nav-tabs">
                    <li>
                        <a href="<?= site_url(). "/servers/listdcs/view/" . $dc_data->dc_id . "/" ?>">Data center details</a> 
                    </li>
                    <li class="active">
                        <a href="#"><b>Servers (<?=$no_of_servers ?>)</b></a> 
                    </li>
                </ul>
                
<?php if ($no_of_servers == 0) :?>
                No servers found.
<?php else :?>
                <p>&nbsp;</p>
                <table class="table table-striped table-hover table-bordered">
	<thead>
            <tr>
                <th>Name</th>
                <th>Hostname</th>
                <th class="hidden-phone">Control Panel</th>
                <th class="hidden-phone">IP Address</th>
                <th class="hidden-phone hidden-tablet">Rack Location</th>
            </tr>
	</thead>
	<tbody>
            <?php foreach($server_list as $server) :?>
                    <tr>
                        <td><a href="<?= site_url(). "/servers/viewserver/info/" . $server->s_id . "/" ?>"><?=$server->s_name ?></a></td>
                        <td><a href="http://<?=$server->s_hostname ?>" target="_blank"><?=$server->s_hostname ?></a></td>
                        <td class="hidden-phone">
            <?php if ($server->p_name == "CPanel/WHM") :?>        
                        <a href="<?= site_url() ?>/servers/viewserver/redirect/whm/<?=$server->s_id ?>/" target="_blank""><?=$server->p_name ?></a>
            <?php endif; ?>
                        </td>
                        <td class="hidden-phone"><a href="http://<?=$server->s_ip ?>" target="_blank"><?=$server->s_ip ?></a></td>
                        <td class="hidden-phone hidden-tablet"><?=$server->s_rack ?></td>
                  </tr>
                <?php endforeach; ?>
	</tbody>
        </table>
<?php endif; ?>
                <p>&nbsp;</p><p>&nbsp;</p>
                <?php if(TRUE == Essentials::checkWhamUserRole("add_server")) :?>
                <p align="right">
                    <a class="btn btn-success" href="<?= site_url() . "/servers/addserver/index/" . $dc_data->dc_id . "/" ?>">
                    <span class="btn-label"><i class="icon-file icon-white"></i> Add new server to <?= $dc_data->dc_name ?></span>
                    </a>
                </p>
                <?php endif; ?>
                <p>&nbsp;</p><p>&nbsp;</p>
              </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>