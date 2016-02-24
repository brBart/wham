<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#modify_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($.trim($("#quota").val()) == "") {
                    alert("Quota should be valid in order to continue..");
                } else {
                    $("#edit_package_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_editpkg/" . $s_id . "/" ?>",
                        data: $("#edit_package_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_editpkg/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
                }
                
            });
            
            $("#select_sbmt").bind("click", function(e) {
                e.preventDefault();
                if ($("#package_to_modify").val() == 0)
                    alert("Please select a package from the list..");
                else
                    $("#select_frm").submit();
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
          <li class="active"><?=$hostname ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              
                <ul class="nav nav-tabs">
                    <li>
                        <a href="<?= site_url() . "/servers/viewserver/info/" . $s_id . "/" ?>">Server details</a> 
                    </li>
                    <li class="active">
                        <a href="<?= site_url() . "/servers/viewserver/whm/" . $s_id . "/" ?>"><b>Web Host Manager</b></a>
                    </li>
                </ul>
              
                <p><b>Edit A Package</b></p><hr/>
     <div id="edit_package_div">
     <?php if (isset($selected_package_to_modify)) :?>     
     <form id="edit_package_form">           
     <table class="table table-striped">
        <tr>
            <td valign="top">Package name</td>
            <td valign="top"><?= $selected_package_to_modify->name ?><br/>&nbsp;<input type="hidden" id="name" name="name" value="<?= $selected_package_to_modify->name ?>"></td>
        </tr>
        <tr>
            <td valign="top">Disk quota<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" class="input-medium" id="quota" name="quota" value="<?= $selected_package_to_modify->QUOTA ?>" placeholder="Disk quota (in MB)"></td>
        </tr>
        <tr>
            <td valign="top">Bandwidth</td>
            <td valign="top"><input type="text" class="input-medium" id="bwlimit" name="bwlimit" value="<?= $selected_package_to_modify->BWLIMIT ?>" placeholder="bandwidth (in MB)"></td>
        </tr>
        <tr>
            <td valign="top">Max FTP accounts</td>
            <td valign="top"><input type="text" class="input-medium" id="maxftp" name="maxftp" value="<?= $selected_package_to_modify->MAXFTP ?>" placeholder="Max FTP accounts"></td>
        </tr>
        <tr>
            <td valign="top">Max SQL databases</td>
            <td valign="top"><input type="text" class="input-medium" id="maxsql" name="maxsql" value="<?= $selected_package_to_modify->MAXSQL ?>" placeholder="Max SQL databases"></td>
        </tr>
        <tr>
            <td valign="top">Max POP accounts</td>
            <td valign="top"><input type="text" class="input-medium" id="maxpop" name="maxpop" value="<?= $selected_package_to_modify->MAXPOP ?>" placeholder="Max POP accounts"></td>
        </tr>
        <tr>
            <td valign="top">Max mailing lists</td>
            <td valign="top"><input type="text" class="input-medium" id="maxlists" name="maxlists" value="<?= $selected_package_to_modify->MAXLST ?>" placeholder="Max mailing lists"></td>
        </tr>
        <tr>
            <td valign="top">Max sub domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxsub" name="maxsub" value="<?= $selected_package_to_modify->MAXSUB ?>" placeholder="Max sub domains"></td>
        </tr>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "allow-parkedcreate", $priv_list)) :?>
        <tr>
            <td valign="top">Max parked domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxpark" name="maxpark" value="<?= $selected_package_to_modify->MAXPARK ?>" placeholder="Max park domains"></td>
        </tr>
        <?php endif; ?>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "allow-addoncreate", $priv_list)) :?>
        <tr>
            <td valign="top">Max addon domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxaddon" name="maxaddon" value="<?= $selected_package_to_modify->MAXADDON ?>" placeholder="Max addon domains"></td>
        </tr>
        <?php endif; ?>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "add-pkg-ip", $priv_list)) :?>
        <tr>
            <td valign="top">Has dedicated IP</td>
            <td valign="top">
                <input type="radio" name="ip" value="1" />  Yes<br/>
                <input type="radio" name="ip" value="0" checked />  No<br/>&nbsp;
            </td>
        </tr>
        <?php endif; ?>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "add-pkg-shell", $priv_list)) :?>
        <tr>
            <td valign="top">Has shell access</td>
            <td valign="top">
                <input type="radio" name="hasshell" value="1" />  Yes<br/>
                <input type="radio" name="hasshell" value="0" checked />  No<br/>&nbsp;
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td valign="top">Has CGI access</td>
            <td valign="top">
                <input type="radio" name="cgi" value="1" checked />  Yes<br/>
                <input type="radio" name="cgi" value="0" />  No<br/>&nbsp;
            </td>
        </tr>
        <tr>
            <td valign="top">Has Frontpage</td>
            <td valign="top">
                <input type="radio" name="frontpage" value="1" />  Yes<br/>
                <input type="radio" name="frontpage" value="0" checked/>  No<br/>&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="2"><p><sup><font size="3" color="red">*</font></sup> indicates a necessary field</p>
                  <p>&nbsp;</p><button class="btn btn-success" href="#" id="modify_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Modify package</span>
              </button><p>&nbsp;</p></td>
        </tr>
     </table></form>
     <?php else :?>
         Select the package you wish to modify<br/>
     <form method="post" action="<?= site_url() ?>/servers/viewserver/whm_editpkg/<?= $s_id ?>/" id="select_frm">
         <select name="package_to_modify" id="package_to_modify">
            <option value="0">-- select --</option>
        <?php if(isset($package_list)) :?>    
        <?php foreach ($package_list as $pkg) :?>
            <option value="<?=$pkg->name ?>"<?= (isset($sel_pkg) && $sel_pkg == $pkg->name)?" selected":"" ?>><?=$pkg->name ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
        </select><br/>&nbsp;<br/>
        <button class="btn btn-success" href="#" id="select_sbmt">
        <span class="btn-label">Next <i class="icon-hand-right icon-white"></i></span>
        </button>
     </form>
     <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>