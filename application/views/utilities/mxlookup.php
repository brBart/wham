<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if (isset($args)) :?>
                    $("#domain").val("<?= $args ?>");
                    setTimeout('$("#mx_check_btn").click()', 300);
            <?php endif; ?>
                    
            $("#mx_check_btn").bind("click", function(e) {
                if ($("#domain").val().trim().length < 4)
                    alert("Invalid input. Cannot proceed..");
                else {
                    e.preventDefault();
                    $("#input_frm").hide();
                    $("#tmp_msg").show();
                    $("#results_div").children().remove();
                    query = $("#domain").val().trim();
                    $("#results_div").load("<?= site_url() ?>/utilities/mxlookup/backend/" + query + "/", function() {
                        $("#results_div .tool-result-header").remove();
                        $("#results_div .tool-result-table tr").each(function(){ $(this).children().last().remove() ; })
                        $("#results_div table.app_form").remove();
                        $("#results_div .tool-result-table").next().remove();
                        $("#results_div .tool-result-table a").removeAttr("onmousedown").removeAttr("href");
                        
                        $("#input_frm").show();
                        $("#tmp_msg").hide();
                        $("a:contains(Transcript)").remove();
                        $("#results_div .tool-result-div").show(); 
                    });
                    
                    $("#results_div2").children().remove();
                    $("#results_div2").load("<?= site_url() ?>/utilities/mxlookup/backend/" + query + "/spf/", function() {
                        $("#results_div2 .tool-result-header").remove();
                        $("#results_div2 .tool-result-table tr").each(function(){ $(this).children().last().remove() ; })
                        $("#results_div2 table.app_form").remove();
                        $("#results_div2 .tool-result-table").next().remove();
                        $("#results_div2 .tool-result-table a").removeAttr("onmousedown").removeAttr("href");
                        
                        $("#input_frm").show();
                        $("#tmp_msg").hide();
                        $("a:contains(Transcript)").remove();
                        $("#results_div2 .tool-result-div").show(); 
                    });
                }
            });
        });
    </script>
  </head>
  <body>
      <?php Template::printTopMenu_('utilities'); ?>
      <?php Template::printSideBar_("mxlookup"); ?>
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
          <li class="active">MX lookup</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <div id="input_frm"><form><input type="text" id="domain" placeholder="domain name">
               <button class="btn btn-success" href="#" id="mx_check_btn"><span class="btn-label"><i class="icon-info-sign icon-white"></i> MX Lookup</span></button>
               <p style="font-size: 12px">Enter the <b>domain name </b> to view its MX and SPF records (if any).
               <br/>&nbsp;<br/>This test will list MX records for a domain in priority order. The MX lookup 
               is done directly against the domain's authoritative name server, so changes to MX Records 
               should show up instantly. </p></form>
               </div>
               <div id="tmp_msg" style="display: none">working.. please be patient.. <img src='<?= base_url() ?>includes/images/working.gif'></div>
               <p>&nbsp;</p><div id="results_div"></div><p>&nbsp;</p><div id="results_div2"></div><p>&nbsp;</p><p>&nbsp;</p>
          </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>