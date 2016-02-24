<?php Template::printHeader_(); ?>
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
          <li>
            <a href="<?php echo site_url(); ?>/servers/viewserver/info/<?= $s_id; ?>/"><?=$hostname ?></a>
            <span class="divider">/</span>
          </li>
          <li class="active">Delete server</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              <p><b>Delete Server</b></p><hr/>
     <div id="delete_server_div">
     <div class="alert">
    <a class="close" data-dismiss="alert">×</a>
    <span>    
        <h4><font color="red">WARNING!</font></h4>
        You are about to remove this server from WHAM! db. This option cannot be undone.</span>
    </div>
         <form id="delete_server_form" method="post" action="<?php echo site_url(); ?>/servers/viewserver/delete/<?= $s_id ?>/">           
             <input type="hidden" name="server_id" value="<?= $s_id ?>">
             <p>Please type in the exact sentence given below into the textbox. This is 
                to confirm that you are well aware of what you are about to do.</p><p>&nbsp;<br/>
                    <font color="green"><b>I wish to delete this server from WHAM!</b></font></p>
             
             <input type="text" class="input-medium" id="confirm_msg" name="confirm_msg" value="">
             <hr/></p>
                  <button class="btn btn-success" href="#" id="modify_sbmt">
                <span class="btn-label"><i class="icon-trash icon-white"></i> Delete Server</span>
              </button>
         </form>
     
         <p>&nbsp;</p>
     </div>
     
     
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>