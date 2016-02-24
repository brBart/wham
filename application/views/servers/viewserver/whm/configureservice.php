<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#conf_serv_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#service").val() == "0") {
                    alert("Please select a valid service from the list.");
                } else {
                    $("#conf_serv_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_configureservice/" . $s_id . "/" ?>",
                        data: $("#configure_service_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_configureservice/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
                }
            });
        });
    </script>
  </head>
<?php if (isset($service_list)) $no_of_services = count($service_list); ?>  
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
              
                <p><b>Configure Service</b></p><hr/>
     <?php if (isset($service_list)) :?><div id="conf_serv_div">
                <p>This option enables you to configure the services listed in WHM<br/>&nbsp;</p>
                <form id="configure_service_form">
                    <p>Service Name<br/>
                    <select id="service" name="service">
                        <option value="0">-- select --</option>
                        <?php for($i=0; $i<$no_of_services; $i++) :?>
                        <option value="<?= $service_list[$i]["name"] ?>"><?= $service_list[$i]["name"] ?> - <?= $service_list[$i]["display_name"] ?></option>
                        <?php endfor; ?>
                    </select>
                    </p>
                    <p>&nbsp;<br/>Enabled?<br/>
                    <input type="radio" name="enabled" value="1" checked> Yes<br/>
                    <input type="radio" name="enabled" value="0"> No
                    </p>
                    <p>&nbsp;<br/>Monitored?<br/>
                    <input type="radio" name="monitored" value="1" checked> Yes<br/>
                    <input type="radio" name="monitored" value="0"> No
                    </p>
                    <p>&nbsp;<br/><button id="conf_serv_sbmt" class="btn btn-success" href="#">
                <span class="btn-label"><i class="icon-check icon-white"></i> Save Changes</span>
              </button></p>
                </form></div>
     <p>&nbsp;</p>
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <?php else :?>
     &nbsp; &nbsp; API/Network error
     <?php endif; ?>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>