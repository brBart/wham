<?php Template::printHeader_(); ?>
   <style type="text/css" title="currentStyle">
            @import "<?= base_url()?>includes/datatables/css/demo_table.css";
    </style>
    <script src="<?= base_url()?>includes/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#searchBtn").bind("click", function(e) {
            e.preventDefault();
            
            thisBtn = $(this);
            if ($(thisBtn).hasClass("disabled") == false) {
                $(thisBtn).addClass("disabled");
                $("#results").children().remove();
                $("#loading").show();
                
                $.ajax({
                    type: "POST",
                    url: "<?= site_url() ?>/accounts/accountsearch/search/",
                    data: $("#searchFrm").serialize() ,
                    dataType: "text",
                    success: function(text) {
                        $("#results").html(text);
                        $("#results table.example").dataTable({
                            "bPaginate": true,
                            "bLengthChange": true,
                            "bFilter": true,
                            "bSort": true,
                            "bInfo": true,
                            "bAutoWidth": true,
                            "aaSorting": [[ 0, "asc" ]]
                        });
                        $("input[aria-controls]").css({"width": "120px", "height" : "12px"});
                        $("select[aria-controls]").css({"width": "70px"});
                        $("#loading").hide();
                        $("#results").show();  
                        $(thisBtn).removeClass("disabled");
                    },

                    error: function() {
                        alert("Something went wrong!");
                        $("#results").children().remove();
                        $("#loading").hide();
                        $(thisBtn).removeClass("disabled");
                    }
                });
                
                
                
            }
        });
    });
</script>
  </head>
  
  <body>
      <?php Template::printTopMenu_('accounts'); ?>
      <?php Template::printSideBar_("accountsearch"); ?>
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
          <li class="active">Search ...</li>
        </ul>
        <div class="row-fluid">
          <div class="span12">
            <div>
                <p><b>Search ...</b><hr/></p>
                <p>&nbsp;</p>
        <div class="well">
        <form id="searchFrm">    
        <ul class="inline">
           <li><i class="icon-hand-right"></i> <b>Search for ..</b><br/>
               <select name="what" id="what_" class="input-medium">
                   <option value="account">Account</option>
               </select>
           </li>
           <li><i class="icon-hand-right"></i> <b>where ..</b><br/>
               <select name="for" id="for_" class="input-medium">
                   <option value="user">Username</option>
                   <option value="domain">Domain</option>
                   <option value="email">Email</option>
                   <option value="ip">IP Address</option>
                   <option value="owner">Owner</option>
                   <option value="plan">Plan</option>
                   <option value="any">- any -</option>
               </select>
           </li>
           <li><i class="icon-hand-right"></i> <b>condition ..</b><br/>
               <select name="condition" class="input-medium">
                   <option value="equals">equals</option>
                   <option value="contains">contains</option>
                   <option value="begins_with">begins with</option>
                   <option value="ends_with">ends with</option>
               </select>
           </li>
           <li>&nbsp;<br/>
               <input type="text" name="query" id="query_" placeholder="query" class="span10">
           </li>
           <li class="pull-right">
               <button type="submit" class="btn btn-success" id="searchBtn">Search</button>
           </li>
        </ul>  
        </form>    
        </div>                        
        <p>&nbsp;</p><p>&nbsp;</p>
        <div id="results"></div>
        <div id="loading" style="display:none">working.. please be patient.. <img src='<?= base_url() . "includes/images/working.gif" ?>'></div>
        <p>&nbsp;</p>
            </div>
            
            
          </div>
        </div>
      </div>
    </div>
    <?php Template::printFooter_(); ?>