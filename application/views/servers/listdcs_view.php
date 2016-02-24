<?php Template::printHeader_(); ?>
<?php if ($no_of_servers > 0) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#delete_btn').bind('click', function(e) {
                e.preventDefault();
                $("#delete_btn_msg").show();
            });
        });
    </script>
<?php else :?>
    <?php if (TRUE == Essentials::checkWhamUserRole("delete_dc")) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#delete_btn').bind('click', function(e) {
                e.preventDefault();
                var response=confirm("Are you sure you wish to delete this datacenter '<?=$dc_data->dc_name ?>'?\n\nThis action cannot be undone.");
                if (response==true)
                    window.location='<?=site_url() ?>/servers/listdcs/delete/<?=$dc_data->dc_id ?>/';
            });
        });
    </script>
    <?php endif; ?>
<?php endif; ?>
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
                    <li class="active">
                        <a href="#"><b>Data center details</b></a> 
                    </li>
                    <li>
                        <a href="<?= site_url() . "/servers/listdcs/showservers/" . $dc_data->dc_id . "/" ?>">Servers (<?=$no_of_servers ?>)</a> 
                    </li>
                </ul>
                <p>&nbsp;</p>
                <table class="table table-striped">
                      <tr>
                          <td valign="top">DC Name</td>
                          <td valign="top"><font color="red"><?=$dc_data->dc_name ?></font></td>
                      </tr>
                      <tr>
                          <td valign="top">Website URL</td>
                          <td valign="top"><a href="<?=$dc_data->dc_websiteurl ?>" target="_blank"><?=$dc_data->dc_websiteurl ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">Support URL</td>
                          <td valign="top"><a href="<?=$dc_data->dc_supporturl ?>" target="_blank"><?=$dc_data->dc_supporturl ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">Support Email</td>
                          <td valign="top"><a href="mailto:<?=$dc_data->dc_email ?>"><?=$dc_data->dc_email ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">DC Location</td>
                          <td valign="top"><a href="http://maps.google.com/maps?q=<?=$dc_data->dc_location ?>"><?=$dc_data->dc_location ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">Created On</td>
                          <td valign="top"><?=Essentials::toLocalTime($dc_data->dc_dateofc) ?></td>
                      </tr>
                      <tr>
                          <td valign="top">Last Modified</td>
                          <td valign="top"><?=Essentials::toLocalTime($dc_data->dc_dateofm) ?></td>
                      </tr>
                      <?php if (TRUE == Essentials::checkWhamUserRole("view_dc_note")) :?><tr>
                          <td valign="top">Notes</td>
                          <td valign="top"><?=nl2br($dc_data->dc_notes) ?></td>
                      </tr><?php endif; ?>
                  </table>
                <p>&nbsp;</p>
             <?php if ($no_of_servers > 0 && TRUE == Essentials::checkWhamUserRole("delete_dc")) :?>   
                <div class="alert" id="delete_btn_msg" style="display:none;">
                
                <span>
                <b><font color="green">ERROR:</font></b> 
                Cannot delete this data center. You have <?=$no_of_servers ?> server<?=($no_of_servers == 1)?'':'s' ?> binded to it. 
                </span>
                </div>
             <?php endif; ?>
                <p>&nbsp;</p>
                <p align="right">
                    <?php if (TRUE == Essentials::checkWhamUserRole("edit_dc")) :?><a class="btn btn-success" href="<?=site_url() . "/servers/listdcs/edit/" . $dc_data->dc_id . "/" ?>">
                    <span class="btn-label"><i class="icon-pencil icon-white"></i> Edit details</span>
                    </a><?php endif; ?>
                    <?php if (TRUE == Essentials::checkWhamUserRole("delete_dc")) :?><a class="btn btn-inverse" href="#" id="delete_btn">
                    <span class="btn-label"><i class="icon-trash icon-white"></i> Delete data center</span>
                    </a><?php endif; ?>
                </p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>