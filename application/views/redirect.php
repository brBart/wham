<?php 

/**
 * This view is to show message on screen and then redirect a user
 * to another page of the application.
 * 
 * e.g. (controller code):
 * 
 * $redirect_data['active_in_top_menu'] = 'servers';  // will be shown active in top menu
 * $redirect_data['breadactive'] = 'Servers';  // will be shown active in breadcrumb
 * $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
 * $redirect_data['message'] = "<b>New data center added.</b>";
 * $this->load->view('redirect', $redirect_data);
 * 
 */

Template::printHeader_(); 

?>

  </head>
  
  <body>
      <?php Template::printTopMenu_($active_in_top_menu); ?>
      <?php Template::printSideBar_(); ?>
    <div class="row-fluid">
      <div class="span12">
        <ul class="breadcrumb">
          <li>
            <a href="<?php echo base_url(); ?>">WHAM!</a>
            <span class="divider">/</span>
          </li>
          <li class="active"><?php echo $breadactive; ?></li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
              <div>
                <b><?php echo $message; ?></b><br/>&nbsp;<br/>
                <p>Redirecting you to new page in 5 seconds.. click <a href="<?php echo $redirect_url; ?>">here</a> if it takes too long</p>
              </div>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
      <script type="text/javascript">
        setTimeout("window.location='<?php echo $redirect_url; ?>'",5000);
    </script>
    <?php Template::printFooter_(); ?>