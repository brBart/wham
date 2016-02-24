<?php 
    $user = Mysession::getVar('username');
?>
<!-- printtopmenu -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?= base_url(); ?>">WHAM!</a>
          <div class="nav-collapse collapse">
            <form class="form-search pull-right navbar-text hidden-phone hidden-tablet" method="post" action="<?= site_url() ?>/welcome/searchall/">
                <input type="text" name="query" class="span2 search-query" placeholder="Search anything..">
            </form>
            <ul class="nav">
              <li<?= ($current == "home")?' class="active"':'' ;?>><a href="<?= base_url(); ?>"><i class="icon-home icon-white"></i> Home</a></li>
              <li<?= ($current == "servers")?' class="active"':"" ?>><a href="<?= site_url(); ?>/welcome/servers/"><i class="icon-tasks icon-white"></i> Servers</a></li>
              <li<?= ($current == "accounts")?' class="active"':"" ?>><a href="<?= site_url(); ?>/welcome/accounts/"><i class="icon-th-list icon-white"></i> Accounts</a></li>
              <li<?= ($current == "utilities")?' class="active"':"" ?>><a href="<?= site_url(); ?>/welcome/utilities/"><i class="icon-shopping-cart icon-white"></i> Utilities</a></li>
              <li<?= ($current == "settings")?' class="active"':"" ?>><a href="<?= site_url(); ?>/welcome/settings/"><i class="icon-wrench icon-white"></i> Settings</a></li>
              <li><a href="<?= site_url(); ?>/welcome/logout/"><i class="icon-ban-circle icon-white"></i> Logout (<?= $user ?>)</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<!-- printtopmenu ends -->
