<?php Template::printHeader_(); ?>
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
  </head>
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_("event_log"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/settings/">Settings</a>
            <span class="divider">/</span>
          </li>
          <li class="active">WHAM! event log</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <p><b>WHAM! Event Log</b><hr/></p>
                  <p>&nbsp;</p>
              <ul class="nav nav-tabs">
                  <li class="active"><a href="#login" data-toggle="tab">Login</a></li>
                  <li><a href="#actions" data-toggle="tab">Actions</a></li>
                  <li><a href="#sync" data-toggle="tab">Sync</a></li>
              </ul>    
              <div class="tab-content">
                <div class="tab-pane active" id="login">
                    <p>&nbsp;</p>
                    <?php if (!isset($auth_result)) :?>
                    No results to display..!
                    <?php else :?>
                    <table class="table table-hover table-striped table-condensed display example">
                        <thead>
                            <tr><th>Date</th><th>User</th><th class="hidden-phone">IP</th><th class="hidden-phone">Message</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach($auth_result as $row) :?>
                            <tr>
                                <td><?= Essentials::toLocalTime($row->log_time) ?></td>
                                <td><?= $row->log_user ?></td>
                                <td class="hidden-phone"><?= $row->log_ip ?></td>
                                <td class="hidden-phone"><?= $row->log_msg ?></td>
                                <td><?php if ($row->log_status == 1) :?>
                                    <span class="label">Success</span><?php else: ?>
                                    <span class="label label-important">Failed</span><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="muted">&nbsp;<br/>&nbsp;<br/><span class="label label-success">Info</span> Data sorted according to date in descending order (most recent entries appear first).</p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane" id="actions">
                    <p>&nbsp;</p>
                    <?php if (!isset($action_result)) :?>
                    No results to display..!
                    <?php else :?>
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
                                    <?php if ($row->log_server != NULL && $row->s_name != NULL) :?>
                                    <span class="label label-warning">Server</span> <a href="<?= site_url() ?>/servers/viewserver/info/<?= $row->log_server?>/"><?= $row->s_name ?></a>
                                    <?php elseif ($row->log_server != NULL && $row->s_name == NULL) :?>
                                    <span class="label label-warning">Server</span> <b>ID: <?= $row->log_server?> ** DELETED **</b>
                                    <?php endif; ?>
                                </td>
                                <td><?php if ($row->log_status == 1) :?>
                                    <span class="label">Success</span><?php else: ?>
                                    <span class="label label-important">Failed</span><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="muted">&nbsp;<br/>&nbsp;<br/><span class="label label-success">Info</span> Data sorted according to date in descending order (most recent entries appear first).</p>
                    <?php endif; ?>
                </div>
                <div class="tab-pane" id="sync">
                    <p>&nbsp;</p>
                    <?php if (!isset($sync_result)) :?>
                    No results to display..!
                    <?php else :?>
                    <table class="table table-hover table-striped table-condensed display example">
                        <thead>
                            <tr><th class="hidden-phone">Date</th><th>User</th><th class="hidden-phone">IP</th><th>Message</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach($sync_result as $row) :?>
                            <tr>
                                <td class="hidden-phone"><?= Essentials::toLocalTime($row->log_time) ?></td>
                                <td><?= $row->log_user ?></td>
                                <td class="hidden-phone"><?= $row->log_ip ?></td>
                                <td>
                                    <?= $row->log_msg ?>
                                    <?php if ($row->log_server != NULL && $row->s_name != NULL) :?>
                                    <span class="label label-warning">Server</span> <a href="<?= site_url() ?>/servers/viewserver/info/<?= $row->log_server?>/"><?= $row->s_name ?></a>
                                    <?php elseif ($row->log_server != NULL && $row->s_name == NULL) :?>
                                    <span class="label label-warning">Server</span> <b>ID: <?= $row->log_server?> ** DELETED **</b>
                                    <?php endif; ?>
                                </td>
                                <td><?php if ($row->log_status == 1) :?>
                                    <span class="label">Success</span><?php else: ?>
                                    <span class="label label-important">Failed</span><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="muted">&nbsp;<br/>&nbsp;<br/><span class="label label-success">Info</span> Data sorted according to date in descending order (most recent entries appear first).</p>
                    <?php endif; ?>
                </div>
              </div>
                  <p>&nbsp;</p>
                  <p class="text-info"><hr/><span class="label label-info">Note</span> WHAM! records information regarding all actions that resulted in 
                   any sort of update in its db, like changes to data centers, servers, or even changes made 
               to any accounts inside them.</p>
               <p><span class="label label-success">Info</span> You can configure the related settings under "Logging" section of <a href="<?= site_url() ?>/settings/wham_settings/">WHAM! Settings</a>.</p>
                  <p>&nbsp;</p>
            </div>       
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
