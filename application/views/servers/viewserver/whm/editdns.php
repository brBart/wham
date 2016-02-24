<?php Template::printHeader_(); ?>
<?php if(isset($status) && $status == "success") :?> 
    <script type="text/javascript">
        $(document).ready(function() {
            $("#select_dns_sbmt").bind("click", function(e) {
                e.preventDefault();
                
                if ($(this).hasClass("disabled") == true)
                    return;
                
                if ($("#domain").val() == "0") {
                    alert("Please select a valid zone from the list");
                    return;
                }
                $(this).addClass("disabled");
                $("#loading").show();
                $("#select_dns_form").submit();
                
                
            });
        });
    </script>
<?php endif; ?>
<?php if (isset($dump_zone) && $dump_zone != FALSE) :?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("img.loadingImg").hide();
            
            $(".editbtn").live("click", function(e) {
                e.preventDefault();

                thisBtn = $(this);
                if ($(thisBtn).hasClass("disabled") == false) {
                    parentTr = $(thisBtn).parents("tr");
                    parentTd = $(thisBtn).parents("td");

                    $("#rr input").each(function() { $(this).val($(this).attr("default")).attr({"disabled" : "true"}) ; });
                    $(parentTd).find("input").removeAttr("disabled");
                    $(parentTd).find("input.type_").attr({"disabled" : "true"});
                    $(parentTd).find("input.class_").attr({"disabled" : "true"});
                    $("#rr button.savebtn").hide();
                    $("#rr button.editbtn").show();
                    $(thisBtn).hide();
                    $(parentTd).find("button.savebtn").show();
                    
                }
            });
            
            $(".delbtn").live("click", function(e) {
                e.preventDefault();
                
                
                thisBtn = $(this);
                if ($(thisBtn).hasClass("disabled") == false) {
                    parentTr = $(thisBtn).parents("tr");
                    parentTd = $(thisBtn).parents("td");
                    
                    td_bg_color = $(parentTd).css("background-color");
                    $(parentTd).css({"background-color": "#ffcc00"});
                    
                    cnf = confirm("Do you wish to remove this resource record?\nThis action cannot be undone!");
                    if (cnf == false) {
                        $(parentTd).css({"background-color": td_bg_color});
                        return;
                    }
                    $("#add_record_btn").addClass("disabled");
                    $("#rr button.editbtn").addClass("disabled");
                    $("#rr button.delbtn").addClass("disabled");
                    $("#rr button.savebtn").addClass("disabled");
                    $("#rr input").each(function() { $(this).val($(this).attr("default")).attr({"disabled" : "true"}) ; });
                    $(parentTd).find("input").removeAttr("disabled");
                    $(parentTd).find("input.type_").attr({"disabled" : "true"});
                    $(parentTd).find("input.class_").attr({"disabled" : "true"});
                    $("#rr button.savebtn").hide();
                    $("#rr button.editbtn").show();
                    $(thisBtn).hide();
                    $(parentTd).find("img.loadingImg").show();
                    curLine = $(parentTr).attr("line");
                    
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/servers/viewserver/whm_editdns_delete/<?= $s_id ?>/",
                        data: { domain: "<?= $domain ?>", Line: curLine },
                        dataType: "text",
                        success: function(text) {
                            if (text == "success") {
                                $(parentTr).nextUntil().each( function() { $(this).attr({ "line": $(this).attr("line") - 1 }) } );
                                $(parentTr).remove();
                                $("#rr button.editbtn").removeClass("disabled").show();
                                $("#rr button.delbtn").removeClass("disabled").show();
                                $("#rr button.savebtn").removeClass("disabled").hide();
                                $("#add_record_btn").removeClass("disabled");
                                
                            } else {
                                alert(text);
                                $(parentTd).css({"background-color": td_bg_color});
                                setTimeout('$(parentTd).find("span.msgFail").slideUp()', 1000);
                                $("#rr button.editbtn").removeClass("disabled").show();
                                $("#rr button.delbtn").removeClass("disabled").show();
                                $("#rr button.savebtn").removeClass("disabled").hide();
                                $("#add_record_btn").removeClass("disabled");
                                
                                $(parentTd).find("img.loadingImg").hide();
                                $(parentTd).find("span.msgFail").slideDown();
                                $(parentTd).find("input").attr({"disabled" : "true"});
                            }
                        },

                        error: function() {
                            alert("Something went wrong!");
                            $(parentTd).css({"background-color": td_bg_color});
                            setTimeout('$(parentTd).find("span.msgFail").slideUp()', 1000);
                            $("#rr button.editbtn").removeClass("disabled").show();
                            $("#rr button.delbtn").removeClass("disabled").show();
                            $("#rr button.savebtn").removeClass("disabled").hide();
                            $("#add_record_btn").removeClass("disabled");

                            $(parentTd).find("img.loadingImg").hide();
                            $(parentTd).find("span.msgFail").slideDown();
                            $(parentTd).find("input").attr({"disabled" : "true"});
                        }
                    });
                }
            });
            
            $(".savebtn").live("click", function(e) {
                e.preventDefault();
                
                thisBtn = $(this);
                if ($(thisBtn).hasClass("disabled") == true)
                    return;
                
                $(thisBtn).hide();
                parentTr = $(thisBtn).parents("tr");
                parentTd = $(thisBtn).parents("td");
                $("#hiddenform").children().remove();
                $(parentTd).clone().appendTo($("#hiddenform"));
                $('<input type="hidden" name="domain" value="<?= $domain ?>">').appendTo($("#hiddenform"));
                $('<input type="hidden" name="Line" value="' + $(parentTr).attr("line") + '">').appendTo($("#hiddenform"));
                $("#hiddenform input").removeAttr("disabled");
                $("#rr button.editbtn").addClass("disabled");
                $("#rr button.delbtn").addClass("disabled");
                $("#add_record_btn").addClass("disabled");
                $(parentTd).find("img.loadingImg").show();
                
                $.ajax({
                    type: "POST",
                    url: "<?= site_url() ?>/servers/viewserver/whm_editdns_edit/<?= $s_id ?>/",
                    data: $("#hiddenform").serialize(),
                    dataType: "text",
                    success: function(text) {
                        if (text == "success") {
                            setTimeout('$(parentTd).find("span.msgOk").slideUp()', 1000);
                            $(parentTd).find("img.loadingImg").hide();
                            $(parentTd).find("input").each(function() { $(this).attr({ "default" : $(this).val() }) ; } );
                            $(parentTd).find("button.editbtn").show();
                            $("#rr button.editbtn").removeClass("disabled");
                            $("#rr button.delbtn").removeClass("disabled");
                            $("#add_record_btn").removeClass("disabled");
                            $(parentTd).find("span.msgOk").slideDown();
                            $(parentTd).find("input").attr({"disabled" : "true"});
                        } else {
                            alert(text);
                            setTimeout('$(parentTd).find("span.msgFail").slideUp()', 1000);
                            $(parentTd).find("img.loadingImg").hide();
                            $(thisBtn).show();
                            $("#rr button.editbtn").removeClass("disabled");
                            $("#rr button.delbtn").removeClass("disabled");
                            $("#add_record_btn").removeClass("disabled");
                            $(parentTd).find("span.msgFail").slideDown();
                            $(parentTd).find("input").removeAttr("disabled");
                            $(parentTd).find("input.type_").attr({"disabled" : "true"});
                            $(parentTd).find("input.class_").attr({"disabled" : "true"});
                        }
                    },
                    
                    error: function() {
                        alert("Something went wrong!");
                        $(parentTd).find("img.loadingImg").hide();
                        setTimeout('$(parentTd).find("span.msgFail").slideUp()', 1000);
                        $(thisBtn).show();
                        $("#rr button.editbtn").removeClass("disabled");
                        $("#rr button.delbtn").removeClass("disabled");
                        $("#add_record_btn").addClass("disabled");
                        $(parentTd).find("span.msgFail").slideDown();
                        $(parentTd).find("input").removeAttr("disabled");
                        $(parentTd).find("input.type_").attr({"disabled" : "true"});
                        $(parentTd).find("input.class_").attr({"disabled" : "true"});
                    }
                });
            });
            
            $("#selectRR").bind("change", function() {
                $(this).nextUntil().remove();
                if ($(this).val() == "A"){
                    $('<input style="margin-left: 10px" type="text" class="input-medium address_" name="address" placeholder="IP Address">').insertAfter($(this));
                } else if ($(this).val() == "NS") {
                    $('<input style="margin-left: 10px" type="text" class="input-medium nsdname_" name="nsdname" placeholder="Nameserver">').insertAfter($(this));
                } else if ($(this).val() == "TXT") {
                    $('<input style="margin-left: 10px" type="text" class="input-xlarge txtdata_" name="txtdata" placeholder="Text">').insertAfter($(this));
                } else if ($(this).val() == "CNAME") {
                    $('<input style="margin-left: 10px" type="text" class="input-medium cname_" name="cname" placeholder="CNAME">').insertAfter($(this));
                } else if ($(this).val() == "MX") {
                    $('<input style="margin-left: 10px" type="text" class="input-medium preference_" name="preference" placeholder="Preference"> &nbsp; <input type="text" class="input-medium exchange_" name="exchange" placeholder="Exchange">').insertAfter($(this));
                }
            });
            
            $("#add_record_btn").bind("click", function(e) {
                e.preventDefault();
                if ($(this).hasClass("disabled") == true)
                    return;
                
                $(this).addClass("disabled");
                $("#loading").show();
                $("#rr button.editbtn").addClass("disabled");
                $("#rr button.delbtn").addClass("disabled");
                $("#rr button.savebtn").addClass("disabled");
                $.ajax({
                    type: "POST",
                    url: "<?= site_url() ?>/servers/viewserver/whm_editdns_add_rr/<?= $s_id ?>/",
                    data: $("#add_record_frm").serialize(),
                    dataType: "text",
                    success: function(text) {
                        if (text == "success") {
                            newLineNo = parseInt($("#rr tr:last").attr("line")) + 1;
                            $("#rr tr:last").after('<tr line="' + newLineNo + '"><td></td></tr>');
                            
                            
                            $("#example_tds div[type="+ $("#selectRR").val() + "]").children().clone().appendTo($("#rr tr:last td"));
                            $("#rr tr:last td").find("input[name=name]").attr( {"default": $("#add_record_frm input[name=name]").val(), "value": $("#add_record_frm input[name=name]").val() });
                            $("#rr tr:last td").find("input[name=ttl]").attr( {"default": $("#add_record_frm input[name=ttl]").val(), "value": $("#add_record_frm input[name=ttl]").val() });
                            $("#rr tr:last td").find("input[name=class]").attr( {"default": $("#add_record_frm input[name=class]").val(), "value": $("#add_record_frm input[name=class]").val() });
                            $("#rr tr:last td").find("input[name=type]").attr( {"default": $("#add_record_frm select[name=type]").val(), "value": $("#add_record_frm select[name=type]").val() });
                            
                            $("#rr tr:last td").find("input[name=address]").attr( {"default": $("#add_record_frm input[name=address]").val(), "value": $("#add_record_frm input[name=address]").val() });
                            $("#rr tr:last td").find("input[name=nsdname]").attr( {"default": $("#add_record_frm input[name=nsdname]").val(), "value": $("#add_record_frm input[name=nsdname]").val() });
                            $("#rr tr:last td").find("input[name=txtdata]").attr( {"default": $("#add_record_frm input[name=txtdata]").val(), "value": $("#add_record_frm input[name=txtdata]").val() });
                            $("#rr tr:last td").find("input[name=cname]").attr( {"default": $("#add_record_frm input[name=cname]").val(), "value": $("#add_record_frm input[name=cname]").val() });
                            $("#rr tr:last td").find("input[name=preference]").attr( {"default": $("#add_record_frm input[name=preference]").val(), "value": $("#add_record_frm input[name=preference]").val() });
                            $("#rr tr:last td").find("input[name=exchange]").attr( {"default": $("#add_record_frm input[name=exchange]").val(), "value": $("#add_record_frm input[name=exchange]").val() });
                            
                            $("#add_record_btn").removeClass("disabled");
                            $("#loading").hide();
                            $("#rr button.editbtn").removeClass("disabled");
                            $("#rr button.delbtn").removeClass("disabled");
                            $("#rr button.savebtn").removeClass("disabled");
                            
                            $("#add_record_frm input[type=text]").val("");
                            
                        } else {
                            alert(text);
                            $("#add_record_btn").removeClass("disabled");
                            $("#loading").hide();
                            $("#rr button.editbtn").removeClass("disabled");
                            $("#rr button.delbtn").removeClass("disabled");
                            $("#rr button.savebtn").removeClass("disabled");
                        }
                    },
                    
                    error: function() {
                        alert("Something went wrong!");
                        $("#add_record_btn").removeClass("disabled");
                        $("#loading").hide();
                        $("#rr button.editbtn").removeClass("disabled");
                        $("#rr button.delbtn").removeClass("disabled");
                        $("#rr button.savebtn").removeClass("disabled");
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
              
                <p><b>Edit A DNS Zone</b></p><hr/>
      <?php if(isset($status)) :?>          
      <?php if($status == "success") :?>          
     <div class="hidden-phone">
         Select the zone you wish to edit<p>&nbsp;</p>
                <form id="select_dns_form" class="form-horizontal" method="post" action="<?= site_url() ?>/servers/viewserver/whm_editdns/<?= $s_id ?>/">
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
                    <div class="controls"><button id="select_dns_sbmt" class="btn btn-success" href="#">
                <span class="btn-label"><i class="icon-pencil icon-white"></i> Edit DNS Zone</span>
              </button> &nbsp; <span id="loading" style="display:none"> &nbsp; <img src="<?= base_url()?>includes/images/working.gif"></span></div>
                </form></div>
     <p>&nbsp;</p>
         
            </div><div class="hidden-desktop hidden-tablet">This interface cannot be accessed from phone. Please use a tablet or PC instead to edit DNS zones.</div>
      <?php else :?> <?= $status; ?>          
      <?php endif; ?><?php endif; ?>
      <?php if (isset($dump_zone) && $dump_zone != FALSE) :?>
      <?php 
        $zone = $dump_zone["record"];
        $count = count($zone);
      ?>
                
      <div class="alert alert-success">This interface allows management of DNS zones that are in proper format as required by Cpanel.</div><p>&nbsp;</p>
      
      <table border="0">
          <tr><td><font face="monospace" style="font-size: 12px"><?= $zone[0]["raw"] ?></font></td></tr>
          <tr><td><font face="monospace" style="font-size: 12px"><?= $zone[1]["raw"] ?></font></td></tr>
          <tr><td><font face="monospace" style="font-size: 12px"><?= $zone[2]["type"] ?> &nbsp; <?= $zone[2]["ttl"] ?></font></td></tr>
      </table><hr/>
      <table class="table-condensed" border="0" width="100%">
          <tr>
              <td width="45%">
                  <font face="monospace" style="font-size: 12px"><?= $zone[3]["name"] ?> &nbsp; <?= $zone[3]["ttl"] ?> &nbsp; <?= $zone[3]["class"] ?> &nbsp; <?= $zone[3]["type"] ?></font>
              </td>
              <td width="30%"><font face="monospace" style="font-size: 12px"><?= $zone[3]["mname"] ?></font></td>
              <td width="25%"><font face="monospace" style="font-size: 12px"><?= $zone[3]["rname"] ?> &nbsp; (</font></td>
          </tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px"><?= $zone[3]["serial"] ?></font></td><td>; <font face="monospace" style="font-size: 12px; font-style: italic">Serial Number</font></td></tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px"><?= $zone[3]["refresh"] ?></font></td><td>; <font face="monospace" style="font-size: 12px; font-style: italic">Refresh</font></td></tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px"><?= $zone[3]["retry"] ?></font></td><td>; <font face="monospace" style="font-size: 12px; font-style: italic">Retry</font></td></tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px"><?= $zone[3]["expire"] ?></font></td><td>; <font face="monospace" style="font-size: 12px; font-style: italic">Expire</font></td></tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px"><?= $zone[3]["minimum"] ?></font></td><td>; <font face="monospace" style="font-size: 12px; font-style: italic">Minimum TTL</font></td></tr>
          <tr><td>&nbsp;</td><td><font face="monospace" style="font-size: 12px">)</font></td><td>&nbsp;</td></tr>
          <tr><td colspan="3">&nbsp;</td></tr></table>
      <table class="table-condensed table-striped" id="rr" border="0" width="100%">
          <?php for ($i = 4; $i < $count; $i ++) :?>
          <?php if ($zone[$i]["type"] == ":RAW" && $zone[$i]["raw"] == "" ) :?>
             <?php continue; ?>
          <?php endif; ?>
          <tr line="<?= $zone[$i]["Line"]?>">
          <td>
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="<?= $zone[$i]["name"] ?>" value="<?= $zone[$i]["name"] ?>" disabled> &nbsp; 
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="<?= $zone[$i]["ttl"] ?>" value="<?= $zone[$i]["ttl"] ?>" disabled> &nbsp;
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="<?= $zone[$i]["class"] ?>" value="<?= $zone[$i]["class"] ?>" disabled> &nbsp;
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="<?= $zone[$i]["type"] ?>" value="<?= $zone[$i]["type"] ?>" disabled> &nbsp; &nbsp; &nbsp;
              <?php if($zone[$i]["type"] == "A") :?>
                <input type="text" class="input-medium address_" name="address" placeholder="IP Address" default="<?= $zone[$i]["address"] ?>" value="<?= $zone[$i]["address"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "NS") :?>
                <input type="text" class="input-medium nsdname_" name="nsdname" placeholder="Nameserver" default="<?= $zone[$i]["nsdname"] ?>" value="<?= $zone[$i]["nsdname"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "TXT") :?>
                <input type="text" class="input-xlarge txtdata_" name="txtdata" placeholder="Text" default='<?= $zone[$i]["txtdata"] ?>' value='<?= $zone[$i]["txtdata"] ?>' disabled>
              <?php elseif ($zone[$i]["type"] == "CNAME") :?>
                <input type="text" class="input-medium cname_" name="cname" placeholder="CNAME" default="<?= $zone[$i]["cname"] ?>" value="<?= $zone[$i]["cname"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "AAAA") :?>
                <input type="text" class="input-xlarge address_" name="address" placeholder="AAAA" default="<?= $zone[$i]["address"] ?>" value="<?= $zone[$i]["address"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "AFSDB") :?>
                <input type="text" class="input-mini subtype_" name="subtype" placeholder="Subtype" default="<?= $zone[$i]["subtype"] ?>" value="<?= $zone[$i]["subtype"] ?>" disabled> &nbsp; <input type="text" class="input-xlarge hostname_" name="hostname" placeholder="Hostname" default="<?= $zone[$i]["hostname"] ?>" value="<?= $zone[$i]["hostname"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "DNAME") :?>
                <input type="text" class="input-medium dname_" name="hostname" placeholder="Hostname" default="<?= $zone[$i]["dname"] ?>" value="<?= $zone[$i]["dname"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "HINFO") :?>
                <input type="text" class="input-medium hwtype_" name="subtype" placeholder="Hardware type" default="<?= $zone[$i]["cpu"] ?>" value="<?= $zone[$i]["cpu"] ?>" disabled> &nbsp; <input type="text" class="input-medium os_" name="os" placeholder="OS Version" default="<?= $zone[$i]["os"] ?>" value="<?= $zone[$i]["os"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "RP") :?>
                <input type="text" class="input-medium rp_" name="email" placeholder="Email" default="<?= $zone[$i]["mbox"] ?>" value="<?= $zone[$i]["mbox"] ?>" disabled> &nbsp; <input type="text" class="input-medium txtdname_" name="txtdname" placeholder="TXT Pointer with more info" default="<?= $zone[$i]["txtdname"] ?>" value="<?= $zone[$i]["txtdname"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "SRV") :?>
                <input type="text" class="input-mini priority_" placeholder="Priority" default="<?= $zone[$i]["priority"] ?>" value="<?= $zone[$i]["priority"] ?>" disabled> &nbsp; <input type="text" class="input-mini weight_" placeholder="Weight" default="<?= $zone[$i]["weight"] ?>" value="<?= $zone[$i]["weight"] ?>" disabled> &nbsp; <input type="text" class="input-mini port_" placeholder="Port" default="<?= $zone[$i]["port"] ?>" value="<?= $zone[$i]["port"] ?>" disabled> &nbsp; <input type="text" class="input-medium target_" placeholder="Target" default="<?= $zone[$i]["target"] ?>" value="<?= $zone[$i]["target"] ?>" disabled>
              <?php elseif ($zone[$i]["type"] == "MX") :?>
                <input type="text" class="input-mini preference_" name="preference" placeholder="Preference" default="<?= $zone[$i]["preference"] ?>" value="<?= $zone[$i]["preference"] ?>" disabled> &nbsp; <input type="text" class="input-medium exchange_" name="exchange" placeholder="Exchange" default="<?= $zone[$i]["exchange"] ?>" value="<?= $zone[$i]["exchange"] ?>" disabled>
              <?php endif; ?> &nbsp; &nbsp; <span class="label label-success msgOk" style="display:none"><i class="icon-ok icon-white"></i> SUCCESS</span><span class="label label-warning msgFail" style="display:none"><i class="icon-remove icon-white"></i> FAIL</span>
              <?php if ($zone[$i]["type"] == "A" || $zone[$i]["type"] == "NS" || $zone[$i]["type"] == "TXT" || $zone[$i]["type"] == "CNAME" || $zone[$i]["type"] == "MX") :?><span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button> &nbsp; <button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
              <?php else: ?><span class="pull-right"><button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
              <?php endif; ?>
          </td>
          </tr>
          <?php endfor; ?>
      </table>
      
      <p>&nbsp;</p>
      <!--<pre><? //print_r($dump_zone) ?></pre>-->
      <b>Add New DNS Record</b>
      <div class="well">
          <form class="form-inline" id="add_record_frm">
              <input type="hidden" name="zone" value="<?= $domain ?>">
              <input type="text" class="input-medium name_" placeholder="Name" name="name"> &nbsp;
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl"> &nbsp;
              <input type="text" class="input-mini class_" placeholder="Class" name="class"> &nbsp; 
              <select name="type" class="input-mini" id="selectRR">
                  <option value="A">A</option>
                  <option value="NS">NS</option>
                  <option value="TXT">TXT</option>
                  <option value="CNAME">CNAME</option>
                  <option value="MX">MX</option>
              </select> &nbsp;
              <input type="text" class="input-medium address_" name="address" placeholder="IP Address">
          </form><button class="btn btn-success" id="add_record_btn">Add</button> &nbsp; <span id="loading" style="display:none"> &nbsp; <img src="<?= base_url()?>includes/images/working.gif"></span>
      </div><p>&nbsp;</p><p>&nbsp;</p>
      <div style="display:none" id="example_tds">
          <div type="A">
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="" value="" disabled><span> &nbsp; &nbsp; &nbsp; </span>
              <input type="text" class="input-medium address_" name="address" placeholder="IP Address" default="" value="" disabled>
               <span>&nbsp; &nbsp; </span><span class="label label-success msgOk" style="display:none"><i class="icon-ok icon-white"></i> SUCCESS</span><span class="label label-warning msgFail" style="display:none"><i class="icon-remove icon-white"></i> FAIL</span>
               <span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button> &nbsp; <button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
          </div>
          <div type="NS">
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="" value="" disabled><span> &nbsp; &nbsp; &nbsp; </span>
              <input type="text" class="input-medium nsdname_" name="nsdname" placeholder="Nameserver" default="" value="" disabled>
               <span>&nbsp; &nbsp; </span><span class="label label-success msgOk" style="display:none"><i class="icon-ok icon-white"></i> SUCCESS</span><span class="label label-warning msgFail" style="display:none"><i class="icon-remove icon-white"></i> FAIL</span>
               <span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button> &nbsp; <button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
          </div>
          <div type="TXT">
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="" value="" disabled><span> &nbsp; &nbsp; &nbsp; </span>
              <input type="text" class="input-xlarge txtdata_" name="txtdata" placeholder="Text" default='' value='' disabled>
              <span>&nbsp; &nbsp; </span><span class="label label-success msgOk" style="display:none"><i class="icon-ok icon-white"></i> SUCCESS</span><span class="label label-warning msgFail" style="display:none"><i class="icon-remove icon-white"></i> FAIL</span>
               <span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button> &nbsp; <button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
          </div>
          <div type="CNAME">
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="" value="" disabled><span> &nbsp; &nbsp; &nbsp; </span>
              <input type="text" class="input-medium cname_" name="cname" placeholder="CNAME" default="" value="" disabled>&nbsp; &nbsp; <span class="label label-success msgOk" style="display:none"><i class="icon-ok icon-white"></i> SUCCESS</span><span class="label label-warning msgFail" style="display:none"><i class="icon-remove icon-white"></i> FAIL</span>
              <span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button><span> &nbsp; </span><button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
          </div>
          <div type="MX">
              <input type="text" class="input-medium name_" placeholder="Name" name="name" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini ttl_" placeholder="TTL" name="ttl" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini class_" placeholder="Class" name="class" default="" value="" disabled><span> &nbsp; </span>
              <input type="text" class="input-mini type_" placeholder="Type" name="type" default="" value="" disabled><span> &nbsp; &nbsp; &nbsp; </span>
              <input type="text" class="input-medium preference_" name="preference" placeholder="Preference" default="" value="" disabled><span> &nbsp; </span><input type="text" class="input-medium exchange_" name="exchange" placeholder="Exchange" default="" value="" disabled>
              <span class="pull-right"><button class="btn btn-success btn-mini editbtn"><i class="icon-pencil icon-white"></i></button><button class="btn btn-success btn-mini savebtn" style="display:none">save</button><span> &nbsp; </span><button class="btn btn-mini btn-danger delbtn"><i class="icon-trash icon-white"></i></button></span><span class="pull-right"><img class="loadingImg" style="margin-top:5px" src="<?= base_url() ?>includes/images/progress_bar.gif" style="display:none"></span>
          </div>
      </div>
      <?php endif; ?>          
     
      <p>&nbsp;</p>        
          </div>
        </div>
          <form id="hiddenform" style="display:none"></form>
      </div>
    </div>
    <?php Template::printFooter_(); ?>