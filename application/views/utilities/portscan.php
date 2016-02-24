<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if (isset($args)) :?>
                    $("#domain").val("<?= $args ?>");
                    setTimeout('$("#rbl_check_btn").click()', 300);
            <?php endif; ?>
                    
            $("#rbl_check_btn").bind("click", function(e) {
                if ($("#domain").val().trim().length < 4)
                    alert("Invalid input. Cannot proceed..");
                else {
                    e.preventDefault();
                    $("#input_frm").hide();
                    $("#tmp_msg").show();
                    $("#results_div").children().remove();
                    query = $("#domain").val().trim();
                    $("#results_div").load("<?= site_url() ?>/utilities/portscan/backend/" + query + "/", function() {
                        $("#results_div .tool-result-header").remove();
                        $("table.app_form").remove();
                        $("#input_frm").show();
                        $("#tmp_msg").hide();
                        $("#results_div .tool-result-div").show(); 
                    });
                }
            });
        });
    </script>
  </head>
  <body>
      <?php Template::printTopMenu_('utilities'); ?>
      <?php Template::printSideBar_("portscan"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/utilities/">Utilities</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Port scan tool</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <div id="input_frm"><form><input type="text" id="domain">
               <button class="btn btn-success" href="#" id="rbl_check_btn"><span class="btn-label"><i class="icon-info-sign icon-white"></i> Perform a Port Scan</span></button>
               <p style="font-size: 12px">Enter the IP <b>address / domain name / host name</b> you wish to scan.
               <br/>&nbsp;<br/>This test will tell you what standard services are running on your server and open 
               to the world.  You can type in an IP address or hostname. We attempt a full TCP connection and 
               graceful disconnect on each of about 15 common TCP ports we test, with a timeout of 3 seconds. 
               Possible results for each port are Success, Timeout or Refused.</p>
               </form></div>
               <div id="tmp_msg" style="display: none">working.. please be patient.. <img src='<?= base_url() ?>includes/images/working.gif'></div>
               <p>&nbsp;</p><div id="results_div"></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>