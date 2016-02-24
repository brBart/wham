<?php Template::printHeader_(); ?>
<script type="text/javascript">

var subRemoveLock = false;
    
$(document).ready(function() {
    
    $(".removeEmailLink").bind("click", function(e) {
        e.preventDefault();
        $("#sub_rm_result").children().remove();
        $("#sub_rm_result").hide();
        
        if (subRemoveLock == true)
            return;
        
        domain = $(this).attr("domain");
        email = $(this).attr("email");
        fullemail = $(this).attr("fullemail");
        
        choice = confirm("You are about to delete the email account: " + fullemail + 
            ". \n\nDo you wish to continue?");
        
        if (choice == false)
            return;
        
        subRemoveLock = true;
        
        $("#hiddenRemoveEmailForm input[name=domain]").val(domain);
        $("#hiddenRemoveEmailForm input[name=email]").val(email);
        
        cur = $(this);
        cur.next("span").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/remove_email/<?= $s_id ?>/<?= $account ?>/",
            data: $("#hiddenRemoveEmailForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#sub_rm_result").html(text.msg);
                $("#sub_rm_result").show();
                $(cur).next("span").hide();
                if (text.status == 1) {
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
    
    $("#addNewEmailBtn").bind("click", function(e) {
        e.preventDefault();
        $("#sub_emailadd_result").children().remove();
        $("#sub_emailadd_result").hide();
        
        if ($(this).hasClass("disabled") == "true")
            return;
        
        $(this).addClass("disabled");
        $("#loading").show();
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/create_email/<?= $s_id ?>/<?= $account ?>/",
            data: $("#newEmailForm").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#sub_emailadd_result").html(text.msg);
                $("#sub_emailadd_result").show();
                if (text.status == 1) {
                    location.reload(true);
                } else {
                    $("#addNewEmailBtn").removeClass("disabled");
                    $("#loading").hide();
                    setTimeout('$("#sub_emailadd_result").hide()', 10000);
                }
            },

            error: function() {
                $("#sub_emailadd_result").html("Connection Error");
                $("#sub_emailadd_result").show();
                $("#addNewEmailBtn").removeClass("disabled");
                $("#loading").hide();
                setTimeout('$("#sub_emailadd_result").hide()', 3000);
            }
        });
    });
    
    
    $(".resetPwdLink").bind("click", function(e){
        e.preventDefault();
        
        cur = $(this);
        
        $("#closePwdModal").html("Cancel");
        $("#email_pwd_modal_email_id").html($(cur).attr("fullemail"));
        $("#email_pwd_modal_form input[name=domain]").val($(cur).attr("domain"));
        $("#email_pwd_modal_form input[name=email]").val($(cur).attr("email"));
        $("#email_pwd_modal_form input[name=password]").val("");
        $("#resultResetPwd").html("");
        $("#email_pwd_modal").removeData("modal").modal({
            backdrop: 'static',
            keyboard: false
        });
        
    });
    
    
    $("#resetPasswdBtn").bind("click", function(e){
        e.preventDefault();
        $("#closePwdModal").hide();
        $("#resetPasswdBtn").hide();
        $("#loadingImgPwd").show();
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/resetpasswd_email/<?= $s_id ?>/<?= $account ?>/",
            data: $("#email_pwd_modal_form").serialize() ,
            dataType: "json",
            success: function(text) {
                $("#resultResetPwd").html(text.msg);
                $("#closePwdModal").html("Done").show();
                $("#resetPasswdBtn").show();
                $("#loadingImgPwd").hide();
                $("#email_pwd_modal_form input[name=password]").val("");
            },

            error: function() {
                $("#resultResetPwd").html("Connection Error");
                $("#closePwdModal").show();
                $("#resetPasswdBtn").show();
                $("#loadingImgPwd").hide();
                $("#email_pwd_modal_form input[name=password]").val("");
            }
        });
        
    });
    
    
    $(".resetQuotaLink").bind("click", function(e){
        e.preventDefault();
        
        cur = $(this);
        
        $("#closeQuotaModal").html("Cancel");
        $("#email_quota_modal_email_id").html($(cur).attr("fullemail"));
        $("#email_quota_modal_form input[name=domain]").val($(cur).attr("domain"));
        $("#email_quota_modal_form input[name=email]").val($(cur).attr("email"));
        $("#resultQuota").html("");
        $("#email_quota_modal_form input[name=quota]").val("");
        $("#email_quota_modal").removeData("modal").modal({
            backdrop: 'static',
            keyboard: false
        });
        
    });
    
    $("#resetQuotaBtn").bind("click", function(e){
        e.preventDefault();
        
        if ($("#email_quota_modal_form input[name=quota]").val().trim().length == 0)
            return;
        
        $("#closeQuotaModal").hide();
        $("#resetQuotaBtn").hide();
        $("#loadingImgQuota").show();
        
        id = $("#email_quota_modal_form input[name=email]").val() + "@" + $("#email_quota_modal_form input[name=domain]").val();
        
        
        $.ajax({
            type: "POST",
            url: "<?= site_url() ?>/servers/cpanel/resetquota_email/<?= $s_id ?>/<?= $account ?>/",
            data: $("#email_quota_modal_form").serialize() ,
            dataType: "json",
            success: function(text) {
                $("tr").each(function() {
                    if ($(this).hasClass(id)) {
                        $(this).children("td:first").next().next().html($("#email_quota_modal_form input[name=quota]").val() + " M");
                    } 
                });
                $("#resultQuota").html(text.msg);
                $("#closeQuotaModal").html("Done").show();
                $("#resetQuotaBtn").show();
                $("#loadingImgQuota").hide();
                $("#email_quota_modal_form input[name=quota]").val("");
                
                
            },

            error: function() {
                $("#resultQuota").html("Connection Error");
                $("#closeQuotaModal").show();
                $("#resetQuotaBtn").show();
                $("#loadingImgQuota").hide();
                $("#email_quota_modal_form input[name=quota]").val("");
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
                    <li class="active"><a href="#"><b>Email</b></a></li>
                    <li><a href="<?= site_url()?>/servers/cpanel/custinfo/<?= $s_id ?>/<?= $account?>/">CustInfo</a></li>
                </ul>
            </div>
                
    
            
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Current Email Accounts</b></p>
                <table class="table table-bordered table-striped">
                    <thead><tr><th>Email</th><th class="hidden-phone">Disk Used</th><th class="hidden-phone">Quota</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (isset($emailacctlist)) :?>
                        <?php foreach($emailacctlist as $emailacct=>$details) :?>
                        <tr><td colspan="4"><span class="label label-warning">DOMAIN</span> <b><font color="red"><?= $emailacct ?></font></b></td></tr>
                        <?php if(is_array($details) && count($details) > 0) :?>
                        <?php foreach ($details as $eachdetail) :?>
                        <tr class="<?= $eachdetail["email"] ?>">
                            <td><?= $eachdetail["email"] ?></td>
                            <td class="hidden-phone"><?= $eachdetail["diskused"] ?> M</td>
                            <td class="hidden-phone"><?= $eachdetail["quota"] ?> M</td>
                            <td>
                                <a href="#" class="resetPwdLink" domain="<?= $emailacct ?>" fullemail="<?= $eachdetail["email"] ?>" email="<?= $eachdetail["user"] ?>">password</a> | 
                                <a href="#" class="resetQuotaLink" domain="<?= $emailacct ?>" fullemail="<?= $eachdetail["email"] ?>" email="<?= $eachdetail["user"] ?>">quota</a> | 
                                <a href="#" class="removeEmailLink" domain="<?= $emailacct ?>" fullemail="<?= $eachdetail["email"] ?>" email="<?= $eachdetail["user"] ?>">delete</a><span style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else :?>
                        <tr><td colspan="4">No results to display</td></tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php else :?>
                        <tr><td colspan="4">No results to display</td></tr>
                    <?php endif; ?>
                        
                        
                        
                    
                    </tbody>    
                </table><p>&nbsp;</p><div id="sub_rm_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p>&nbsp;</p><p class="alert alert-success"><i class="icon-hand-right"></i> <b>Add A New Email Account</b></p>
            <form class="form-horizontal" id="newEmailForm">
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls"><input type="text" class="input-medium" name="email" placeholder="email"> @ 
                    <select name="domain">
                    <?php foreach ($emaildomains as $domain) :?>
                        <option value="<?= $domain ?>"><?= $domain ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>
                    <div class="controls"><input type="password" name="password" placeholder="password"></div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="quota">Quota</label>
                    <div class="controls"><input type="text" name="quota" placeholder="quota"> MB</div>
                </div>
                <div class="control-group">
                    <div class="controls"><button type="submit" class="btn btn-success" id="addNewEmailBtn"><i class="icon-plus-sign icon-white"></i> Create Email Account</button> <span id="loading" style="display:none">&nbsp; <img src='<?= base_url() . "includes/images/working.gif" ?>'></span>
                    </div>
                </div>
            </form><p>&nbsp;</p><div id="sub_emailadd_result" class="well" style="display:none"></div><p>&nbsp;</p>
            <p><i class="icon-hand-right"></i> <b>Note</b><br/>&nbsp;</p>
            <p><i>Email</i> : Username part of the e-mail account (the address part before "@")</p>
            <p><i>Password</i> : Password for the e-mail account.</p>
            <p><i>Quota</i> : Positive integer defining a disk quota for the e-mail account; could be 0 for unlimited.</p>
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
<div id="email_pwd_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="email_pwd_modal_label" aria-hidden="true">
    <form id="email_pwd_modal_form">
    <div class="modal-header">
    <h3 id="email_pwd_modal_label">Reset Password</h3>
  </div>
  <div class="modal-body">
    <p>You are about to reset the password for email address: <b><font color="red"><span id="email_pwd_modal_email_id"></span></font></b></p>
    <p>Enter the account's new password:</p>
        <input type="password" name="password" value=""> <img id="loadingImgPwd" style="display:none" src='<?= base_url() . "includes/images/working.gif" ?>'>
        <input type="hidden" name="domain" value="">
        <input type="hidden" name="email" value="">
    <p id="resultResetPwd"></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="closePwdModal">Cancel</button>
    <button class="btn btn-primary" id="resetPasswdBtn">Reset Password</button>
  </div></form>
</div>
      
<div id="email_quota_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="email_quota_modal_label" aria-hidden="true">
    <form id="email_quota_modal_form">
    <div class="modal-header">
    <h3 id="email_quota_modal_label">Modify Quota</h3>
  </div>
  <div class="modal-body">
    <p>You are about to modify the disk quota for email address <b><font color="red"><span id="email_quota_modal_email_id"></span></font></b></p>
    <p>Enter the account's new quota limit:</p>
    
        <input type="text" name="quota" value=""> MB &nbsp; &nbsp; <img id="loadingImgQuota" style="display:none" src='<?= base_url() . "includes/images/working.gif" ?>'>
        <input type="hidden" name="domain" value="">
        <input type="hidden" name="email" value="">
    <p id="resultQuota"></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true" id="closeQuotaModal">Cancel</button>
    <button class="btn btn-primary" id="resetQuotaBtn">Modify Quota</button>
  </div></form>
</div>
      <form id="hiddenRemoveEmailForm">
            <input type="hidden" name="domain" value="">
            <input type="hidden" name="email" value="">
        </form>
      
   <?php Template::printFooter_(); ?>   