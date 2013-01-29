<?php $siteurl = get_option('siteurl'); ?>

<div class="wrap">
  <div id="rb-overview-icon" class="icon32"></div>
  <h2>Manage Records</h2>
<?php
	global $wpdb;

if (isset($_POST['action'])) {

	$MapLocationID			=$_POST['MapLocationID'];
	$MapLocationTitle		=$_POST['MapLocationTitle'];
	$MapLocationText		=$_POST['MapLocationText'];
	$MapLocationTags		=$_POST['MapLocationTags'];
	$MapLocationPhone		=$_POST['MapLocationPhone'];
	$MapLocationHours		=$_POST['MapLocationHours'];
	$MapTypeID				=$_POST['MapTypeID'];
	$MapLocationFloor		=$_POST['MapLocationFloor'];
	$MapLocationCoords		=$_POST['MapLocationCoords'];
	$MapLocationActive		=$_POST['MapLocationActive'];

	// Error checking
	$error = "";
	$have_error = false;
	if(trim($MapLocationTitle) == ""){
		$error .= "<b><i>Record Name is required</i></b><br>";
		$have_error = true;
	}

	$action = $_POST['action'];
	switch($action) {

	// Add
	case 'addRecord':
		if($have_error){
        	echo ('<div id="message" class="error"><p>Error creating record, please ensure you have filled out all required fields.</p><p>'.$error.'</p></div>'); 
		} else { // Good to go...
			// Create Record
			$insert = "INSERT INTO ". table_interactivemap_location ." (MapTypeID,MapLocationTitle,MapLocationText,MapLocationTags,MapLocationPhone,MapLocationHours,MapLocationFloor,MapLocationCoords,MapLocationActive)" .
				"VALUES (" . $wpdb->escape($MapTypeID) . ",'" . $wpdb->escape($MapLocationTitle) . "','" . $wpdb->escape($MapLocationText) . "','" . $wpdb->escape($MapLocationTags) . "','" . $wpdb->escape($MapLocationPhone) . "','" . $wpdb->escape($MapLocationHours) . "','" . $wpdb->escape($MapLocationFloor) . "','" . $wpdb->escape($MapLocationCoords) . "','" . $wpdb->escape($MapLocationActive) . "')";
		    $results = $wpdb->query($insert);
			$MapLocationID = $wpdb->insert_id;

			echo ('<div id="message" class="updated"><p>New record added successfully! You may now <a href="'. str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page'] .'&action=editRecord&xMapLocationID='. $MapLocationID .'">Load Information</a> to the record.</p></div>'); 
		}
		rb_display_list();
		exit;
	break;
	
	// Edit
	case 'editRecord':
		if($have_error || empty($MapLocationID)){
        	echo ('<div id="message" class="error"><p>Error creating record, please ensure you have filled out all required fields.</p><p>'.$error.'</p></div>'); 
		} else { // Good to go...
			// Update Record
			$update = "UPDATE " . table_interactivemap_location . " SET 
				MapTypeID='" . $wpdb->escape($MapTypeID) . "',
				MapLocationTitle='" . $wpdb->escape($MapLocationTitle) . "',
				MapLocationText='" . $wpdb->escape($MapLocationText) . "',
				MapLocationTags='" . $wpdb->escape($MapLocationTags) . "',
				MapLocationPhone='" . $wpdb->escape($MapLocationPhone) . "',
				MapLocationHours='" . $wpdb->escape($MapLocationHours) . "',
				MapLocationFloor='" . $wpdb->escape($MapLocationFloor) . "',
				MapLocationCoords='" . $wpdb->escape($MapLocationCoords) . "',
				MapLocationActive='" . $wpdb->escape($MapLocationActive) . "'
				WHERE MapLocationID=$MapLocationID";
			$results = $wpdb->query($update);

		  echo ('<div id="message" class="updated"><p>Record updated successfully!</p></div>');
		}
		
		rb_display_list();
		exit;
	break;

	// Delete bulk
	case 'deleteRecord':
		foreach($_POST as $MapLocationID) {
			// Remove Record
			mysql_query("DELETE FROM " . table_interactivemap_location . " WHERE MapLocationID=$MapLocationID");
		}
		echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
		rb_display_list();
		exit;
	break;
	
	}
}
elseif (isset($_GET['deleteRecord'])) {
	$MapLocationID = $_GET['MapLocationID'];
	// Verify Record
	$queryDelete = "SELECT * FROM ". table_interactivemap_location ." WHERE MapLocationID =  \"". $MapLocationID ."\"";
	$resultsDelete = mysql_query($queryDelete);
	while ($dataDelete = mysql_fetch_array($resultsDelete)) {

		// Remove Record
		$delete = "DELETE FROM " . table_interactivemap_location . " WHERE MapLocationID = \"". $MapLocationID ."\"";
		$results = $wpdb->query($delete);
			
	echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
	} // is there record?
	rb_display_list();

}
elseif (($_GET['action'] == "editRecord") || ($_GET['action'] == "add")) {
	$action = $_GET['action'];

	?>
    <p><a class="button-primary" href="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page'] ?>">Back to Record List</a></p>
	<?php

  if (($action == "editRecord") && !empty($_GET['xMapLocationID'])) {

	$xMapLocationID = $_GET['xMapLocationID'];
	$query = "SELECT * FROM " . table_interactivemap_location . " WHERE MapLocationID='$xMapLocationID'";
	$results = mysql_query($query) or die ('Error, query failed');
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$MapLocationID			=stripslashes($data['MapLocationID']);
		$MapTypeID    			=stripslashes($data['MapTypeID']);
		$MapLocationTitle		=stripslashes($data['MapLocationTitle']);
		$MapLocationText		=stripslashes($data['MapLocationText']);
		$MapLocationTags		=stripslashes($data['MapLocationTags']);
		$MapLocationPhone    	=stripslashes($data['MapLocationPhone']);
		$MapLocationHours    	=stripslashes($data['MapLocationHours']);
		$MapLocationFloor    	=stripslashes($data['MapLocationFloor']);
		$MapLocationCoords    	=stripslashes($data['MapLocationCoords']);
		$MapLocationActive		=stripslashes($data['MapLocationActive']);
	} 
	?>
	<h3 class="title">Edit Record</h3>
	<p>Make changes in the form below to edit a Record. <strong>Required fields are marked *</strong></p>
	<?php
  } else {
		$MapLocationID			= "";
		$MapTypeID    = "";
		$MapLocationTitle		= "";
		$MapLocationText		= "";
		$MapLocationTags		= "";
		$MapLocationHours    	= "";
		$MapLocationPhone    	= "";
		$MapLocationFloor    	= "";
		$MapLocationCoords    	= "";
		$MapLocationActive		= 1;
	?>
	<h3 class="title">Add New Record</h3>
	<p>Fill in the form below to add a new Record. <strong>Required fields are marked *</strong></p>
	<?php 
  } 
?>

	<form method="post" enctype="multipart/form-data" action="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page']; ?>">

	<table class="form-table">
	<tbody>
    	<tr>
			<td colspan="2">
				<h2>Space Information</h2>
			</td>
		</tr>
    	<tr valign="top">
			<th scope="row">Space Name:</th>
			<td>
				<input type="text" id="MapLocationTitle" name="MapLocationTitle" value="<?php echo $MapLocationTitle; ?>" />
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">Type:</th>
			<td><select name="MapTypeID" id="MapTypeID">
			<?php
			if (empty($MapTypeID)) {
			  ?>
				<option value="" selected>--</option>
			  <?php
			}
			global $wpdb;
			$query1 = "SELECT MapTypeID, MapTypeTitle FROM ". table_interactivemap_maptype ."";
			$results1 = mysql_query($query1);
			$count1 = mysql_num_rows($results1);
			while ($data1 = mysql_fetch_array($results1)) {
				?><option value="<?php echo $data1['MapTypeID']; ?>"<?php if($MapTypeID == $data1['MapTypeID']){ echo " selected"; } ?>><?php echo $data1['MapTypeTitle']; ?></option>
			<?php } ?>
            </select>
            <?php
            if ($count1 < 1) {
                echo "There is nothing to select.  <a href='?page=interactivemap_settings&ConfigID=2'>Setup Categories Now</a>.";
            }
            ?>
			</td>
		</tr>
    	<tr valign="top">
			<th scope="row">Description:</th>
			<td>
				<textarea id="MapLocationText" name="MapLocationText"><?php echo $MapLocationText; ?></textarea>
			</td>
		</tr>
    	<tr valign="top">
			<th scope="row">Phone:</th>
			<td>
				<input type="text" id="MapLocationPhone" name="MapLocationPhone" value="<?php echo $MapLocationPhone; ?>" />
			</td>
		</tr>
    	<tr valign="top">
			<th scope="row">Hours:</th>
			<td>
				<input type="text" id="MapLocationHours" name="MapLocationHours" value="<?php echo $MapLocationHours; ?>" />
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">Tags/Keywords</th>
			<td>
				<textarea id="MapLocationTags" name="MapLocationTags"><?php echo $MapLocationTags; ?></textarea>
			</td>
		</tr>
    	<tr>
			<td colspan="2">
				<h2>Location Information</h2>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">Floor</th>
			<td>
				<input type="text" id="MapLocationFloor" name="MapLocationFloor" value="<?php echo $MapLocationFloor; ?>" />
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">Coordinates</th>
			<td>
				<input type="text" id="MapLocationCoords" name="MapLocationCoords" value="<?php echo $MapLocationCoords; ?>" />
			</td>
		</tr>
        <tr valign="top">
			<th scope="row">Status</th>
			<td>
            	<select id="MapLocationActive" name="MapLocationActive">
                    <option>--</option>
					<option value="1"<?php if ($MapLocationActive == 1) { echo " selected=selected"; }; ?>> Active</option>
					<option value="0"<?php if ($MapLocationActive == 0) { echo " selected=selected"; }; ?>> Inactive</option>
                </select>
			</td>
		</tr>
        </tbody>
    </table>

	<?php if ($_GET['action'] == "editRecord") { ?>
        <p class="submit">
            <input type="hidden" name="action" value="editRecord" />
            <input type="hidden" name="MapLocationID" value="<?php echo $MapLocationID; ?>" />
            <input type="submit" name="Submit" value="Update Record Record" class="button-primary" />
        </p>
    <?php } else { ?>
        <p class="submit">
            <input type="hidden" name="action" value="addRecord" />
            <input type="submit" name="Submit" value="Create Record Record" class="button-primary" />
        </p>
    <?php } ?>
    </form>
<?php 
} else {
rb_display_list();
	
}

