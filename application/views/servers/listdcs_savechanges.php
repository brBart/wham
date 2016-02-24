<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reset').bind('click', function(e) {
                e.preventDefault();
                $('#dc_name').val("");
                $('#dc_websiteurl').val("http://");
                $('#dc_supporturl').val("http://");
                $('#dc_email').val("");
                $('#dc_location').val("");
                <?php if (TRUE == Essentials::checkWhamUserRole("view_dc_note")) :?>
                $('#dc_notes').val("");
                <?php endif; ?>
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
            <a href="<?php echo site_url(); ?>/servers/listdcs/">List all data centers</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/servers/listdcs/view/<?=$dc_data->dc_id; ?>/"><?=$dc_data->dc_name; ?></a>
            <span class="divider">/</span>
          </li>
          <li class="active">Edit details</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              <p><b>Edit details</b><hr/></p>
              <form method='post' action="<?php echo site_url(); ?>/servers/listdcs/savechanges/">
                  <input type="hidden" name="dc_id" value="<?=$dc_data->dc_id?>">
                  <input type="hidden" name="dc_name_old" value="<?=$dc_data->dc_name?>">
                  <table border="0" width="100%">
                      <tr>
                          <td valign="top">DC Name<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-medium" id="dc_name" name="dc_name" value="<?=$dc_data->dc_name ?>" placeholder="Data center name"></td>
                      </tr>
                      <tr>
                          <td valign="top">Website URL<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-large" id="dc_websiteurl" name="dc_websiteurl" value="<?=$dc_data->dc_websiteurl ?>" placeholder="DC's website URL"></td>
                      </tr>
                      <tr>
                          <td valign="top">Support URL<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-large" id="dc_supporturl" name="dc_supporturl" value="<?=$dc_data->dc_supporturl ?>" placeholder="Support URL"></td>
                      </tr>
                      <tr>
                          <td valign="top">Support Email<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-large" id="dc_email" name="dc_email" value="<?=$dc_data->dc_email ?>" placeholder="Support Email address"></td>
                      </tr>
                      <tr>
                          <td valign="top">DC Location<sup><font size="3" color="red">*</font></sup></td>
                          <td valign="top"><input type="text" class="input-large" id="dc_location" name="dc_location" value="<?=$dc_data->dc_location ?>" placeholder="Geographical location of DC"></td>
                      </tr>
                      <?php if (TRUE == Essentials::checkWhamUserRole("view_dc_note")) :?>
                      <tr>
                          <td valign="top">Notes</td>
                          <td valign="top"><textarea placeholder="Important notes.." id="dc_notes" name="dc_notes" class="input-large"><?=$dc_data->dc_notes ?></textarea></td>
                      </tr>
                      <?php endif; ?>
                      <tr>
                          <td valign="top">&nbsp;</td>
                          <td valign="top">
                              <button class="btn btn-success" href="#">
                                <span class="btn-label"><i class="icon-file icon-white"></i> Save Changes</span>
                              </button>
                              <button class="btn" href="#" id="reset">
                                <span class="btn-label"><i class="icon-refresh"></i> Reset fields</span>
                              </button>
                          </td>
                      </tr>
                  </table>
                  </form>
                  <p>&nbsp;<br/><sup><font size="3" color="red">*</font></sup> indicates a necessary field</p>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>
