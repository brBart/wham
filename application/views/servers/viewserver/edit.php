<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#save_changes_btn').bind('click', function(e) {
                e.preventDefault();
                
                if ($("#s_name").val().length < 3) {
                    alert("Server name should atleast be 3 characters");
                } else if ($("#s_hostname").val().length < 3) {
                    alert("Server hostname should atleast be 3 characters");
                } else if ($("#s_dc").val() == 0) {
                    alert("Please select a valid datacenter from the list");
                } else {
                    $("#server_edit_frm").submit();
                }
            });
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
          <li>
            <a href="<?php echo site_url(); ?>/servers/viewserver/info/<?=$server->s_id; ?>/"><?=$server->s_hostname; ?></a>
            <span class="divider">/</span>
          </li>
          <li class="active">Edit details</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              <p><b>Edit Details</b><hr/></p>
              <form method='post' action="<?php echo site_url(); ?>/servers/viewserver/edit/<?=$server->s_id; ?>/" id="server_edit_frm">
                  
                  <table class="table table-striped">
                      <tr>
                          <td valign="top">Server Name<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-medium" id="s_name" name="s_name" value="<?=$server->s_name ?>" placeholder="Server name"></td>
                      </tr>
                      <tr>
                          <td valign="top">Hostname<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-medium" id="s_hostname" name="s_hostname" value="<?=$server->s_hostname ?>" placeholder="Server hostname"></td>
                      </tr>
                      <tr>
                          <td valign="top">Control Panel</td>
                          <td valign="top"><b><?=$server->p_name ?></b><br/>&nbsp;</td>
                      </tr>
                      <tr>
                          <td valign="top">IP Address<sup></td>
                          <td valign="top"><b><?=$server->s_ip ?></b><br/>&nbsp;</td>
                      </tr>
                      <tr>
                          <td valign="top">Rack Location</td>
                          <td valign="top"><input type="text" class="input-medium" id="s_rack" name="s_rack" value="<?=$server->s_rack ?>" placeholder="Rack location"></td>
                      </tr>
                      <tr>
                          <td valign="top">Datacenter</td>
                          <td valign="top">
                              <select name="s_dc" id="s_dc">
                                  <option value="0">-- select --</option>
                              <?php foreach($dcs as $dc_id=>$dc_name) :?>
                                  <option value="<?= $dc_id ?>"<?= ($dc_id==$server->dc_id)?" selected":"" ?>><?= $dc_name ?></option>
                              <?php endforeach; ?>
                              </select>
                          </td>
                      </tr>
                      <?php if (TRUE == Essentials::checkWhamUserRole("view_server_note")) :?>
                      <tr>
                          <td valign="top">Notes</td>
                          <td valign="top"><textarea rows="10" placeholder="Important notes.." id="s_notes" name="s_notes" class="input-medium"><?= Essentials::decrypt($server->s_notes) ?></textarea></td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                          <td valign="top" colspan="2">
                              <button class="btn btn-success" href="#" id="save_changes_btn">
                                <span class="btn-label"><i class="icon-check icon-white"></i> Save Changes</span>
                              </button>
                          </td>
                      </tr>
                  </table>
                  </form>
                  <p>&nbsp;<br/></p>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>