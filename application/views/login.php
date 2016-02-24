<!doctype html>
<html>
  
  <head>
    <title>WHAM! - Web Host Account Manager!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo base_url(); ?>includes/bootstrap/css/bootstrap.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>includes/bootstrap/css/wham.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>includes/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function() {
    		$('#reset').bind('click', function(e) {
    			e.preventDefault();
    			$('#username').val("");
    			$('#pass').val("");
    		});
    	});
    </script>
  </head>
  
  <body>
    <div class="container">
      <br>
      <div class="row">
        <div class="span8">
          <div class="hero-unit">
            <h1>WHAM!</h1>
            <p>Web Host Account Manager</p>
            <p></p>
          </div>
            <?php if (isset($incorrect_pwd)) :?>
          <div class="alert">
            <a class="close" data-dismiss="alert">x</a>
            <span>
              <b>ERROR: </b>Incorrect username or password.</span>
          </div>
            <?php endif; ?>
        </div>
        <div class="span4">
          <div class="well">
            <form class="form-vertical" name="loginform" id="loginform" method="post" action="<?php echo site_url(); ?>">
              <div class="control-group">
                <label class="control-label">Username</label>
                <div class="controls">
                  <input type="text" class="input-large" name="username" id="username"> 
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Password</label>
                <div class="controls">
                  <input type="password" class="input-large" name="pass" id="pass"> 
                </div>
              </div>
            <div class="form-actions">
              <button class="btn btn-success" href="#">
                <span class="btn-label">Login</span>
              </button>
              <button class="btn" href="#" id="reset"><span class="btn-label">Reset</span></button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
  </body>

</html><?php $this->db->cache_delete_all(); ?>