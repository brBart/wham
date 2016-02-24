<?php Template::printHeader_(); ?>
<script type="text/javascript">

var subRemoveLock = false;
    
$(document).ready(function() {
    
    $(".removeSubDomain").bind("click", function(e) {
        e.preventDefault();
        $("#sub_rm_result").children().remove();
        $("#sub_rm_result").hide();
        
        if (subRemoveLock == true)
            return;
        
        domain = $(this).attr("domain");
        
        choice = confirm("You are about to delete the sub domain " + domain + ". \n\nDo you wish to continue?");
        
        if (choice == false)
            return;
        
        subRemoveLock = true;
        
        $("#hiddenRemoveSubForm input[name=domain]").val(domain);
        
        cur = $(this);
        cur.next("span").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/remove_sub/<?= $s_id ?>/<?= $account ?>/",
            data: $("#hiddenRemoveSubForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#sub_rm_result").html(text.msg);
                $("#sub_rm_result").show();
                $(cur).next("span").hide();
                if (text.status == 1) {
                    if ($(cur).parents("tbody").children().length == 1) {
                        $(cur).parents("tbody").append('<tr><td colspan="2">No results to display</td></tr>');
                    }
                    $(cur).parents("tr").remove();
                }
                setTimeout('$("#sub_rm_result").hide()', 3000);
                subRemoveLock = false;
            },

            error: function() {
                $("#sub_rm_result").html("Connection Error");
                $("#sub_rm_result").show();
                $(cur).next("span").hide();
                setTimeout('$("#sub_rm_result").hide()', 3000);
                subRemoveLock = false;
            }
        });
        
    });
    
    $("#addNewDomainBtn").bind("click", function(e) {
        e.preventDefault();
        $("#sub_add_result").children().remove();
        $("#sub_add_result").hide();
        
        if ($(this).hasClass("disabled") == "true")
            return;
        
        $(this).addClass("disabled");
        $("#loading").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/create_sub/<?= $s_id ?>/<?= $account ?>/",
            data: $("#newSubForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#sub_add_result").html(text.msg);
                $("#sub_add_result").show();
                if (text.status == 1) {
                    location.reload(true);
                }
                else {
                    $("#addNewDomainBtn").removeClass("disabled");
                    $("#loading").hide();
                    setTimeout('$("#sub_add_result").hide()', 10000);
                }
            },

            error: function() {
                $("#sub_add_result").html("Connection Error");
                $("#sub_add_result").show();
                $("#addNewDomainBtn").removeClass("disabled");
                $("#loading").hide();
                setTimeout('$("#sub_add_result").hide()', 3000);
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
                    <li class="active"><a href="#"><b>Sub</b></a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/email/<?= $s_id ?>/<?= $account?>/">Email</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/custinfo/<?= $s_id ?>/<?= $account?>/">CustInfo</a></li>
                </ul>
            </div>
                
    
            
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Current Sub Domains</b></p>
                <table class="table table-bordered table-striped">
                    <thead><tr><th>Sub Domain Name</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (isset($subdomainlist["data"]) && is_array($subdomainlist["data"]) && (!isset($subdomainlist["data"]["domain"])) && count($subdomainlist["data"]) > 0) :?>
                    <?php foreach ($subdomainlist["data"] as $subdomain ) :?>
                        <tr domain="<?= $subdomain["domain"] ?>">
                            <td><a href="http://<?= $subdomain["domain"]?>/" target="_blank"><?= $subdomain["domain"]?></a></td>
                            <td><a href="#" class="removeSubDomain" domain="<?= $subdomain["domain"]?>">remove</a><span style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php elseif (isset($subdomainlist["data"]) && is_array($subdomainlist["data"]) && (isset($subdomainlist["data"]["domain"]))) :?>
                        <tr domain="<?= $subdomainlist["data"]["domain"] ?>">
                            <td><a href="http://<?= $subdomainlist["data"]["domain"]?>/" target="_blank"><?= $subdomainlist["data"]["domain"] ?></a></td>
                            <td><a href="#" class="removeSubDomain" domain="<?= $subdomainlist["data"]["domain"] ?>">remove</a><span style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span></td>
                        </tr>
                    <?php else :?>
                        <tr><td colspan="2">No results to display</td></tr>
                    <?php endif; ?>
                    </tbody>    
                </table><p>&nbsp;</p><div id="sub_rm_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Add A New Sub Domain</b></p>
            <form class="form-horizontal" id="newSubForm">
                <div class="control-group">
                    <label class="control-label" for="domain">Sub domain name</label>
                    <div class="controls"><input type="text" id="subdomain_newdomain" class="input-medium" name="domain" placeholder="domain name"> . 
                    <select name="rootdomain">
                    <?php foreach ($list_all_domains as $domain) :?>
                        <option value="<?= $domain ?>"><?= $domain ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls"><button type="submit" class="btn btn-success" id="addNewDomainBtn"><i class="icon-plus-sign icon-white"></i> Create Sub Domain</button> <span id="loading" style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
                    </div>
                </div>
            </form><p>&nbsp;</p><div id="sub_add_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p><i class="icon-hand-right"></i> <b>Note</b><br/>&nbsp;</p>
            <p><i>Sub domain name</i> : The local part of the subdomain you wish to add. (e.g. 'sub' if the subdomain's is sub.example.com). 
                This value should not include the domain with which the subdomain is associated.</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p><p>&nbsp;</p>
        <form id="hiddenRemoveSubForm">
            <input type="hidden" name="domain" value="">
        </form>
        
                
                </div>
            </div>
        </div>
    </div>
</div>
   <?php Template::printFooter_(); ?>   