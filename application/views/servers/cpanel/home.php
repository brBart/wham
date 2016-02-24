<?php Template::printHeader_(); ?>
<script type="text/javascript">

var subRemoveLock = false;
    
$(document).ready(function() {
    
    $(".removeAddon").bind("click", function(e) {
        e.preventDefault();
        $("#addon_rm_result").children().remove();
        $("#addon_rm_result").hide();
        
        if (subRemoveLock == true)
            return;
        
        domain = $(this).attr("domain");
        subdomain = $(this).attr("subdomain");
        
        choice = confirm("You are about to delete the addon domain " + domain + ". \n\nDo you wish to continue?");
        
        if (choice == false)
            return;
        
        subRemoveLock = true;
        
        $("#hiddenRemoveAddonForm input[name=domain]").val(domain);
        $("#hiddenRemoveAddonForm input[name=subdomain]").val(subdomain);
        
        cur = $(this);
        cur.next("span").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/remove_addon/<?= $s_id ?>/<?= $account ?>/",
            data: $("#hiddenRemoveAddonForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#addon_rm_result").html(text.msg);
                $("#addon_rm_result").show();
                $(cur).next("span").hide();
                if (text.status == 1) {
                    if ($(cur).parents("tbody").children().length == 1) {
                        $(cur).parents("tbody").append('<tr><td colspan="2">No results to display</td></tr>');
                    }
                    $(cur).parents("tr").remove();
                }
                setTimeout('$("#addon_rm_result").hide()', 3000);
                subRemoveLock = false;
            },

            error: function() {
                $("#addon_rm_result").html("Connection Error");
                $("#addon_rm_result").show();
                $(cur).next("span").hide();
                setTimeout('$("#addon_rm_result").hide()', 3000);
                subRemoveLock = false;
            }
        });
        
    });
    
    $("#addNewDomainBtn").bind("click", function(e) {
        e.preventDefault();
        $("#addon_add_result").children().remove();
        $("#addon_add_result").hide();
        
        if ($(this).hasClass("disabled") == "true")
            return;
        
        $(this).addClass("disabled");
        $("#loading").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/create_addon/<?= $s_id ?>/<?= $account ?>/",
            data: $("#newAddonForm").serialize() ,
            dataType: "text",
            success: function(text) {
                $("#addon_add_result").html(text);
                if (text.indexOf("Status : 1") !== -1 && text.indexOf("has been removed.") !== -1) { // fail
                    $("#addon_add_result").html(text + "&nbsp;<br/><p><font color='red'>FAILED. Although status is 1 (bug in CPanel API), this operation has failed.</font></p>");
                    $("#addon_add_result").show();
                    $("#addNewDomainBtn").removeClass("disabled");
                    $("#loading").hide();
                    setTimeout('$("#addon_add_result").hide()', 10000);
                    
                }
                else if (text.indexOf("Status : 1") !== -1 && text.indexOf("has been removed.") == -1) {
                    $("#addon_add_result").show();
                    location.reload(true);
                }
                else if (text.indexOf("Status : 1") == -1) { // fail
                    $("#addon_add_result").show();
                    $("#addNewDomainBtn").removeClass("disabled");
                    $("#loading").hide();
                    setTimeout('$("#addon_add_result").hide()', 10000);
                }
                
            },

            error: function() {
                $("#addon_add_result").html("Connection Error");
                $("#addon_add_result").show();
                $("#addNewDomainBtn").removeClass("disabled");
                $("#loading").hide();
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
                    <li class="active"><a href="#"><b>Addon</b></a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/parked/<?= $s_id ?>/<?= $account?>/">Parked</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/sub/<?= $s_id ?>/<?= $account?>/">Sub</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/email/<?= $s_id ?>/<?= $account?>/">Email</a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/custinfo/<?= $s_id ?>/<?= $account?>/">CustInfo</a></li>
                </ul>
            </div>
                
    
            
            <p>&nbsp;<br/></p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Current Addon Domains</b></p>
                <table class="table table-bordered table-striped">
                    <?php if (isset($addonlist["data"]) && is_array($addonlist["data"]) && (!isset($addonlist["data"]["domain"])) && count($addonlist["data"]) > 0) :?>
                    <?php foreach ($addonlist["data"] as $addon ) :?>
                        <tr domain="<?= $addon["domain"]?>">
                            <td><a href="http://<?= $addon["domain"]?>/" target="_blank"><?= $addon["domain"]?></a></td>
                            <td><a href="#" class="removeAddon" domain="<?= $addon["domain"]?>" subdomain="<?= $addon["domainkey"]?>">remove</a>
                            <span style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php elseif (isset($addonlist["data"]) && is_array($addonlist["data"]) && (isset($addonlist["data"]["domain"]))) :?>
                        <tr domain="<?= $addonlist["data"]["domain"]?>">
                            <td><a href="http://<?= $addonlist["data"]["domain"]?>/" target="_blank"><?= $addonlist["data"]["domain"] ?></a></td>
                            <td><a href="#" class="removeAddon" domain="<?= $addonlist["data"]["domain"] ?>" subdomain="<?= $addonlist["data"]["domainkey"]?>">remove</a>
                            <span style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span></td>
                        </tr>
                    <?php else :?>
                        <tr><td colspan="2">No results to display</td></tr>
                    <?php endif; ?>
                </table><p>&nbsp;</p><div id="addon_rm_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Add A New Addon Domain</b></p>
            <form class="form-horizontal" id="newAddonForm">
                <div class="control-group">
                    <label class="control-label" for="newdomain">Addon domain name</label>
                    <div class="controls"><input type="text" id="addon_newdomain" name="newdomain" placeholder="domain name"></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="subdomain">Sub domain</label>
                    <div class="controls"><input type="text" id="addon_subdomain" name="subdomain" placeholder="sub domain name"> .<?= $main_domain ?></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="dir">Directory</label>
                    <div class="controls"><i class="icon-home"></i> / public_html / <input type="text" id="addon_dir" name="dir" placeholder="directory"></div>
                </div>
                <div class="control-group">
                    <div class="controls"><button type="submit" class="btn btn-success" id="addNewDomainBtn"><i class="icon-plus-sign icon-white"></i> Create Addon Domain</button> <span id="loading" style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
                    </div>
                </div>
            </form><p>&nbsp;</p><div id="addon_add_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p><i class="icon-hand-right"></i> <b>Note</b><br/>&nbsp;</p>
            <p><i>Addon domain name</i> : The domain name of the addon domain you wish to create. (e.g. myseconddomain.com).</p>
            <p><i>Sub domain</i> : This value is the subdomain and FTP username corresponding to the new addon domain.</p>
            <p><i>Directory</i> : The path that will serve as the addon domain's home directory. Usually, the path will be <b>/home/<?= $account ?>/public_html/&lt;dir&gt;/</b> where &lt;dir&gt; is the new directory</p>
            <p>&nbsp;</p><hr/>
            <p>Warning: Addon domain API has a certain bug. <a href="http://forums.cpanel.net/f42/cpanel-api-create-domains-no-errors-given-252892.html" target="_blank">More info</a></p>
            <p>&nbsp;</p><p>&nbsp;</p>
        <form id="hiddenRemoveAddonForm">
            <input type="hidden" name="domain" value="">
            <input type="hidden" name="subdomain" value="">
        </form>
                
                </div>
            </div>
        </div>
    </div>
</div>
   <?php Template::printFooter_(); ?>   