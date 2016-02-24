<?php Template::printHeader_(); ?>    
  </head>
  
  <body>
    <div class="container">
      <br>
      <div class="row">
        <div class="span8">
          <div class="hero-unit">
            <h3>Oops.. Something went wrong<hr/></h3>
            <p>Web Host Account Manager! has encountered one of the below problems:</p>
            <p>
                <ul style="font-size: smaller">
                    <li>The most recent API call failed due to network / remote server / firewall related issues</li>
                </ul>
            </p>
          </div>
        </div>
        
      </div>
    </div>
    
  </body>

</html>
<?php $this->db->cache_delete_all(); ?>