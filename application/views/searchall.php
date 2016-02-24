<?php Template::printHeader_(); ?>
<?php if (!isset($error)) :?>
   <style type="text/css" title="currentStyle">
            @import "<?= base_url()?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url()?>includes/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    
    var dc_over = server_over = account_over = false;
    
    $(document).ready(function() {
        $("#loading").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/serversearch/search/",
            data: $("#dc_form").serialize() ,
            dataType: "text",
            success: function(text) {
                $("#dc_div").append(text);
                $("#dc_div table.example").dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "aaSorting": [[ 0, "asc" ]]
                });
                $("#dc_div input[aria-controls]").css({"width": "120px", "height" : "12px"});
                $("#dc_div select[aria-controls]").css({"width": "70px"});
                
                $("#dc_div").show();
                dc_over = true;
                
                if (server_over == true && account_over == true)
                    $("#loading").hide();
            },

            error: function() {
                $("#dc_div").append("Something went wrong. Please try again");
                
                $("#dc_div").show();
                dc_over = true;
                
                if (server_over == true && account_over == true)
                    $("#loading").hide();
            }
        });
        
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/serversearch/search/",
            data: $("#server_form").serialize() ,
            dataType: "text",
            success: function(text) {
                $("#server_div").append(text);
                $("#server_div table.example").dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "aaSorting": [[ 0, "asc" ]]
                });
                $("#server_div input[aria-controls]").css({"width": "120px", "height" : "12px"});
                $("#server_div select[aria-controls]").css({"width": "70px"});
                
                $("#server_div").show();
                server_over = true;
                
                if (dc_over == true && account_over == true)
                    $("#loading").hide();
            },

            error: function() {
                $("#server_div").append("Something went wrong. Please try again");
                
                $("#server_div").show();
                server_over = true;
                
                if (dc_over == true && account_over == true)
                    $("#loading").hide();
            }
        });
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/accounts/accountsearch/search/",
            data: $("#account_form").serialize() ,
            dataType: "text",
            success: function(text) {
                $("#account_div").append(text);
                $("#account_div table.example").dataTable({
                    "bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "aaSorting": [[ 0, "asc" ]]
                });
                $("#account_div input[aria-controls]").css({"width": "120px", "height" : "12px"});
                $("#account_div select[aria-controls]").css({"width": "70px"});
                
                $("#account_div").show();
                account_over = true;
                
                if (dc_over == true && server_over == true)
                    $("#loading").hide();
            },

            error: function() {
                $("#account_div").append("Something went wrong. Please try again");
                
                $("#account_div").show();
                account_over = true;
                
                if (dc_over == true && server_over == true)
                    $("#loading").hide();
            }
        });
        
        
    });
</script>    
<?php endif; ?>
  </head>
  
  <body>
      <?php Template::printTopMenu_(""); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Search All</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
              <?php if (isset($error) && $error == TRUE) :?>
              <b>ERROR: </b><i><?= $msg ?></i>
              <?php else :?>
              <p class="lead">You searched for "<i><?= $query?></i>" &nbsp; &nbsp; &nbsp;
              <span id="loading" style="display:none">working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
              <hr/></p>
          <div id="account_div" style="display:none">
              <p><i class="icon-hand-right"></i> <b>Accounts:</b></p>
              <p> &nbsp;</p>
            </div>
          <div id="server_div" style="display:none">
              <p><i class="icon-hand-right"></i> <b>Servers:</b></p>
              <p> &nbsp;</p>
            </div>
          <div id="dc_div" style="display:none">
              <p><i class="icon-hand-right"></i> <b>Data centers:</b></p>
              <p> &nbsp;</p>
            </div>
          <form id="dc_form">
              <input type="hidden" name="what" value="datacenter">
              <input type="hidden" name="for" value="any">
              <input type="hidden" name="condition" value="contains">
              <input type="hidden" name="query" value="<?= $query ?>">
          </form>
          <form id="server_form">
              <input type="hidden" name="what" value="server">
              <input type="hidden" name="for" value="any">
              <input type="hidden" name="condition" value="contains">
              <input type="hidden" name="query" value="<?= $query ?>">
          </form>
          <form id="account_form">
              <input type="hidden" name="what" value="account">
              <input type="hidden" name="for" value="any">
              <input type="hidden" name="condition" value="contains">
              <input type="hidden" name="query" value="<?= $query ?>">
          </form>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>