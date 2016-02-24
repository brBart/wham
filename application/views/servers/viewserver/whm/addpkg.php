<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#create_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($.trim($("#name").val()) == "" || $.trim($("#quota").val()) == "") {
                    alert("Package name and quota should be valid in order to continue..");
                } else {
                    $("#new_package_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_addpkg/" . $s_id . "/" ?>",
                        data: $("#create_package_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_addpkg/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
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
              
                <p><b>Add A Package</b></p><hr/>
     <div id="new_package_div"><form id="create_package_form">           
     <table class="table table-striped" width="100%">
        <tr>
            <td valign="top">Package name<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" maxlength="8" class="input-medium" id="name" name="name" value="" placeholder="Package name"></td>
        </tr>
        <tr>
            <td valign="top">Disk quota<sup><font size="3" color="red">*</font></sup></td>
            <td valign="top"><input type="text" class="input-medium" id="quota" name="quota" value="" placeholder="Disk quota (in MB)"></td>
        </tr>
        <tr>
            <td valign="top">Bandwidth</td>
            <td valign="top"><input type="text" class="input-medium" id="bwlimit" name="bwlimit" value="5000" placeholder="bandwidth (in MB)"></td>
        </tr>
        <tr>
            <td valign="top">Max FTP accounts</td>
            <td valign="top"><input type="text" class="input-medium" id="maxftp" name="maxftp" value="" placeholder="Max FTP accounts"></td>
        </tr>
        <tr>
            <td valign="top">Max SQL databases</td>
            <td valign="top"><input type="text" class="input-medium" id="maxsql" name="maxsql" value="" placeholder="Max SQL databases"></td>
        </tr>
        <tr>
            <td valign="top">Max POP accounts</td>
            <td valign="top"><input type="text" class="input-medium" id="maxpop" name="maxpop" value="" placeholder="Max POP accounts"></td>
        </tr>
        <tr>
            <td valign="top">Max mailing lists</td>
            <td valign="top"><input type="text" class="input-medium" id="maxlists" name="maxlists" value="" placeholder="Max mailing lists"></td>
        </tr>
        <tr>
            <td valign="top">Max sub domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxsub" name="maxsub" value="" placeholder="Max sub domains"></td>
        </tr>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "allow-parkedcreate", $priv_list)) :?>
        <tr>
            <td valign="top">Max parked domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxpark" name="maxpark" value="" placeholder="Max park domains"></td>
        </tr>
        <?php endif; ?>
        <?php if (TRUE === Cpanelwhm::hasPriv($s_id, "allow-addoncreate", $priv_list)) :?>
        <tr>
            <td valign="top">Max addon domains</td>
            <td valign="top"><input type="text" class="input-medium" id="maxaddon" name="maxaddon" value="" placeholder="Max addon domains"></td>
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
                  <p>&nbsp;</p><button class="btn btn-success" href="#" id="create_sbmt">
                <span class="btn-label"><i class="icon-plus-sign icon-white"></i> Create new package</span>
              </button><p>&nbsp;</p></td>
        </tr>
     </table></form>
         <p>&nbsp;</p>
     </div>
     
     <div id="results_div" style="display:block;"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>