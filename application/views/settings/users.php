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
            
            $("#usr_edit_btn").bind("click", function(e) {
                e.preventDefault();
                if ($("#user_to_modify").val() == 0) {
                    $("#edit_user_details").hide();
                }
                else {
                    u_id = $("#user_to_modify option:selected").val();
                    un = $("#user_to_modify option:selected").attr("username");
                    fn = $("#user_to_modify option:selected").attr("fullname");
                    role = $("#user_to_modify option:selected").attr("role");
                    
                    $("#edit_user_details p:first").html("Modify user <b>" + un + "</b>");
                    $("#edit_user_details input[name=user_fullname]").val(fn);
                    $("#edit_user_details select[name=role_changed]").val(role);
                    $("#edit_user_details input[name=user_id]").val(u_id);
                    $("#edit_user_details").show();
                }
            });
            
            $("#usr_del_btn").bind("click", function(e) {
                e.preventDefault();
                if ($("#user_to_modify").val() != 0) {
                    un = $("#user_to_modify option:selected").attr("username");
                    u_id = $("#user_to_modify option:selected").val();
                    var can_continue = confirm("You are about to delete user '" + un + 
                        "'.\nAre you sure you wish to continue?");
                    
                    if (can_continue == true) {
                        $.ajax({
                            type: "GET",
                            url: "<?= site_url() ?>/settings/user_roles/deleteuser_post/" + u_id + "/",
                            dataType: "text",
                            success: function(text) {
                                alert(text);
                                if (text == "User deleted successfully.")
                                    window.location.replace("<?= site_url() ?>/settings/user_roles/");
                            }
                        });
                    }
                }
            });
            
            $("#edit_change_save").bind("click", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/user_roles/modifyuser_post/" ?>",
                    data: $("#modify_acct_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#edit_change_save_msg").html(text);
                        if (text == "User details updated successfully.")
                            setTimeout('window.location.replace("<?= site_url() ?>/settings/user_roles/")', 2000);
                    }
                });
            });
            
            $("#new_usr_create_sbmt").bind("click", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/settings/user_roles/addnewuser_post/" ?>",
                    data: $("#new_usr_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#new_usr_msg").html(text);
                        if (text == "New user created successfully.")
                            setTimeout('window.location.replace("<?= site_url() ?>/settings/user_roles/")', 2000);
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
                    <li class="active">
                        <a href="#"><b>Users</b></a> 
                    </li>
                    <li>
                        <a href="<?= site_url() ?>/settings/user_roles/viewroles/">Roles</a>
                    </li>
                </ul>
              <div id="tabs">
                  <ul>
                      <li><a href="#current_users">Current Users</a></li>
                      <li><a href="#add_user">Add</a></li>
                      <?php if (isset($user_list)) :?><li><a href="#edit_user">Edit</a></li><?php endif; ?>
                  </ul>
                  <div id="current_users">
                      <?php if (isset($user_list)) :?><p>&nbsp;</p>
                      <table width="100%">
                          <thead style="text-align: center; font-weight: bold; border-bottom-style: inset; border-bottom-width: thin">
                              <td width="25%">Username</td><td width="50%">Full Name</td><td>Role</td>
                          </thead>
                          <?php for ($i=0 ; $i < count($user_list); $i++) :?>
                          <tr>
                              <td style="text-align: center"><?= $user_list[$i]->u_name ?></td>
                              <td style="text-align: center"><?= $user_list[$i]->u_fullname ?></td>
                              <td style="text-align: center"><?= $user_list[$i]->role_name ?></td>
                          </tr>
                          <?php endfor; ?>
                      </table><p>&nbsp;</p><p>&nbsp;</p>
                      <?php else :?>
                      <p>&nbsp;<br/>No user accounts found.</p>
                      <?php endif; ?>
                  </div>
                  <div id="add_user">
                      <p>&nbsp;<br/><b>Enter details regarding the new user</b><hr/></p>
                      <form id="new_usr_frm">
                          <p>Username <font color="red"><sup>*</sup></font><br/>
                          <input type="text" id="new_username" name="new_username" placeholder="New username" maxlength="15"></p>
                          <p>Full Name <font color="red"><sup>*</sup></font><br/>
                          <input type="text" id="new_fullname" name="new_fullname" placeholder="Name of the user" maxlength="25"></p>
                          <p>Password <font color="red"><sup>*</sup></font><br/>
                          <input type="password" id="new_pwd" name="new_pwd" placeholder="Password" maxlength="15"></p>
                          <p>Confirm Password <font color="red"><sup>*</sup></font><br/>
                          <input type="password" id="new_pwd_cnf" name="new_pwd_cnf" placeholder="Confirm password" maxlength="15"></p>
                          <p>Role <font color="red"><sup>*</sup></font><br/>
                          <select name="role_selected" id="role_selected">
                              <option value="0">-- select --</option>
                              <?php if (isset($role_list)) :?>
                              <?php for($i=0; $i < count($role_list); $i++) :?>
                              <option value="<?= $role_list[$i]->role_id ?>"><?= $role_list[$i]->role_name ?></option>
                              <?php endfor; ?>
                              <?php endif;?>
                          </select>
                          </p>
                          <p id="new_usr_msg" style="color:red">&nbsp;</p>&nbsp;<br/>
                            <button class="btn btn-success" href="#" id="new_usr_create_sbmt">
                            <span class="btn-label"><i class="icon-user icon-white"></i> Create New User</span>
                            </button>
                      </form>
                  </div>
                  <?php if (isset($user_list)) :?>
                  <div id="edit_user">
                      <p>&nbsp;</p>
                      <select name="user_to_modify" id="user_to_modify">
                        <option value="0">-- select --</option>
                        <?php for ($i=0 ; $i < count($user_list); $i++) :?>
                        <option value="<?= $user_list[$i]->u_id ?>" username="<?= $user_list[$i]->u_name ?>" 
                                fullname="<?= $user_list[$i]->u_fullname ?>" role="<?= $user_list[$i]->u_roleid ?>"><?= $user_list[$i]->u_name ?></option>
                        <?php endfor; ?>
                      </select>&nbsp; &nbsp;
                      <button class="btn btn-success" href="#" id="usr_edit_btn"><span class="btn-label"><i class="icon-pencil icon-white"></i> edit</span></button>&nbsp; 
                      <button class="btn btn-success" href="#" id="usr_del_btn"><span class="btn-label"><i class="icon-minus icon-white"></i> delete</span></button>
                      <p>&nbsp;</p>
                      <div id="edit_user_details" style="margin-left: 20px; display: none">
                      <p style="color: green"></p><form id="modify_acct_frm">
                      <p>Full Name<br/><input type="text" name="user_fullname" value=""></p>
                      <p>Password (leave blank if you do not wish to change)<br/><input type="password" name="new_pwd" value=""></p>
                      <p>Confirm Password<br/><input type="password" name="new_pwd_cnf" value=""></p>
                      <p>Role<br/><select name="role_changed">
                              <?php if (isset($role_list)) :?>
                              <?php for($i=0; $i < count($role_list); $i++) :?>
                              <option value="<?= $role_list[$i]->role_id ?>"><?= $role_list[$i]->role_name ?></option>
                              <?php endfor; ?>
                              <?php endif;?>
                          </select></p>
                      <input type="hidden" name="user_id" value=""></form>
                      <p id="edit_change_save_msg" style="color:red">&nbsp;</p><button class="btn btn-success" href="#" id="edit_change_save"><span class="btn-label"><i class="icon-edit icon-white"></i> Save Changes</span></button>
                  </div>
                  </div>
                  
                  
                  <?php endif; ?>
              </div>   
                <p>&nbsp;</p>
                <p>&nbsp;</p><p>&nbsp;</p>
              
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>