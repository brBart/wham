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
                    $("#results_div").load("<?= site_url() ?>/utilities/dnsreport/backend/" + query + "/ .tabular", function() {
                        $("#results_div table").removeClass("tabular")
                            .addClass("table").addClass("table-condensed")
                            .addClass("table-bordered");
                        $("#results_div table.table thead").css({"background-color" : "pink"});
                        $("#results_div table.table").css({"font-size" : "12px"});
                        $("#results_div a:contains(send feedback)").remove();
                        $("#input_frm").show();
                        $("#tmp_msg").hide();
                    });
                }
            });
        });
    </script>
  </head>
  <body>
      <?php Template::printTopMenu_('utilities'); ?>
      <?php Template::printSideBar_("dnsreport"); ?>
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
          <li class="active">DNS report</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <div id="input_frm"><form><input type="text" id="domain" placeholder="domain name">
               <button class="btn btn-success" href="#" id="rbl_check_btn"><span class="btn-label"><i class="icon-info-sign icon-white"></i> Get DNS report</span></button>
               <p style="font-size: 12px">Enter the <b>domain name</b> to check its DNS health.
               <br/>&nbsp;<br/></p></form>
               </div>
               <div id="tmp_msg" style="display: none">working.. please be patient.. <img src='<?= base_url() ?>includes/images/working.gif'></div>
               <p>&nbsp;</p><div id="results_div"></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>