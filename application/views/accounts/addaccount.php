<?php Template::printHeader_(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            
            $("#select_sbmt").bind("click", function(e) {
                e.preventDefault();
                if ($("#server").val() == 0) {
                    alert("Please select a valid server from the list!");
                } else {
                    window.location="<?= site_url() ?>/servers/viewserver/whm_createacct/" + $("#server").val() + "/";
                }    
            });
        });
    </script>
  </head> 
  <body>
      <?php Template::printTopMenu_('accounts'); ?>
      <?php Template::printSideBar_("add_account"); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li>
            <a href="<?php echo site_url(); ?>/welcome/accounts/">Accounts</a>
            <span class="divider">/</span>
          </li>
          <li class="active">Create a new account</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
                <p><b>Create A New Account</b></p><hr/>
     <div id="modify_div">
         <p>Select the server in which you wish to create account</p>
         <form>
             <select name="server" id="server">
                    <option value="0">-- select --</option>
                <?php if (isset($server_list)) :?>    
                <?php foreach ($server_list as $server) :?>
                    <option value="<?=$server->s_id ?>"><?=$server->s_hostname ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
             </select><hr/>
             <p><button class="btn btn-success" href="#" id="select_sbmt">
                <span class="btn-label">Next <i class="icon-hand-right icon-white"></i></span>
              </button></p>
         </form><p>&nbsp;</p><p>&nbsp;</p>
                <p><span class="label">Note</span> You will not be able to create accounts on INACTIVE server(s).
                </p>
     
         <p>&nbsp;</p>
     </div>
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>