function rb_display_list(){  ?>
    <h3 class="title">All Records</h3>
    <p><a class="button-primary" href="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page'] ?>&action=add">Create New Record</a></p>

        <?php 
        global $wpdb;
        
		
		// Sort By
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        else {
            $sort = "MapLocationTitle";
        }
		
		// Sort Order
        $dir = "";
        if (isset($_GET['dir']) && !empty($_GET['dir'])){
            $dir = $_GET['dir'];
            if ($dir == "desc" || !isset($dir) || empty($dir)){
               $sortDirection = "asc";
               } else {
               $sortDirection = "desc";
            } 
        }
        else {
            $dir = "asc";
        }
        ?>
    	<form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page']; ?>">	
        <table cellspacing="0" class="widefat fixed">
        <thead>
            <tr class="thead">
                <th class="column manage-column cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column" scope="col" style="width:50px;"><a href="admin.php?page=interactivemap_business&sort=MapLocationID&dir=<?php echo $sortDirection; ?>">ID</a></th>
                <th class="column" scope="col"><a href="admin.php?page=interactivemap_business&sort=MapLocationTitle&dir=<?php echo $sortDirection; ?>">Business</a></th>
                <th class="column" scope="col"><a href="admin.php?page=interactivemap_business&sort=MapLocationFloor&dir=<?php echo $sortDirection; ?>">Floor</a></th>
                <th class="column" scope="col"><a href="admin.php?page=interactivemap_business&sort=MapTypeID&dir=<?php echo $sortDirection; ?>">Industry</a></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="thead">
                <th class="column cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column" scope="col">ID</th>
                <th class="column" scope="col">Business</th>
                <th class="column" scope="col">Floor</a></th>
                <th class="column" scope="col">Industry</th>
            </tr>
        </tfoot>
        <tbody>
        <?php
        $query = "SELECT * FROM ". table_interactivemap_location ." $filter ORDER BY $sort $dir";
        $results2 = mysql_query($query);
        $count = mysql_num_rows($results2);
        while ($data = mysql_fetch_array($results2)) {
            $MapLocationID = $data['MapLocationID'];
            $MapLocationTitle = stripslashes($data['MapLocationTitle']);
            $MapLocationFloor = stripslashes($data['MapLocationFloor']);
            $MapLocationCoords = stripslashes($data['MapLocationCoords']);
            if ($data['MapLocationActive'] == 0) { $rowColor = " style='background: #FFEBE8'"; } else { $rowColor = ""; }
        ?>
        <tr<?php echo $rowColor; ?>>
            <th class="check-column" scope="row">
                <input type="checkbox" value="<?php echo $MapLocationID; ?>" class="administrator" id="<?php echo $MapLocationID; ?>" name="<?php echo $MapLocationID; ?>"/>
            </th>
            <td class="column MapLocationID">
                <?php echo $MapLocationID; ?>
            </td>
            <td class="column MapLocationTitle">
                <?php echo $MapLocationTitle; ?>
                <div class="row-actions">
                    <span class="edit"><a href="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page']; ?>&amp;action=editRecord&amp;xMapLocationID=<?php echo $MapLocationID; ?>" title="Edit this post">Edit</a> | </span>
                    <span class="delete"><a class='submitdelete' title='Delete this Record' href='<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page']; ?>&amp;deleteRecord&amp;MapLocationID=<?php echo $MapLocationID; ?>' onclick="if ( confirm('You are about to delete the record \'<?php echo $MapLocationTitle; ?>\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
                </div>
            </td>
            <td class="column MapLocationFloor">
                <?php echo $MapLocationFloor; ?>
            </td>
            <td class="column MapLocationCoords">
                <?php echo $MapLocationCoords; ?>
            </td>
        </tr>
        <?php
        }
            mysql_free_result($results2);
            if ($count < 1) {
				if (isset($filter)) { 
		?>
        <tr>
            <th class="check-column" scope="row"></th>
            <td class="column" colspan="3">
                <p>No records found with this criteria.</p>
            </td>
        </tr>
        <?php
				} else {
        ?>
        <tr>
            <th class="check-column" scope="row"></th>
            <td class="column" colspan="3">
                <p>There aren't any records loaded yet!</p>
            </td>
        </tr>
        <?php
					
				}
        ?>
        <?php } ?>
        </tbody>
    
    </table>
        <p class="submit">
            <input type="hidden" name="action" value="deleteRecord" />
            <input type="submit" name="submit" value="<?php echo __('Delete',$_GET['page']); ?>" class="button-primary" />		
        </p>
    </form>
<?php }

?>
</div>