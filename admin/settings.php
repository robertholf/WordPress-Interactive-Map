<div class="wrap">
  <div id="rb-overview-icon" class="icon32"></div>
  <h2>Settings</h2>
  <a class="button-primary" href="?page=interactivemap_settings&ConfigID=0" title="Overview">Overview</a>

<?php
if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
	if($_REQUEST['action'] == 'douninstall') {
		interactivemap_uninstall();
	}
}

if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID == 0) {
?>

  <div class="boxlinkgroup">
    <h2>Types</h2>
    <p>Setup Category Types</p>
      <div class="boxlink">
        <a class="button-primary" href="?page=interactivemap_settings&ConfigID=2" title="Types">Setup Category Types</a><br />
      </div>
  </div>

  <div class="boxlinkgroup">
    <h2>Uninstall</h2>
    <p>Caution, uninstalling will remove ALL DATA and files!</p>
      <div class="boxlink">
        <a class="button-primary" href="?page=interactivemap_settings&ConfigID=99" title="Uninstall">Uninstall</a><br />
      </div>
  </div>

<?php
}
elseif ($ConfigID == 1) {
//////////////////////////////////////////////////////////////////////////////////// ?>
  <h3>Settings</h3>
    	<?php
		$rb_interactivemap_options_arr = get_option('rb_interactivemap_options');
		?>

        <form method="post" action="options.php">
        <?php settings_fields( 'rb_interactivemap-settings-group' ); ?>
            <table class="form-table">
              <tr valign="top">
                <th scope="row"><?php _e('TEst', 'rb_interactivemap-plugin'); ?></th>
                <td><input type="text" name="rb_interactivemap_options[test]" value="<?php echo $rb_interactivemap_options_arr['test']; ?>"/></td>
              </tr>
            </table>
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'rb_interactivemap'); ?>" />
        </form>
