<?php Template::printHeader_(); ?>
    <style type="text/css">
        .col1{
        width: 26%;
        }
        .col2 {
        width: 70%;
        font-weight: normal;
        }
    </style>
  </head>
  
  <body>
      <?php Template::printTopMenu_('servers'); ?>
      <?php Template::printSideBar_("add_new_server"); ?>
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
          <li class="active">Add a new server</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
              <?php if (isset($can_add_new) && $can_add_new === FALSE) :?>
            <div class="alert alert-success"><span class="label label-warning">WARNING</span> All active slots already full. If you add a new server now, an old server will be de-activated automatically.</div>
            <?php endif; ?>
            <div>
                <?php if (isset($dcs) && count($dcs) > 0) :?>
              <p><b>New Server Info</b><hr/></p>
              <form method='post' action="<?php echo site_url() . '/servers/addserver/'?>">
                  <table border="0" width="100%">
                      <tr>
                          <td class="col1" valign="top">Server Name<sup><font size="3" color="red">*</font></sup></td>
                          <td class="col2" valign="top"><input type="text" class="input-medium" id="s_name" name="s_name" value="<?php echo set_value('s_name'); ?>" placeholder="Server name"><font color="red"><?php echo form_error('s_name'); ?></font></td>
                      </tr>
                      <tr>
                          <td class="col1" valign="top">Hostname (FQDN)<sup><font size="3" color="red">*</font></sup></td>
                          <td class="col2" valign="top"><input type="text" class="input-large" id="s_hostname" name="s_hostname" value="<?php echo set_value('s_hostname'); ?>" placeholder="Server's Hostname (FQDN)"><font color="red"><?php echo form_error('s_hostname'); ?></font></td>
                      </tr>
                      <tr>
                          <td class="col1" valign="top">Main IP Address<sup><font size="3" color="red">*</font></sup></td>
                          <td class="col2" valign="top"><input type="text" class="input-large" id="s_ip" name="s_ip" value="<?php echo set_value('s_ip'); ?>" placeholder="Main IP Address"><font color="red"><?php echo form_error('s_ip'); ?></font></td>
                      </tr>
                      <tr>
                          <td class="col1" valign="top">Control Panel<sup><font size="3" color="red">*</font></sup></td>
                          <td class="col2" valign="top"><select name="s_cp" id="s_cp" class="input-large">
                                  <option value="0">- select -</option>
                              <?php foreach ($panels as $panel_id => $panel_name) :?>    
                                  <option value="<?= $panel_id?>"><?= $panel_name ?></option>
                              <?php endforeach; ?>    
                                  </select><font color="red"><?php echo form_error('s_cp'); ?></font>
                          </td>
                      </tr>
                      <tr>
                          <td class="col1" valign="top">Data Center<sup><font size="3" color="red">*</font></sup></td>
                          <td class="col2" valign="top"><select name="s_dc" id="s_dc" class="input-large">
                                  <option value="0">- select -</option>
                              <?php foreach ($dcs as $dc_id => $dc_name) :?>    
                                  <option value="<?= $dc_id?>"<?= (isset($add_to_dc) && $add_to_dc == $dc_id)?" selected":"" ?>><?= $dc_name ?></option>
                              <?php endforeach; ?>    
                                  </select><font color="red"><?php echo form_error('s_dc'); ?></font>
                          </td>
                      </tr>
                      <tr>
                          <td class="col1" valign="top">Rack Location</td>
                          <td class="col2" valign="top"><input type="text" class="input-large" id="s_rack" name="s_rack" value="<?php echo set_value('s_rack'); ?>" placeholder="Server rack location"><font color="red"><?php echo form_error('s_rack'); ?></font></td>
                      </tr>
                      <?php if (TRUE == Essentials::checkWhamUserRole("view_server_note")) :?>
                      <tr>
                          <td class="col1" valign="top">Notes</td>
                          <td class="col2" valign="top"><textarea placeholder="Important notes.." id="s_notes" name="s_notes" class="input-large"><?php echo set_value('s_notes'); ?></textarea></td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                          <td class="col1" valign="top">&nbsp;</td>
                          <td class="col2" valign="top">
                              <button class="btn btn-success" href="#">
                                <span class="btn-label"><i class="icon-file icon-white"></i> Add new server</span>
                              </button>
                              <button class="btn" href="#" id="reset">
                                <span class="btn-label"><i class="icon-refresh"></i> Reset fields</span>
                              </button>
                          </td>
                      </tr>
                  </table>
                  </form>
                  <p>&nbsp;<br/><sup><font size="3" color="red">*</font></sup> indicates a necessary field</p>
            <?php else :?>
                  No data centers found. Please add one first in order to add servers
            <?php endif; ?>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>  
                  
              
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>