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
                    $("#results_div").load("<?= site_url() ?>/utilities/rblcheck/backend/" + query + "/", function() {
                        $("#results_div .tool-result-header").remove();
                        $("#results_div .tool-result-table tr").each(function(){ $(this).children().last().remove() ; })
                        $("#results_div a[onmousedown]").remove();
                        $("#results_div a:last").remove();
                        $("#results_div .alert-block").remove();
                        $("#results_div a").attr({"target" : "_blank" }).removeAttr("onclick");
                        
                        $("#results_div a").each(function() { 
                            a = "http://www.nullrefer.com/?" + $(this).attr("href"); 
                            $(this).attr({"href" : a}); 
                        });

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
      <?php Template::printSideBar_("rblcheck"); ?>
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
          <li class="active">Check RBL</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <div id="input_frm"><form><input type="text" id="domain">
               <button class="btn btn-success" href="#" id="rbl_check_btn"><span class="btn-label"><i class="icon-info-sign icon-white"></i> Check RBL</span></button>
               <p style="font-size: 12px">Enter the IP <b>address / domain name / host name</b> you wish to query in blacklists.
               <br/>&nbsp;<br/>This test will check a mail server IP address against most common DNS based email blacklists. 
               (Commonly called Realtime blacklist, DNSBL or RBL). If your mail server has been blacklisted, some email you 
               send may not be delivered. Email blacklists are a common way of reducing spam.</p>
               </form></div>
               <div id="tmp_msg" style="display: none">working.. please be patient.. <img src='<?= base_url() ?>includes/images/working.gif'></div>
               <p>&nbsp;</p><div id="results_div"></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>