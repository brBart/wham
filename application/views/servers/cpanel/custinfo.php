<?php Template::printHeader_(); ?>
<script type="text/javascript">
    
$(document).ready(function() {
    
    
    $("#saveBtn").bind("click", function(e) {
        e.preventDefault();
        
        if ($(this).hasClass("disabled"))
            return;
        
        $(this).addClass("disabled");
        $("#loadingImg").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/update_custinfo/<?= $s_id ?>/<?= $account ?>/",
            data: $("#custInfoForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#addresult").html(text.msg);
                $("#addresult").show();
                $("#loadingImg").hide();
                
                setTimeout('$("#addresult").hide()', 10000);
                $("#saveBtn").removeClass("disabled");
            },

            error: function() {
                $("#addresult").html("Connection Error");
                $("#addresult").show();
                $("#loadingImg").hide();
                
                setTimeout('$("#addresult").hide()', 10000);
                $("#saveBtn").removeClass("disabled");
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
        <a href="<?php echo site_url(); ?>/servers/viewserver/info/<?= $s_id ?>/"><?=$hostname ?></a>
        <span class="divider">/</span>
        </li>
        <li class="active"><?= $account ?> [ <?= $main_domain ?> ]</li>
        <li class="pull-right">
            <div class="btn-group hidden-phone">
  <button class="btn btn-mini">Account Functions</button>
  <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
  <li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_modifyacct/<?= $s_id ?>/<?= $account?>/">Modify Account</a></li>
  <li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_modifyquota/<?= $s_id ?>/<?= $account?>/">Quota Modification</a></li>
  <li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_modifypasswd/<?= $s_id ?>/<?= $account?>/">Change Password</a></li>
  <li class="divider"></li>
  <li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_removeacct/<?= $s_id ?>/<?= $account?>/">Terminate Account</a></li>
  <?php if (isset($suspended) && $suspended == 1) :?><li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_listsuspended/<?= $s_id ?>/<?= $account?>/">Unuspend Account</a></li><?php else: ?>
  <li><a target="_blank" href="<?= site_url()?>/servers/viewserver/whm_suspendacct/<?= $s_id ?>/<?= $account?>/">Suspend Account</a></li><?php endif; ?>
  <li class="divider"></li>
  <li><a target="_blank" href="<?= site_url() ?>/servers/viewserver/redirect/cpanel/<?= $s_id ?>/<?= $account ?>/">Login To CPanel</a></li>
  </ul>
</div>
        </li>
        <li class="pull-right">
            <div class="btn-group hidden-phone">
  <button class="btn btn-mini">Account Info</button>
  <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
<table class="table table-condensed table-bordered table-striped">
                    <thead><th colspan="2"><i class="icon-hand-right"></i> Account Summary</th></thead>
                <tbody>
                    <?php for($i=0; $i < count($stats["data"]); $i++) :?>
                        
                    <tr>
                        <td>
                         <b><?= $stats["data"][$i]["item"] ?></b>
                        </td><td>
                            <font color="green" key="<?= $stats["data"][$i]["id"] ?>"><?= (isset($stats["data"][$i]["value"]))?$stats["data"][$i]["value"] : $stats["data"][$i]["count"] ?></font> <font color="red"><?php if (isset($stats["data"][$i]["max"]) && !is_array($stats["data"][$i]["max"])) :?> / <?= $stats["data"][$i]["max"] ?><?php endif; ?></font>
                        </td>
                    </tr>
                    <?php endfor; ?>
                    
                </tbody>
                </table><table class="table table-condensed table-bordered table-striped">
                    <thead><th colspan="2"><i class="icon-hand-right"></i> Server Info</th></thead>
                <tbody>
                    <?php for($i=0; $i < count($serverinfo["data"]); $i++) :?>
                    <tr>
                        <td>
                         <b><?= $serverinfo["data"][$i]["item"] ?></b>
                        </td><td>
                            <font color="green"><?= (isset($serverinfo["data"][$i]["value"]))?$serverinfo["data"][$i]["value"] : "" ?></font>
                        </td>
                    </tr>
                    <?php endfor; ?>
                    
                </tbody>
                </table>
  </ul> &nbsp; 
</div> &nbsp; 
            
        </li>
    </ul>
        
        <div class="row-fluid cpaneldivcontainer">
            
            <div class="span12">
                <?php if (isset($suspended) && $suspended == 1) :?><p class="alert alert-warning"><span class="label label-important">WARNING</span> You are modifying a suspended account!</p><?php endif; ?>
                <div>
            <div>
                <ul class="nav nav-tabs">
                    <li><a href="<?= site_url()?>/servers/cpanel/home/<?= $s_id ?>/<?= $account?>/">Addon</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/parked/<?= $s_id ?>/<?= $account?>/">Parked</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/sub/<?= $s_id ?>/<?= $account?>/">Sub</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/email/<?= $s_id ?>/<?= $account?>/">Email</a></li>
                    <li class="active"><a href="#"><b>CustInfo</b></a></li>
                </ul>
            </div>
            
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Customer Information</b></p>
            <p>&nbsp;</p>
<form class="form-horizontal" id="custInfoForm">
  <div class="control-group">
    <label class="control-label" for="email">Primary Email</label>
    <div class="controls">
      <input type="text" placeholder="Email" name="email" value="<?=  $email ?>">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="second_email">Secondary Email</label>
    <div class="controls">
      <input type="text" name="second_email" placeholder="Secondary Email" value="<?= $second_email ?>">
    </div>
  </div>
    <div class="control-group">
    <label class="control-label" for="notify_disk_limit">Notify Disk Limit</label>
    <div class="controls">
      <input type="radio" name="notify_disk_limit" value="TRUE"<?= ($notify_disk_limit == 1)?" checked":"" ?>> TRUE &nbsp; &nbsp;
      <input type="radio" name="notify_disk_limit" value="FALSE"<?= ($notify_disk_limit == 0)?" checked":"" ?>> FALSE
    </div>
  </div>
    <div class="control-group">
    <label class="control-label" for="notify_bandwidth_limit">Notify Bandwidth Limit</label>
    <div class="controls">
      <input type="radio" name="notify_bandwidth_limit" value="TRUE"<?= ($notify_bandwidth_limit == 1)?" checked":"" ?>> TRUE &nbsp; &nbsp;
      <input type="radio" name="notify_bandwidth_limit" value="FALSE"<?= ($notify_bandwidth_limit == 0)?" checked":"" ?>> FALSE
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="notify_email_quota_limit">Notify Email Quota Limit</label>
    <div class="controls">
      <input type="radio" name="notify_email_quota_limit" value="TRUE"<?= ($notify_email_quota_limit == 1)?" checked":"" ?>> TRUE &nbsp; &nbsp;
      <input type="radio" name="notify_email_quota_limit" value="FALSE"<?= ($notify_email_quota_limit == 0)?" checked":"" ?>> FALSE
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" id="saveBtn" class="btn btn-success"><i class="icon-ok icon-white"></i> Save Contact Info</button>
      <span style="display:none" id="loadingImg">&nbsp; &nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
    </div>
  </div>
</form>        <p>&nbsp;</p>
<div id="addresult" class="well" style="display:none"></div>
                </div>
            </div>
        </div>
    </div>
</div>
      
   <?php Template::printFooter_(); ?>   