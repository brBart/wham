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
      <?php Template::printSideBar_("list_all_servers"); ?>
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
          <li class="active">List all servers</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
<?php if(! isset($server_list)) :?>
                  No servers found.
<?php else :?>
                <p><b>List All Servers</b><hr/></p>
                <p>&nbsp;</p>
                  <table class="display table table-striped table-hover table-condensed"<?= (count($server_list) > 10)?' id="example"':"" ?>>
	<thead>
            <tr>
                <th>Name</th>
                  <th>Hostname</th>
                  <th class="hidden-phone">Data center</th>
                  <th class="hidden-phone">IP Address</th>
                  <th class="hidden-phone">Control Panel</th>
                  <th class="hidden-phone hidden-tablet">Rack</th></tr>
	</thead>
	<tbody>
                <?php foreach($server_list as $data) :?>
                    <tr>
                        <td><a href="<?= site_url()?>/servers/viewserver/info/<?=$data->s_id ?>/"><?=$data->s_name ?></a> 
                            </td>
                        <td><a href="http://<?=$data->s_hostname ?>" target="_blank"><?=$data->s_hostname ?></a></td>
                        <td class="hidden-phone"><a href="<?= site_url()?>/servers/listdcs/view/<?=$data->dc_id ?>/"><?=$data->dc_name . ", " . $data->dc_location ?></a></td>
                        <td class="hidden-phone"><a href="http://<?=$data->s_ip ?>" target="_blank"><?=$data->s_ip ?></a></td>
                        <td class="hidden-phone">
                <?php if ($data->p_name == "CPanel/WHM") :?>        
                        <a href="<?= site_url() ?>/servers/viewserver/redirect/whm/<?=$data->s_id ?>/" target="_blank"><?=$data->p_name ?></a>
                <?php endif; ?>        
                        </td>
                        <td class="hidden-phone hidden-tablet"><?=$data->s_rack ?></td>
                    </tr>
                <?php endforeach; ?>
	</tbody>
        </table><p>&nbsp;</p><p>&nbsp;</p>
                    
<?php endif; ?><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
