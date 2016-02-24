<?php Template::printHeader_(); ?>
<?php if (Mysession::getVar("username") == "admin" && isset($action_result)) :?>
    <style type="text/css" title="currentStyle">
            @import "<?= base_url()?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url()?>includes/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
               $('.example').dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": false,
                    "bInfo": true,
                    "bAutoWidth": true
               });
               $("input[aria-controls]").css({"width": "120px", "height" : "12px"});
               $("select[aria-controls]").css({"width": "70px"});
            });
    </script>
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
            <a href="<?php echo site_url(); ?>/servers/listservers/">List all servers</a>
            <span class="divider">/</span>
          </li>
          <li class="active"><?=$server->s_hostname ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
              
              
            <div>
              
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#"><b>Server details</b></a> 
                    </li>
                    <?php if ($server->s_isactive == "Y") :?><li>
                        <a href="<?= site_url() . "/servers/viewserver/whm/" . $server->s_id . "/" ?>">Web Host Manager</a>
                    </li><?php endif; ?>
                </ul>
                
<?php if (Mysession::getVar("username") == "admin" && isset($action_result)) :?>
              <ul class="nav nav-pills">
                  <li class="pull-right"><a href="#logs" data-toggle="tab">Event Log</a></li>
                  <li class="active pull-right"><a href="#info" data-toggle="tab">Info</a></li>
              </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="info">
<?php endif; ?>                  
                
                <p>&nbsp;</p>
                <table class="table table-striped table-hover">
                      <tr>
                          <td valign="top">Server Name</td>
                          <td valign="top"><font color="red"><?=$server->s_name ?></font></td>
                      </tr>
                      <tr>
                          <td valign="top">Hostname</td>
                          <td valign="top"><a href="http://<?=$server->s_hostname ?>" target="_blank"><?=$server->s_hostname ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">IP Address</td>
                          <td valign="top"><a href="http://<?=$server->s_ip ?>" target="_blank"><?=$server->s_ip ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">Control Panel</td>
                          <td valign="top">
                            <a href="<?= site_url()?>/servers/viewserver/redirect/whm/<?=$server->s_id ?>/" target="_blank"><?=$server->p_name ?></a>
                          </td>
                      </tr>
                      <tr>
                          <td valign="top">Rack Location</td>
                          <td valign="top"><?=$server->s_rack ?></td>
                      </tr>
                      <tr>
                          <td valign="top">Data Center</td>
                          <td valign="top"><a href="<?= site_url() ?>/servers/listdcs/view/<?=$server->dc_id ?>/"><?=$server->dc_name ?>, <?=$server->dc_location ?></a></td>
                      </tr>
                      <tr>
                          <td valign="top">Created On</td>
                          <td valign="top"><?=Essentials::toLocalTime($server->s_dateofc) ?></td>
                      </tr>
                      <tr>
                          <td valign="top">Last Modified</td>
                          <td valign="top"><?=Essentials::toLocalTime($server->s_dateofm) ?></td>
                      </tr>
                      <?php if(TRUE == Essentials::checkWhamUserRole("view_dc_note")) :?>
                      <tr>
                          <td valign="top">Notes</td>
                          <td valign="top"><?=nl2br(Essentials::decrypt($server->s_notes)) ?></td>
                      </tr>
                      <?php endif; ?>
                  </table>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p align="right">
                    <?php if(TRUE == Essentials::checkWhamUserRole("edit_server")) :?>
                    <a class="btn hidden-phone btn-success" href="<?=site_url() . "/servers/viewserver/whm_setremotekey/" . $server->s_id . "/" ?>">
                    <span class="btn-label"><i class="icon-edit icon-white"></i> Configure WHM remote key</span>
                    </a>
                    <a class="btn btn-success" href="<?=site_url() . "/servers/viewserver/edit/" . $server->s_id . "/" ?>">
                    <span class="btn-label"><i class="icon-pencil icon-white"></i> Edit details</span>
                    </a>
                    <?php endif; ?>
                    <?php if(TRUE == Essentials::checkWhamUserRole("delete_server")) :?>
                    <a class="btn btn-inverse" href="<?=site_url() ?>/servers/viewserver/delete/<?=$server->s_id ?>/">
                    <span class="btn-label"><i class="icon-trash icon-white"></i> Delete server</span>
                    </a>
                    <?php endif; ?>
                </p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
<?php if (Mysession::getVar("username") == "admin" && isset($action_result)) :?>
            <div class="tab-pane" id="logs"><p>&nbsp;</p>
                <table class="table table-hover table-striped table-condensed display example">
                        <thead>
                            <tr><th class="hidden-phone">Date</th><th>User</th><th class="hidden-phone">IP</th><th>Message</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach($action_result as $row) :?>
                            <tr>
                                <td class="hidden-phone"><?= Essentials::toLocalTime($row->log_time) ?></td>
                                <td><?= $row->log_user ?></td>
                                <td class="hidden-phone"><?= $row->log_ip ?></td>
                                <td>
                                    <?= $row->log_msg ?>
                                </td>
                                <td><?php if ($row->log_status == 1) :?>
                                    <span class="label">Success</span><?php else: ?>
                                    <span class="label label-important">Failed</span><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
          </div>  
<?php endif; ?>            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
