<?php Template::printHeader_(); ?>
<?php if (isset($reseller_list)) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            
            
            $(".removeReseller").bind("click", function(e) {
                e.preventDefault();
                
                cur_res = $(this).parents("tr").attr("reseller");
                cnf = confirm("Do you wish to remove reseller privileges from user: " + cur_res);
                
                if (cnf == false)
                    return;
                
                $("#reseller_div").hide();
                $("#status_msg_div").hide();
                $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                $("#results_div").show();
                $("#remove_reseller_form input[name=user]").val(cur_res);

                $.ajax({
                    type: "POST",
                    url: "<?=site_url() . "/servers/viewserver/whm_listresellers/" . $s_id . "/" ?>",
                    data: $("#remove_reseller_form").serialize(),
                    dataType: "text",
                    success: function(text) {
                        $("#status_msg_div").addClass("well").html(text).show();
                        $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_listresellers/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
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
              
                <p><b>List All Resellers</b></p><hr/>
     <div id="reseller_div">
     <?php if (isset($reseller_list)) :?>    
     <table class="table table-hover table-striped table-condensed">
	<thead>
            <tr><th>UserName</th><th>Active</th><th>Suspended</th><th class="hidden-phone">Limit</th><th>Action</th></tr>
	</thead>
        <tbody>
      <?php foreach ($reseller_list as $reseller) :?>
            <tr reseller="<?= $reseller ?>">
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $s_id ?>/<?= $reseller ?>/"><?= $reseller ?></a></td>
                <td><?= $reseller_details[$reseller]["active"] ?></td>
                <td><?= $reseller_details[$reseller]["suspended"] ?></td>
                <td class="hidden-phone"><?= $reseller_details[$reseller]["limit"] ?></td>
                <td><a class="removeReseller" href="#" reseller="<?= $reseller ?>">remove reseller privileges</a></td>
            </tr>
      <?php endforeach; ?>
        </tbody>
     </table>
         <p>&nbsp;</p><p>&nbsp;</p>
         <p class="alert"><span class="label label-info">NOTE</span> Removing reseller privileges will not terminate 
         the reseller account or any of its sub-accounts.</p>
         
         <form id="remove_reseller_form">
             <input type="hidden" name="user" value="">
         </form>
     <?php elseif (isset($no_resellers)) :?>
         No reseller accounts exist on this server.
     <?php elseif (isset($error)) :?>
         Network/API error
     <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     <div id="results_div" style="display:block"><p></p>
         <div id="status_msg_div" style="display:none"></div>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>