	<!-- printfooter -->
	 </div></div></div><!--/.fluid-container-->
      <p>&nbsp;</p>
      <div><p class="pull-right hidden-phone"><font color="gray"><small>&copy; 2013 <a target="_blank" href="http://www.whamcp.com">whamcp.com</a></small></font></p>
      <p><small><font color="red">WHAM!</font> <font color="gray">&middot; Web Host Account Manager v1.1</font></small></p></div>
   <script src="<?= base_url() ?>includes/bootstrap/js/wham.js" language="javascript"></script>
   <script type="text/javascript">
       <?php if (Mysession::getVar("sidebar") == "RIGHT") :?>
       $(".span3").appendTo(".row-fluid:first");
       <?php endif; ?>
       <?php if (Mysession::getVar("sidebar_view") == "expand") :?>
       $("#more_nav").click();
       <?php endif; ?>
       $("div.navbar ul.nav li.active a").css({"color": "#ffcc00"});
       $("a.brand").css({"color": "red"});
   </script>
</body>
</html>
<!-- printfooter ends -->
<?php $this->db->cache_delete_all(); // required
//$this->output->enable_profiler(TRUE); ?>