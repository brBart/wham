<?php Template::printHeader_("ui"); ?>
<style type="text/css">
    #tabs {
	font-size: 12px;
        margin-left: 20px;
    }
</style>
   <script type="text/javascript">
        $(document).ready(function() {
            $("#tabs").tabs();
            $("#accordion").accordion();
            
            $("#new_role_sbmt").bind("click", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/user_roles/viewroles/" ?>",
                    data: $("#new_role_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#new_role_msg").html(text);
                        if (text == "New role created successfully.")
                            setTimeout('window.location.replace("<?= site_url() ?>/settings/user_roles/viewroles/")', 2000);
                    }
                });
            });
        });
    </script>
  </head>
  
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_("u_and_r"); ?>
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
          <li class="active">Users and roles</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <ul class="nav nav-tabs">
                    <li>
                        <a href="<?= site_url() ?>/settings/user_roles/">Users</a> 
                    </li>
                    <li class="active">
                        <a href="#"><b>Roles</b></a>
                    </li>
                </ul>
              <div id="tabs">
                  <ul>
                      <li><a href="#current">Current Roles</a></li>
                      <li><a href="#addnew">Add New Role</a></li>
                  </ul>
                  <div id="current">
                    
                    <?php if (isset($roles_list)) :?>
                      <div id="accordion">
                    <?php foreach ($roles_list as $row) :?>
                    <h3><?= $row->role_name?></h3>
                    <div>&nbsp;<br/>
                        <?php 
                        $roles = json_decode($row->role_priv);
                        ?>
                        <b>&nbsp;<u>Data Centers</u></b><br/>
                    <div style="padding-left: 20px">
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->add_dc == 1)? "tick":"cross" ?>.png"> &nbsp; Add a new data center<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->delete_dc == 1)? "tick":"cross" ?>.png"> &nbsp; Delete a data center<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->edit_dc == 1)? "tick":"cross" ?>.png"> &nbsp; Edit data center info<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->view_dc_note == 1)? "tick":"cross" ?>.png"> &nbsp; Can view data center note (might contain sensitive info)<br/>
                    </div>
                    
                    <b>&nbsp;<u>Servers</u></b><br/>
                    <div style="padding-left: 20px">
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->add_server == 1)? "tick":"cross" ?>.png"> &nbsp; Add a new server<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->delete_server == 1)? "tick":"cross" ?>.png"> &nbsp; Delete a server<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->edit_server == 1)? "tick":"cross" ?>.png"> &nbsp; Edit server info<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->view_server_note == 1)? "tick":"cross" ?>.png"> &nbsp; Can view server note (might contain sensitive info)<br/>
                    </div>
                    
                    <b>&nbsp;<u>Accounts</u></b><br/>
                    <div style="padding-left: 20px">
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->add_account == 1)? "tick":"cross" ?>.png"> &nbsp; Add a new account<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->delete_account == 1)? "tick":"cross" ?>.png"> &nbsp; Delete account<br/>
                    <img src="<?= base_url() ?>includes/images/<?= ($roles->modify_account == 1)? "tick":"cross" ?>.png"> &nbsp; Modify account<br/>&nbsp;<br/>
                    </div>
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <?php else :?>
                    <center>No roles</center>
                    
                    <?php endif; ?>
                
                </div>
                <div id="addnew">
                <p>&nbsp;</p>
                <form id="new_role_frm">
                    <br/>Role Name<br/><input type="text" name="new_role_name" placeholder="Role name"><br/>&nbsp;</br>
                    <b><u>Data Centers</u></b><br/>
                    <div style="padding-left: 20px">
                    <input type="checkbox" name="add_dc"> &nbsp; Add a new data center<br/>
                    <input type="checkbox" name="delete_dc"> &nbsp; Delete a data center<br/>
                    <input type="checkbox" name="edit_dc"> &nbsp; Edit data center info<br/>
                    <input type="checkbox" name="view_dc_note"> &nbsp; Can view data center note (might contain sensitive info)<br/>
                    </div>
                    
                    <b><u>Servers</u></b><br/>
                    <div style="padding-left: 20px">
                    <input type="checkbox" name="add_server"> &nbsp; Add a new server<br/>
                    <input type="checkbox" name="delete_server"> &nbsp; Delete a server<br/>
                    <input type="checkbox" name="edit_server"> &nbsp; Edit server info<br/>
                    <input type="checkbox" name="view_server_note"> &nbsp; Can view server note (might contain sensitive info)<br/>
                    </div>
                    
                    <b><u>Accounts</u></b><br/>
                    <div style="padding-left: 20px">
                    <input type="checkbox" name="add_account"> &nbsp; Add a new account<br/>
                    <input type="checkbox" name="delete_account"> &nbsp; Delete account<br/>
                    <input type="checkbox" name="modify_account"> &nbsp; Modify account<br/>&nbsp;<br/>
                    </div>
                    <p id="new_role_msg" style="color: red">&nbsp;</p>&nbsp;<br/>
                    <button class="btn btn-success" href="#" id="new_role_sbmt">
                <span class="btn-label"><i class="icon-file icon-white"></i> Create new role</span>
              </button>
                    <p id="new_role_msg"></p>
                </form>
              </div>
              </div><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>