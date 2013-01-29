<?php
	global $wpdb;
	$rb_interactivemap_options_arr = get_option('rb_interactivemap_options');
?>
<div class="wrap">
  <div id="rb-overview-icon" class="icon32"></div>
  <h2>Dashboard</h2>
  <p>You are using version <b><?php echo $rb_interactivemap_options_arr['databaseVersion']; ?></b></p>
  
  <div class="boxblock-holder">
  
    <div class="boxblock-container" style="width: 46%;">
 
        <div class="boxblock">
            <h3></h3>
            <div class="inner">
            </div>
        </div>

     </div>

    </div><!-- .container -->

    <div class="boxblock-container" style="width: 46%;">

    	<div class="boxblock">
            <h3>Actions</h3>
            <div class="inner">
               <?php
                echo "<p><a href='?page=interactivemap_locations' class=\"button-secondary\">Location</a> - Manage Business listings.</p>";
                ?>
            </div>
    	</div>
    
    </div><!-- .container -->

    <div class="clear"></div>

    <div class="boxblock-container" style="width: 93%;">

     <div class="boxblock">
        <div class="inner">
            <p>WordPress Plugins by <a href="http://code.bertholf.com/wordpress/rb-interactivemap-plugin/" target="_blank">Rob Bertholf</a>.</p>
        </div>
     </div>
     
    </div><!-- .container -->

</div>
</div>
