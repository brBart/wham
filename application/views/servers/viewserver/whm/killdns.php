<?php Template::printHeader_(); ?>
<?php if(isset($status) && $status == "success") :?> 
    <script type="text/javascript">
        $(document).ready(function() {
            $("#kill_dns_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#domain").val() == "0") {
                    alert("Please select a valid zone from the list");
                    return;
                }
                
                var a = confirm("Are you sure you wish to delete the zone: " + $("#domain").val());
                if (a == false)
                    return;
                
                $("#kill_dns_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_killdns/" . $s_id . "/" ?>",
                    data: $("#kill_dns_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_killdns/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                    }
                });
                    
                
            });
        });
    </script>
<?php endif; ?>
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
              
                <p><b>Delete A DNS Zone</b></p><hr/>
      <?php if(isset($status) && $status == "success") :?>          
     <div id="kill_dns_div"><p class="alert alert-error"><span class="label label-important">ALERT</span> You are about to delete a DNS zone. This action cannot be undone. Please proceed with caution</p><p>&nbsp;</p>
         Select the zone you wish to delete<p>&nbsp;</p>
                <form id="kill_dns_form" class="form-horizontal">
                    <div class="control-group">
                    <label class="control-label" for="domain">Zone Name</label>
                    <div class="controls">
                    <select name="domain" id="domain">
                        <option value="0">-- select --</option>
                       <?php foreach ($zonelist as $zone) :?>
                        <option value="<?= $zone["domain"] ?>"><?= $zone["domain"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                    <div class="control-group">
                    <div class="controls"><button id="kill_dns_sbmt" class="btn btn-warning" href="#">
                <span class="btn-label"><i class="icon-minus-sign icon-white"></i> Delete DNS Zone</span>
              </button></div>
                </form></div>
     <p>&nbsp;</p>
         
            </div>
      <?php else :?> <?= $status; ?>          
      <?php endif; ?>
     <div id="results_div" style="display:block;"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     
     <p>&nbsp;</p>         
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>