<?php
}	 // End	
elseif ($ConfigID == 2) {
//////////////////////////////////////////////////////////////////////////////////// ?>
      <h3>Manage Service Types</h3>
<?php
    global $wpdb;

	if (isset($_POST['action'])) {
		$MapTypeID		= $_POST['MapTypeID'];
		$MapTypeTitle	= $_POST['MapTypeTitle'];
		$MapTypeText	= $_POST['MapTypeText'];
		$MapTypeActive	= $_POST['MapTypeActive'];

		// Error checking
		$error = "";
		$have_error = false;
		if(trim($MapTypeTitle) == ""){
			$error .= "<b><i>Title is required</i></b><br>";
			$have_error = true;
		}
	
		$action = $_POST['action'];
		switch($action) {
	
		// Add
		case 'addRecord':
			if($have_error){
				echo '<div id="message" class="updated"><p>'.$error.'</p></div>';
			} else {
				// Create Record
				$insert = "INSERT INTO " . table_interactivemap_maptype . " (MapTypeTitle,MapTypeText,MapTypeActive) VALUES ('" . $wpdb->escape($MapTypeTitle) . "','" . $wpdb->escape($MapTypeText) . "','" . $wpdb->escape($MapTypeActive) . "')";
				echo ('<div id="message" class="updated"><p>New record added successfully!</p></div>'); 
				$results = $wpdb->query($insert);
			}
		break;
		
		// Edit
		case 'editRecord':
			if($have_error){
				echo '<div id="message" class="updated"><p>'.$error.'</p></div>';
			} else {
				// Update Record
				$update = "UPDATE " . table_interactivemap_maptype . " SET 
					MapTypeTitle='" . $wpdb->escape($MapTypeTitle) . "',
					MapTypeText='" . $wpdb->escape($MapTypeText) . "',
					MapTypeActive='" . $wpdb->escape($MapTypeActive) . "'
					WHERE MapTypeID=$MapTypeID";
				$results = $wpdb->query($update);
				echo ('<div id="message" class="updated"><p>Record edited successfully!</p></div>');
			}
		break;

		// Delete bulk
		case 'deleteRecord':
			foreach($_POST as $MapTypeID) {
				// Verify Record
				$queryDelete = "SELECT * FROM  ". table_interactivemap_maptype ." WHERE MapTypeID =  \"". $MapTypeID ."\"";
				$resultsDelete = mysql_query($queryDelete);
				while ($dataDelete = mysql_fetch_array($resultsDelete)) {
					mysql_query("DELETE FROM " . table_interactivemap_maptype . " WHERE MapTypeID=$MapTypeID");
					echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
				} // is there record?
			}
		break;
		
		} // End Switch
	} // End Post
	if (isset($_GET['deleteRecord'])) {
		
			$MapTypeID = $_GET['MapTypeID'];
			// Verify Record
			$queryDelete = "SELECT * FROM  ". table_interactivemap_maptype ." WHERE MapTypeID =  \"". $MapTypeID ."\"";
			$resultsDelete = mysql_query($queryDelete);
			while ($dataDelete = mysql_fetch_array($resultsDelete)) {
				mysql_query("DELETE FROM  ". table_interactivemap_maptype ." WHERE MapTypeID='$MapTypeID'");
				echo ('<div id="message" class="updated"><p>Record deleted successfully!</p></div>');
			} // is there record?
			
	}; // Delete single
		
	if (($_GET['action'] == "edit") && (!isset($_POST['editRecord']))){
		
		$oldMapTypeID = $_GET['oldMapTypeID'];
		$query = "SELECT * FROM  ". table_interactivemap_maptype ." WHERE MapTypeID='$oldMapTypeID'";
		$results = mysql_query($query) or die ('Error, query failed');
		$count = mysql_num_rows($results);
		while ($data = mysql_fetch_array($results)) {
			$MapTypeID 		= $data['MapTypeID'];
			$MapTypeTitle 	= stripslashes($data['MapTypeTitle']);
			$MapTypeText 	= stripslashes($data['MapTypeText']);
			$MapTypeActive 	= stripslashes($data['MapTypeActive']);
		?>
		<h4 class="title">Edit Record</h4>
		<p>Make changes in the form below to edit a record. <strong>Required fields are marked *</strong></p>
		<?php 
		} 
	} else { ?>
		<h4 class="title">Add New Record</h4>
		<p>Fill in the form below to add a new record. <strong>Required fields are marked *</strong></p>
	<?php 
			$MapTypeID 		= "";
			$MapTypeTitle 	= "";
			$MapTypeText 	= "";
			$MapTypeActive 	= 1;
	} ?>
		
	<form method="post" enctype="multipart/form-data" action="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page'] ."&ConfigID=". $ConfigID; ?>">
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="MapTypeTitle">Title *</label></th>
                <td>
                    <input type="text" id="MapTypeTitle" name="MapTypeTitle" class="regular-text" value="<?php echo $MapTypeTitle; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="MapTypeText">Description</label></th>
                <td>
                    <input type="text" id="MapTypeText" name="MapTypeText" class="regular-text" value="<?php echo $MapTypeText; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Active</th>
                <td>
                    <select id="MapTypeActive" name="MapTypeActive">
                        <option>--</option>
                        <option value="1"<?php if ($MapTypeActive == 1) { echo " selected=selected"; }; ?>> Yes, Active</option>
                        <option value="0"<?php if ($MapTypeActive == 0) { echo " selected=selected"; }; ?>> No, Inactive</option>
                    </select>
                </td>
            </tr>
        <tbody>
    </table>
    <?php if (($_GET['action'] == "edit") && (!isset($_POST['editRecord']))){ ?>
        <p class="submit">
            <input type="hidden" name="action" value="editRecord" />
            <input type="hidden" name="ConfigID" value="<?php echo $ConfigID; ?>" />
            <input type="hidden" name="MapTypeID" value="<?php echo $MapTypeID; ?>" />
            <input type="submit" name="Submit" value="Update" class="button-primary" />
        </p>
    <?php } else { ?>
        <p class="submit">
            <input type="hidden" name="action" value="addRecord" />
            <input type="hidden" name="ConfigID" value="<?php echo $ConfigID; ?>" />
            <input type="submit" name="Submit" value="Submit" class="button-primary" />
        </p>
    <?php } ?>
    </form>
		
    <br>
    <h4 class="title">All Options</h4>
    <form method="post"action="<?php echo str_replace('%7E', '~', $_SERVER['SCRIPT_NAME']) . "?page=" . $_GET['page'] ."&ConfigID=". $ConfigID; ?>">
        <table cellspacing="0" class="widefat fixed">
        <?php 
        global $wpdb;
        
        $sort = "";
        if (isset($_GET['sort']) && !empty($_GET['sort'])){
            $sort = $_GET['sort'];
        }
        else {
            $sort = "MapTypeID";
        }
        
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
        <thead>
            <tr class="thead">
                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column" id="MapTypeID" scope="col" style="width:35px;"><a href="admin.php?page=interactivemap_settings&sort=MapTypeID&ConfigID=<?php echo $ConfigID; ?>&dir=<?php echo $sortDirection; ?>">ID</a></th>
                <th class="column" id="MapTypeTitle" scope="col"><a href="admin.php?page=interactivemap_settings&sort=MapTypeTitle&ConfigID=<?php echo $ConfigID; ?>&dir=<?php echo $sortDirection; ?>">Title</a></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="thead">
                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
                <th class="column" id="MapTypeID" scope="col">ID</th>
                <th class="column" id="MapTypeTitle" scope="col">Title</th>
            </tr>
        </tfoot>
        <tbody>
        <?php
        $query = "SELECT * FROM  ". table_interactivemap_maptype ."";
        $results = mysql_query($query) or die ('Error, query failed');
        $count = mysql_num_rows($results);
        while ($data = mysql_fetch_array($results)) {
            
            $MapTypeID = $data['MapTypeID'];
            $MapTypeTitle = stripslashes($data['MapTypeTitle']);
        ?>
        <tr>
            <th class="check-column" scope="row">
                <input type="checkbox" value="<?php echo $MapTypeID; ?>" class="administrator" id="<?php echo $MapTypeID; ?>" name="<?php echo $MapTypeID; ?>"/>
            </th>
            <td class="column MapTypeID">
                <?php echo $MapTypeID; ?>
            </td>
            <td class="column MapTypeTitle">
                <?php echo $MapTypeTitle; ?>
                <div class="row-actions">
                    <span class='edit'><a href="?page=interactivemap_settings&amp;action=edit&amp;oldMapTypeID=<?php echo $MapTypeID; ?>&amp;ConfigID=<?php echo $ConfigID; ?>" title="Edit this post">Edit</a> | </span>
                    <span class='delete'><a href='?page=interactivemap_settings&amp;deleteRecord&amp;MapTypeID=<?php echo $MapTypeID; ?>&amp;ConfigID=<?php echo $ConfigID; ?>' title='Delete this Record' class='submitdelete' onclick="if ( confirm('You are about to delete this record\'\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></span>
                </div>
            </td>
        </tr>
        <?php
        }
            mysql_free_result($results);
            if ($count < 1) {
        ?>
        <tr>
            <th class="check-column" scope="row"></th>
            <td class="name column-name" colspan="2">
                <p>There aren't any types loaded yet!</p>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    
    </table>
    <p class="submit">
        <input type="hidden" name="ConfigID" value="<?php echo $ConfigID; ?>" />
        <input type="hidden" name="action" value="deleteRecord" />
        <input type="submit" name="submit" value="<?php echo __('Delete','interactivemap_settings'); ?>" class="button-primary" />		
    </p>
    </form>
<?php
}	 // End	
elseif ($ConfigID == 99) {
?>
    <h3>Uninstall</h3>
    <div>Are you sure you want to uninstall?</div>
	<div><a href="?page=interactivemap_settings&action=douninstall">Yes! Uninstall</a></div>
<?php
}	 // End	
?>
</div>