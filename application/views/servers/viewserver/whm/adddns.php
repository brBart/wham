<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#add_dns_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                $("#add_dns_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_adddns/" . $s_id . "/" ?>",
                    data: $("#add_dns_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_adddns/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
                    
                
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
              
                <p><b>Add A DNS Zone</b></p><hr/>
     <div id="add_dns_div">
         Enter details about the DNS zone you wish to create<p>&nbsp;</p>
                <form id="add_dns_form" class="form-horizontal">
                    <div class="control-group">
                    <label class="control-label" for="domain">Domain</label>
                    <div class="controls">
                    <input type="text" name="domain" id="domain" placeholder="domain">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="ip">IP Address</label>
                    <div class="controls">
                    <input type="text" name="ip" id="ip" placeholder="ip">
                    </div></div>
                    <div class="control-group">
                    <div class="controls"><button id="add_dns_sbmt" class="btn btn-success" href="#">
                <span class="btn-label"><i class="icon-plus-sign icon-white"></i> Add DNS Zone</span>
              </button></div>
                </form></div>
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
    <?php Template::printFooter_(); ?>