<?php
include_once('../../../../wp-config.php');
$conn = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$conn);
global $wpdb;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title></title>
            <meta name='keywords' content='Waikiki Shopping Plaza' />
            <meta property='og:site_name' content='Waikiki Shopping Plaza' />
            <meta property='og:title' content='Waikiki Shopping Plaza' />
            <meta property='og:description' content='Waikiki Shopping Plaza' />
            <link rel="stylesheet" type="text/css" href="store.css" />
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
            <script type="text/javascript" src="jquery.js"></script>
            <script type="text/javascript" src="map.js"></script>
            <script type="text/javascript">
				// Hover Stores
				function show(name,region){
					document.getElementById(name + region).style.visibility = "visible";
				}
				function hide(name,region){
					document.getElementById(name + region).style.visibility = "hidden";
				}
				
				// Switch Floors
				function switchfloor(theid){ 
				  var thearray= new Array("floor0"); 
				  for(i=0; i<thearray.length; i++){ 
					  if(thearray[i] == theid){
						  alert("Selected " + thearray[i]);
						 // SHOW IT document.getElementById(theid).style.display='block'; 
					  } else { 
						 // HIDE IT document.getElementById(thearray[i]).style.display='none'; 
					  } 

				   } 
				}
            </script>
            <style type="text/css">
				body { background: #fff url(image/background.png) repeat-x; font-family: "Century Gothic"; }
				.clear { clear: both; }
				a { color: #bdbdbc; text-decoration: none; }
				
				#container { margin: 20px auto; padding: 10px; width: 965px; height: 550px; background: #fff; }
 
 				#col-map { float: left; width: 1000px; margin: 0px 20px; }
				#col-map .map img { width: 515px; height: 450px; }
				#col-map .details { height: 50px;}
				
				#col-type { float: left; width: 250px; height: 550px; background: #e3e2e2; overflow: hidden; }
				#col-type h2 { font-size: 18px; margin: 0px; padding: 5px 5px; height: 20px; color: #bdbdbc; }
				#col-type .inner { overflow-y: auto; overflow-x: none; height: 530px; background: #ededed; color: #696969; }
				#col-type .inner h3 { font-size: 13px; margin: 5px 0px 0px 0px; padding: 5px 0px 0px 5px; }
				#col-type .inner ul { margin: 0px; padding-left: 5px; }
				#col-type .inner ul li { list-style: none; padding: 0px; font-size: 11px; }
				#col-type .inner ul li a { color: #676767; display: block; }
				#col-type .inner ul li a:hover { background: #d0d0d0 url(image/background-menu.png) repeat-x; color: #1e76a3; }
			</style>
    </head>
    <body>
    <div id="container">
    
    	<div id="col-map">
        	<?php
            /* -----------  DISPLAY MAPS ---------------------- */ 
				echo "\t<div id=\"level0\" style=\"display: block;\">\n";
                echo "\t\t<img class=\"map\" src=\"TheStandardLayout.png\" usemap=\"#level0\" />\n";
                echo "\t\t\t<map name=\"level0\">\n";
                    // Get Locations
                    $MapLocations = $wpdb->get_results("SELECT MapLocationID, MapLocationTitle, MapLocationCoords FROM ". table_interactivemap_location ." WHERE MapLocationActive = 1 AND MapLocationFloor = 0");
                    foreach  ($MapLocations as $MapLocation) {
						echo "\t\t\t\t<area id=\"level". $thisFloor ."_s". $MapLocation->MapLocationID ."\" shape=\"poly\" title=\"". stripslashes($MapLocation->MapLocationTitle) ."\" href=\"#\" class=\"Store1\" onmouseover=\"show('hand', '1_a')\" onmouseout=\"hide('hand', '1_a')\" coords=\"". $MapLocation->MapLocationCoords ."\" />\n";
					}
                echo "\t\t</map>\n";
				echo "\t</div> \n";       
			?>
    	</div>
        
    	<div id="col-type">
        	<h2>Directory</h2>
            <div class="inner">
        	<?php
            /* -----------  DISPLAY TYPES ---------------------- */ 

			// Get Types
			$MapTypes = $wpdb->get_results("SELECT MapTypeID, MapTypeTitle FROM ". table_interactivemap_maptype ."");
			foreach  ($MapTypes as $MapType) {
				
				echo "<div id=\"MapType". $MapType->MapTypeID ."\">\n";
                echo "\t<h3 class=\"MapTypeTitle\">". $MapType->MapTypeTitle ."</h2>\n";
                echo "\t<ul>\n";
                    // Get Locations
                    $MapLocations = $wpdb->get_results("SELECT MapLocationID, MapLocationTitle, MapLocationFloor FROM ". table_interactivemap_location ." WHERE MapLocationActive = 1 AND MapTypeID = ". $MapType->MapTypeID);
                    foreach  ($MapLocations as $MapLocation) {
						echo "\t\t<li class=\"Store1\"><a href=\"javascript:switchfloor('floor". $MapLocation->MapLocationFloor ."');\" id=\"level". $thisFloor ."_t". $MapLocation->MapLocationID ."\">". stripslashes($MapLocation->MapLocationTitle) ."</a></li>\n";
					}
                echo "\t</ul>\n";
				echo "</div> \n";       
			}
			?>
            </div>
    	</div>

		<div class="clear"></div>
    </div>
    </body>
</html>