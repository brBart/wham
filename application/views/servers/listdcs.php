<?php Template::printHeader_(); ?>
  </head>
  
  <body>
      <?php Template::printTopMenu_('servers'); ?>
      <?php Template::printSideBar_("list_all_dcs"); ?>
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
          <li class="active">List all data centers</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
<?php if(! isset($dc_data)) :?>
                  No servers found.
<?php else :?>
                <p><b>List All Data Centers</b><hr/></p>
                <p>&nbsp;</p>
                  <table class="table table-striped table-hover table-condensed">
	<thead>
            <tr>
                <th><center>Name</center></th>
                  <th class="hidden-phone"><center>Location</center></th>
                  <th><center>No. of servers</center></th>
          </tr>
	</thead>
	<tbody>
            
                <?php foreach($dc_data as $data) :?>
                    <tr>
                        <td><center><a href="<?php echo site_url(); ?>/servers/listdcs/view/<?=$data->dc_id ?>/"><?=$data->dc_name ?></a></center></td>
                        <td class="hidden-phone"><center><a href="http://maps.google.com/maps?q=<?=$data->dc_location ?>" target="_blank"><?=$data->dc_location ?></a></center></td>
                        <td><center><?php echo ($data->count==0)?'-':"<a href='". site_url() . "/servers/listdcs/showservers/$data->dc_id/'>" . $data->count . '</a>' ; ?></center></td>
                  </tr>
                <?php endforeach; ?>
	</tbody>
        </table>
<?php endif; ?>                
        <p>&nbsp;</p><p>&nbsp;</p>
        <?php if (TRUE == Essentials::checkWhamUserRole("add_dc")) :?>
        <p align="right">
            <a class="btn btn-success" href="<?php echo site_url(); ?>/servers/adddc/"><span class="btn-label"><i class="icon-file icon-white"></i> Add a new data center</span></a>
        </p>
        <?php endif; ?>
        <p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>