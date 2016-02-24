<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#create_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($.trim($("#ip").val()) == "" || $.trim($("#netmask").val()) == "") {
                    alert("IP address and Netmask should be valid in order to continue..");
                } else {
                    $("#new_ip_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_addip/" . $s_id . "/" ?>",
                        data: $("#new_ip_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_addip/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>Add New IP Address</b></p><hr/>
     <div id="new_ip_div"><form id="new_ip_form">           
     <table class="table table-striped" width="100%">
        <tr>
            <td valign="top">IP Address</td>
            <td valign="top"><input type="text" maxlength="15" class="input-medium" id="ip" name="ip" value="" placeholder="aaa.bbb.ccc.ddd"></td>
        </tr>
        <tr>
            <td valign="top">Subnet Mask</td>
            <td valign="top"><input type="text" class="input-medium" id="netmask" name="netmask" value="" placeholder="e.g.: 255.255.255.0"></td>
        </tr>
            <td colspan="2">
                  <p>&nbsp;</p><button class="btn btn-success" href="#" id="create_sbmt">
                <span class="btn-label"><i class="icon-plus-sign icon-white"></i> Add new IP</span>
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