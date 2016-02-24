<?php Template::printHeader_(); ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $("#delip_sbmt").bind("click", function(e) {
            e.preventDefault();
            if ($("#ip").val() == "0") {
                alert("Invalid ip selected.");
            }
            else {
                $("#ethernetdev").val($("#ip option:selected").attr("dev"));
                $("#del_ip_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?= site_url() ?>/servers/viewserver/whm_delip/<?= $s_id ?>/",
                    data: $("#del_ip_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?= site_url() ?>/servers/viewserver/whm_delip/<?= $s_id ?>/'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
            }

        });
        });
    </script>
  </head>
<?php if (isset($ip_list)) $no_of_ips = count($ip_list); ?>  
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
              
                <p><b>Remove An IP Address</b></p><hr/>
     <?php if (isset($ip_list)) :?><div id="del_ip_div"> 
    <div class="alert">
    <a class="close" data-dismiss="alert">×</a>
    <span>    
        <h4><font color="red">WARNING!</font></h4>
        Please use this option with proper care. It is not possible to undo your actions.</span>
    </div>    
     <p>
         Please select the IP address you wish to remove, from the below list:
     </p><form id="del_ip_form">
     <select name="ip" id="ip">
        <option value="0">-- select --</option>
     <?php for($i=0; $i<$no_of_ips; $i++) :?>
        <?php if ($ip_list[$i]["removable"] == 1) :?>
        <option dev="<?= $ip_list[$i]["if"] ?>" value="<?= $ip_list[$i]["ip"] ?>"<?= (isset($selected_ip) && ($selected_ip == $ip_list[$i]["ip"]))?" selected":""?>><?= $ip_list[$i]["if"] ?> - <?= $ip_list[$i]["ip"] ?></option>
        <?php endif; ?>
     <?php endfor; ?>
     </select>
         <input type="hidden" name="ethernetdev" id ="ethernetdev" value="">
         <input type="hidden" name="skipifshutdown" value="0"><p><hr/>
         <button class="btn btn-success" href="#" id="delip_sbmt">
                <span class="btn-label"><i class="icon-minus icon-white"></i> Delete IP</span>
              </button></p>
         </form>
        <p>&nbsp;</p>
     <p>Note: List shows those ips which are free and can be removed safely from the server</p>
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
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