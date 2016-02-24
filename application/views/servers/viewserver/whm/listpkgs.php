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
              
                <p><b>List All Packages</b></p><hr/>
     <div>
     <?php if(isset($package_list)) :?>    
     <table class="table table-condensed">
         <?php foreach ($package_list as $i) :?>
         <tr class="info">
             <td><b><font color="red"><?= $i->name ?></font></b></td>
             <td style="padding-right: 50px"><div align="right">
                     <a href="<?= site_url() ?>/servers/viewserver/whm_editpkg/<?= $s_id ?>/<?= $i->name ?>/">edit</a> | 
                     <a href="<?= site_url() ?>/servers/viewserver/whm_killpkg/<?= $s_id ?>/<?= $i->name ?>/">delete</a>
                 </div></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Max POP:</b> <?= $i->MAXPOP ?></td>
             <td><b>Has Shell:</b> <?= $i->HASSHELL ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Bandwidth Limit:</b> <?= $i->BWLIMIT ?></td>
             <td><b>Max SQL:</b> <?= $i->MAXSQL ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Max Mailing Lists:</b> <?= $i->MAXLST ?></td>
             <td><b>Max Parked:</b> <?= $i->MAXPARK ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Max Addon:</b> <?= $i->MAXADDON ?></td>
             <td><b>Quota:</b> <?= $i->QUOTA ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Dedicated IP:</b> <?= $i->IP ?></td>
             <td><b>Max FTP:</b> <?= $i->MAXFTP ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>Has CGI:</b> <?= $i->CGI ?></td>
             <td><b>Max Sub domains:</b> <?= $i->MAXSUB ?></td>
         </tr>
         <tr>
             <td style="padding-left:25px;"><b>FrontPage:</b> <?= $i->FRONTPAGE ?></td>
             <td></td>
         </tr>
         <tr>
             <td>&nbsp;</td><td>&nbsp;</td>
         </tr>
         <?php endforeach; ?>
     </table>
     <?php else :?>
         No packages available.
     <?php endif; ?>
         <p>&nbsp;</p>
     </div>
     
     
     <p>&nbsp;</p>      
            </div>
            
          </div>
        </div>
          
      </div>
    </div>
    <?php Template::printFooter_(); ?>