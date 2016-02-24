<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#protocol").bind("change", function() {
                val = $(this).val();
                if (val == "smtp")
                    $("#smtp_div").show();
                else
                    $("#smtp_div").hide();
            });
            
            $("#sbmt_btn").bind("click", function(e) {
                e.preventDefault();
                var canContinue = false;
                if ($("#protocol").val() == "mail") {
                    canContinue = true;
                } else {
                    if ($.trim($("#smtp_host").val()).length < 5)
                        alert("Invalid smtp host specified");
                    else if ($.trim($("#smtp_port").val()).length < 2)
                        alert("Invalid smtp port number");
                    else if ($.trim($("#smtp_user").val()).length < 5)
                        alert("Invalid email address");
                    else if ($.trim($("#smtp_pass").val()).length < 3)
                        alert("Invalid password");
                    else
                        canContinue = true;
                }
                
                if (canContinue == true) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>/settings/email_settings/",
                        data: $("#email_settings_frm").serialize(),
                        dataType: "text",
                        success: function(text) {
                            $("#result").html(text);
                        }
                    });
                }
            });
        });
    </script>
  </head>
  
  <body>
      <?php Template::printTopMenu_('settings'); ?>
      <?php Template::printSideBar_("email_settings"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/settings/">Settings</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Email settings</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
               <p><b>Email Settings</b><hr/></p>
              <p><span class="label label-warning">Warning</span> 
                To receive email notifications, it is important that you configure the below 
                options correctly.
            </p>
                  <br/>
              <form id="email_settings_frm">
              <div style="background-color: snow; padding: 15px 5px">    
              Email Transport Medium<br/>
              <select name="protocol" id="protocol">
                  <option value="mail"<?= (isset($protocol) && $protocol == "mail")?" selected":"" ?>>PHP mail</option>
                  <option value="smtp"<?= (isset($protocol) && $protocol == "smtp")?" selected":"" ?>>SMTP</option>
              </select>
              <p><b style="color:red">PHP mail:- </b>Send email using php mail() function<br/>
              <b style="color:red">SMTP:- </b>Send email using local or remote mail server</p>
              </div>
                  <b><br/>Mail Server Settings<hr/></b>
                  <p>
                      Username<br/>
                      <input type="text" name="smtp_user" id="smtp_user" placeholder="user@domain.com" value="<?= (isset($smtp_user))?$smtp_user:"" ?>">
                  </p>
              <div id="smtp_div" style="padding: 5px 5px;<?= (isset($protocol) && $protocol == "mail")?" display:none":"" ?>">
                  <p>
                      SMTP Host (ip / hostname)<br/>
                      <input type="text" name="smtp_host" id="smtp_host" placeholder="localhost" value="<?= (isset($smtp_host))?$smtp_host:"" ?>">
                  </p>
                  <p>
                      SMTP Port<br/>
                      <input type="text" name="smtp_port" id="smtp_port" placeholder="25" value="<?= (isset($smtp_port))?$smtp_port:"" ?>">
                  </p>
                  <p>
                      SMTP Security<br/>
                      <select name="smtp_crypto" id="smtp_crypto">
                          <option value="none">none</option>
                          <option value="ssl"<?= (isset($smtp_crypto) && $smtp_crypto == "ssl")?" selected":"" ?>>SSL</option>
                          <option value="tls"<?= (isset($smtp_crypto) && $smtp_crypto == "tls")?" selected":"" ?>>TLS</option>
                      </select>
                  </p>
                  
                  <p>
                      Password<br/>
                      <input type="password" name="smtp_pass" id="smtp_pass" placeholder="email password" value="<?= (isset($smtp_pass))?$smtp_pass:"" ?>">
                  </p>
              </div>
                  <p style="color:red" id="result">&nbsp;</p>
                  <button class="btn btn-success" href="#" id="sbmt_btn"><span class="btn-label"><i class="icon-edit icon-white"></i> Save settings</span></button>    
              </form>    
                  <p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>