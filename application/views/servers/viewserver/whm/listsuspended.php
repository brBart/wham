<?php Template::printHeader_(); ?>
<?php 
if (isset($account_list_table)) 
    $no_of_accts = count($account_list_table); 

?>  
<?php if (isset($account_list_table) && $no_of_accts > 0) :?>
    <style type="text/css" title="currentStyle">
            @import "<?= base_url() ?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url() ?>includes/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').dataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": false,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": true,
                "aaSorting": [[ 0, "asc" ]]
           });        
            
            
            $("#unsuspend_acct_select_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($("#account_to_unsuspend").val() == "0") {
                    alert("Please select an account from the list");
                } else {
                    $("#modify_div").hide();
                    $("#status_msg_div").hide();
                    $("#results_div p").html("working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'>");
                    $("#results_div").show();

                    $.ajax({
                        type: "POST",
                        url: "<?=site_url() . "/servers/viewserver/whm_listsuspended/" . $s_id . "/" ?>",
                        data: $("#unsuspend_acct_form").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#status_msg_div").addClass("well").html(text).show();
                            $("#results_div p").html("<p align=right><a class='btn btn-success' href='<?=site_url() . "/servers/viewserver/whm_listsuspended/" . $s_id . "/" ?>'><span class='btn-label'>Go back</span></a><br/></p><b>RESULT:</b>");
                        }
                    });
                }
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
              
                <p><b>List Suspended Accounts</b></p><hr/>
     <div id="modify_div">
     <?php if (isset($account_list_table) && $no_of_accts > 0) :?>    
     <table class="table table-hover table-striped table-condensed display"<?php if($no_of_accts > 10) :?> id="example"<?php endif;?>>
	<thead>
            <tr><th>User</th><th>Domain</th><th class="hidden-phone">IP Address</th>
                <th class="hidden-phone">Owner</th><th class="hidden-phone hidden-tablet">Reason for suspension</th></tr>
	</thead>
        <tbody>
      <?php for($i=0; $i<$no_of_accts; $i++) :?>
            <tr>
                <td><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $s_id ?>/<?= $account_list_table[$i]->user ?>/"><?= $account_list_table[$i]->user ?></a></td>
                <td><a target="_blank" href="http://<?= $account_list_table[$i]->domain ?>/"><?= $account_list_table[$i]->domain ?></a></td>
                <td class="hidden-phone"><a target="_blank" href="http://<?= $account_list_table[$i]->ip ?>/"><?= $account_list_table[$i]->ip ?></a></td>
                <td class="hidden-phone"><?php if ($account_list_table[$i]->owner == "root") :?><?= $account_list_table[$i]->owner ?><?php else :?><a target="_blank" href="<?= site_url() ?>/servers/cpanel/home/<?= $s_id ?>/<?= $account_list_table[$i]->owner ?>/"><?= $account_list_table[$i]->owner ?></a><?php endif; ?></td>
                <td class="hidden-phone hidden-tablet"><?= $account_list_table[$i]->suspendreason ?></td>
            </tr>
      <?php endfor; ?>
        </tbody>
     </table>
         
         <p>&nbsp;</p><p><br/>&nbsp;<br/><b>Unsuspend an account</b> / <a href="<?= site_url() ?>/servers/viewserver/whm_unsuspendacct_multi/<?= $s_id?>/">Unsuspend Multiple Accounts</a><hr/></p>
         <form id="unsuspend_acct_form"><p>Select the account you wish to unsuspend:<br/>
             <select name="account_to_unsuspend" id="account_to_unsuspend">
                    <option value="0">-- select --</option>
                <?php foreach ($account_list as $account=>$detail) :?>
                    <option value="<?=$account ?>"<?= ( isset($selected) && $selected == $account  )?" selected":"" ?>><?=$detail ?></option>
                <?php endforeach; ?>
             </select><hr/></p>
             <p><button class="btn btn-success" href="#" id="unsuspend_acct_select_sbmt">
                <span class="btn-label"><i class="icon-check icon-white"></i> Unsuspend account</span>
              </button></p>
         </form>
     <?php else :?>
         No suspended accounts exist on this server.
         
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