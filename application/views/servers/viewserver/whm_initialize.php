<html>
<head>
    <title><?= (isset($title))?$title:"Server out of sync" ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>  
    <script type="text/javascript">
        var access_status, priv_status, accounts_status, packages_status;
        
        function update_access() {
            return $.ajax({
                url : "<?= site_url()?>/remote/cpanel/update_access/<?= $s_id?>/",
                type : "GET",
                dataType : "json",
                success : function(json) {
                    $("#access_div").show();
                    $("#access_div span").html(json.message);
                    access_status = json.status;
                }
            });
        }
        
        function update_privs() {
            return $.ajax({
                url : "<?= site_url()?>/remote/cpanel/update_privs/<?= $s_id?>/",
                type : "GET",
                dataType : "json",
                success : function(json) {
                    $("#priv_div").show();
                    $("#priv_div span").html(json.message);
                    priv_status = json.status;
                }
            });
        }
        
        function update_accounts() {
            return $.ajax({
                url : "<?= site_url()?>/remote/cpanel/update_accounts/<?= $s_id?>/",
                type : "GET",
                dataType : "json",
                success : function(json) {
                    $("#accounts_div").show();
                    $("#accounts_div span").html(json.message);
                    accounts_status = json.status;
                }
            });
        }
        
        function update_packages() {
            return $.ajax({
                url : "<?= site_url()?>/remote/cpanel/update_packages/<?= $s_id?>/",
                type : "GET",
                dataType : "json",
                success : function(json) {
                    $("#packages_div").show();
                    $("#packages_div span").html(json.message);
                    packages_status = json.status;
                }
            });
        }
        
        $(document).ready(function() {
            
            $.when(update_access(), update_privs(), update_accounts(), update_packages())
              .then(function() {
                    if (access_status == "success" && accounts_status == "success" && packages_status == "success" && priv_status == "success") 
                    {

                        $.ajax({
                            url : "<?= site_url()?>/remote/cpanel/unset_sync/<?= $s_id?>/",
                            type : "GET",
                            dataType : "json",
                            success : function(json) {
                                $("#msg").show();
                                setTimeout("window.location='<?= site_url()?>/servers/viewserver/whm/<?= $s_id?>/'",5000);
                            }
                        });
                    } else {
                        $("#msg font b").html("ERROR");
                        $("#msg span").html("Could not sync the server data at this point of time. Please try again later.");
                        $("#msg").show();
                        setTimeout("window.location='<?= site_url()?>/servers/viewserver/info/<?= $s_id?>/'",5000);
                    }
               
              })
              .fail(function() {
                $("#msg font b").html("FAIL");
                $("#msg span").html("Could not sync the server data at this point of time. Please try again later.");
                $("#msg").show();
                setTimeout("window.location='<?= site_url()?>/servers/viewserver/info/<?= $s_id?>/'",5000);
              });
                 
        });
    </script>
</head>
<body>
    <font size='+3' color='green'><b><?= (isset($title))?$title:"Server out of sync"?></b><hr style='width:520px;' align='left'/></font>
    <div style='margin-left:20px; width:500px'>
    <p><?= (isset($title))?$title:"It appears that this server is not in sync with WHAM!."?></p> 
    <p>WHAM! will now attempt to connect to the remote server and sync its Cpanel/WHM 
    information to its database.</p>
    <p>This process might take a few minutes to complete... Once the sync is 
    over, you will be redirected to the appropriate page.</p>
    <p><b><font color=red>WARNING: </font>*** DO NOT CLOSE THIS BROWSER WINDOW ***</b>
    </p><p>&nbsp;</p>
        <div id="access_div" style="display:none">* Retrieving API access info ... <span style="font-weight: bold; color: red"></span></div>
        <div id="priv_div" style="display:none">* Fetching list of privileges... <span style="font-weight: bold; color: red"></span></div>
        <div id="accounts_div" style="display:none">* Fetching account list ... <span style="font-weight: bold; color: red"></span></div>
        <div id="packages_div" style="display:none">* Fetching packages ... <span style="font-weight: bold; color: red"></span></div>
        <p>&nbsp;<p>
        <p id="msg" style="display:none"><font color="green"><b>DONE!</b></font><br/>&nbsp;<br/><span>Redirecting in 5 seconds ..<span></p>
    </div>
</body>
</html><?php $this->db->cache_delete_all(); ?>