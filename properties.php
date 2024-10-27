<?php
/**
* ----------------------------------------------------------------------------------------------------------------------
*  WP Admin Plugin Properties Page
* ----------------------------------------------------------------------------------------------------------------------
*/

global $wpdb;

$MLSID = 0;	
$mlsid_listing = array();	
$mlsid_photos = array();

$wp_plugin_aem_params = wp_plugin_aem_params();

$excluded_fields = array();
$excluded_fields[] = 'id';
$excluded_fields[] = 'Title';
$excluded_fields[] = 'date_added';
$excluded_fields[] = 'date_updated';
$excluded_fields[] = 'URL';
$excluded_fields[] = 'DefaultImageURL';
$excluded_fields[] = 'DefaultThumbnailURL';
$excluded_fields[] = 'PropertyType';
$excluded_fields[] = 'ListingDate';
$excluded_fields[] = 'Description';
$excluded_fields[] = 'Bedrooms';
$excluded_fields[] = 'Bathrooms';
$excluded_fields[] = 'full_property_details';
$excluded_fields[] = 'Comment';

//get table info
$sql = "SHOW COLUMNS FROM ".AEM_PLUGIN_DB_Table;
$columns = $wpdb->get_results($sql, ARRAY_A);

if(isset($_POST['id']) && $_POST['action']=='delete'){		
	
	$total_deleted = 0;
	
	foreach($_POST['id'] as $key => $value) {
		
		$delete_mlsid = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$value." LIMIT 1", ARRAY_A);
		if(count($delete_mlsid ) > 0) {
			
			$selected_mlsid_before = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$value." LIMIT 1", ARRAY_A);
			
			$sql = "DELETE FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$value." LIMIT 1";
			$wpdb->query($sql);
			
			$selected_mlsid_after = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$value." LIMIT 1", ARRAY_A);
			
			$total_deleted = ( $total_deleted + ( count($selected_mlsid_before) - count($selected_mlsid_after) ) );
	
		}
		
	}
	
	if($total_deleted > 0) {
		echo '<div id="message" class="updated fade"><p><strong>';
		echo $total_deleted.' Agents Deleted Successfully.';
		echo '</strong></p></div>';
	} else {
		/*
		echo '<div id="message" class="updated fade"><p><strong>';
		echo 'Failed Deleting Agent.';
		echo '</strong></p></div>';
		*/
	}	
		
}
		
if(isset($_GET['id']) && $_GET['action']=='delete'){		
	
	$delete_mlsid = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$_GET['id']." LIMIT 1", ARRAY_A);
	
	if(count($delete_mlsid ) > 0) {
		
		$sql = "DELETE FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$_GET['id']." LIMIT 1";
		$wpdb->query($sql);
		
		$delete_mlsid = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = ".$_GET['id']." LIMIT 1", ARRAY_A);
		if(count($delete_mlsid ) <= 0) {
					
			echo '<div id="message" class="updated fade"><p><strong>';
			echo 'Properties Deleted Successfully.';
			echo '</strong></p></div>';

		} else {
			echo '<div id="message" class="updated fade"><p><strong>';
			echo 'Failed Deleting Property.';
			echo '</strong></p></div>';
		}	
					
	}
	
}

if($_POST['action'] == 'sort') {

	$_SESSION['search_mlsid']['where'] = $_POST['where'];
	$_SESSION['search_mlsid']['operator'] = $_POST['operator'];
	$_SESSION['search_mlsid']['value'] = $_POST['value'];
	$_SESSION['search_mlsid']['sortBy'] = $_POST['sortBy'];
	$_SESSION['search_mlsid']['ascdesc'] = $_POST['ascdesc'];
   
}

if(!isset($_SESSION['search_mlsid']['where']) || empty($_SESSION['search_mlsid']['where'])) {
	$_SESSION['search_mlsid']['where'] = "none";
}

if(!isset($_SESSION['search_mlsid']['operator']) || empty($_SESSION['search_mlsid']['operator'])) {
	$_SESSION['search_mlsid']['operator'] = "";
}

if(!isset($_SESSION['search_mlsid']['value']) || empty($_SESSION['search_mlsid']['value'])) {
	$_SESSION['search_mlsid']['value'] = "";
}

if(!isset($_SESSION['search_mlsid']['sortBy']) || empty($_SESSION['search_mlsid']['sortBy'])) {
	$_SESSION['search_mlsid']['sortBy'] = "MLS";
}

if(!isset($_SESSION['search_mlsid']['ascdesc']) || empty($_SESSION['search_mlsid']['ascdesc'])) {
	$_SESSION['search_mlsid']['ascdesc'] = "ASC";
}

$sql = "SELECT * FROM ".AEM_PLUGIN_DB_Table;

if($_SESSION['search_mlsid']['where'] != 'none'){
	
	if($_SESSION['search_mlsid']['operator'] == "LIKE" || $_SESSION['search_mlsid']['operator'] == "NOT LIKE") {
		$sql .= " WHERE ".$_SESSION['search_mlsid']['where'].' '.$_SESSION['search_mlsid']['operator']." '%".$_SESSION['search_mlsid']['value']."%' ";
	} else {
		$sql .= " WHERE ".$_SESSION['search_mlsid']['where'].' '.$_SESSION['search_mlsid']['operator']." '".$_SESSION['search_mlsid']['value']."' ";
	}

}

$sql .= " ORDER BY ".$_SESSION['search_mlsid']['sortBy']." ".$_SESSION['search_mlsid']['ascdesc'];
#echo $sql;

// set pagination setting
$frontText 		= "";
$limit 			= 50;
$adjacents 		= 1;
$targetpage 	= "admin.php?page=agenteasy-properties/properties.php";
$pagestring 	= "&pg=";
$mlsid_list		= $wpdb->get_results($sql, ARRAY_A);
$total_mlsid 	= count($mlsid_list);

// get current page 
if($_GET['pg'] > 0) {
	$pg = $_GET['pg']; // get page from the url parameter ( eg. ?pg=2 )
} else {
	$pg = 1; // default = page 1
}

// check if current page value is greater than 1 (page 2 and above)
if($pg > 1) {
	$offset = (($pg - 1) * $limit);// set the start number of displaying records
} else {
	$offset = 0; // default: page 1 will start displaying records from record 1
}
			
// pagination >> get & set the listings that will be lists
$rec_min = ($offset);
if($total_mlsid > $limit) {		 
	$rec_max = ($offset) + $limit;
} else {
	$rec_max = $total_mlsid;
}
$n = $rec_min; // counter

?>

<script type="text/javascript" language="javascript">  
function really() {
    var x=window.confirm("Do you really want to delete these mlsid?")
    if(x)
        return true
    else
        return false
}
// function cadged from http://www.hscripts.com/scripts/JavaScript/select-all-checkbox.php
checked = false;
function checkedAll(form_mlsid) {
    var aa= document.getElementById('form_mlsid');
    if(checked == false) {
        checked = true
    }  else {
        checked = false
    }
    for(var i =0; i < aa.elements.length; i++) {
        aa.elements[i].checked = checked;
    }
}
</script>

<style type="text/css">
.property_status {
 	color: #FFFFFF;
    font-size: 9px;
    margin: 0;
    padding: 0 3px;
    text-transform: uppercase;
}

.status_sold {
	background: none repeat scroll 0 0 #8B461F;
}

.status_active {
	background: none repeat scroll 0 0 #7FAF1B;
}

</style>

<div class="wrap">

    <div class="icon32" id="icon-edit"><br></div>            
  
    <h2>
        Properties
        <a class="button add-new-h2" href="admin.php?page=agenteasy-properties/add-property.php">Add New Property</a> 
    </h2>

    <br/>
        
     <table cellspacing="0" class="widefat">
        <tr class="alternate">
            <th scope="row" style="background:#D7D7D7; font-size:14px;">
                Search Property
            </th>
        </tr>
        <tr class="alternate">
            <td>
                <form id="form_mlsid_search" name="form_mlsid_search" method="post">
                    <input type="hidden" name="action" value="sort">
                    Select WHERE:
                    <select name="where">
                        <option value="none"></option>
						<?php foreach($columns as $column) { ?>
                            <?php if(!in_array($column['Field'], $excluded_fields)) { ?>
                                <option value="<?php echo $column['Field']; ?>" <?php if( $column['Field'] == $_SESSION['search_mlsid']['where'] ) { echo ' selected="selected"'; } ?>>
                                    <?php echo aem_properties_field_name($column['Field']); ?>
                                </option>
                            <?php }	?>		
                        <?php }	?>		
                    </select>
                    <select name="operator">
                        <option value="=" <?php if( $_SESSION['search_mlsid']['operator'] == "=" ) { echo ' selected="selected"'; } ?>>=</option>
                        <option value="!=" <?php if( $_SESSION['search_mlsid']['operator'] == "!=" ) { echo ' selected="selected"'; } ?>>!=</option>
                        <option value="LIKE" <?php if( $_SESSION['search_mlsid']['operator'] == "LIKE" ) { echo ' selected="selected"'; } ?>>LIKE</option>
                        <option value="NOT LIKE" <?php if( $_SESSION['search_mlsid']['operator'] == "NOT LIKE" ) { echo ' selected="selected"'; } ?>>NOT LIKE</option>
                    </select>
                    <input type="text" name="value" value="<?php echo $_SESSION['search_mlsid']['value']; ?>" style="width:500px;" />
                    Sort by:
                    <select name="sortBy">
						<?php foreach($columns as $column) { ?>
                            <?php if(!in_array($column['Field'], $excluded_fields)) {?>
                                <option value="<?php echo $column['Field']; ?>" <?php if( $column['Field'] == $_SESSION['search_mlsid']['sortBy'] ) { echo ' selected="selected"'; } ?>>
                                    <?php echo aem_properties_field_name($column['Field']); ?>
                                </option>
                            <?php }	?>		
                        <?php }	?>		
                    </select>
                    <input type="radio" name="ascdesc" value="asc"<?php if(!isset($_SESSION['search_mlsid']['ascdesc']) || $_SESSION['search_mlsid']['ascdesc']=='asc') echo ' checked="checked"'; ?>> Ascending 
                    <input type="radio" name="ascdesc" value="desc"<?php if($_SESSION['search_mlsid']['ascdesc']=='desc') echo 'checked="checked"'; ?>> Descending 
                    <input type="submit" name="Search" value="Search" />
                </form>
            </td>
        </tr>
    </table>          
    
   	<form id="form_mlsid" name="form_mlsid" action="" method="post">
        <input type="hidden" name="action" value="delete">
                        
        <?php if($total_mlsid > 0) { ?>
        	<br/>
            <div class="tablenav">
                <div class="alignleft actions">
                    <input type="submit" name="submit" value="Delete Selected/Checked" title="Delete All Selected/Checked" onclick="return really();" class="button-secondary action" id="doaction2" />
                </div>
                <div class="tablenav-pages">
                    <span class="displaying-num">Displaying <?php echo $rec_min+1; ?>&ndash;<?php if($rec_max > $total_mlsid) { echo $total_mlsid; } else { echo $rec_max; } ?> of <?php echo $total_mlsid; ?></span>
                    <?php echo aem_properties_getPaginationString($frontText, $pg, $total_mlsid, $limit, $adjacents, $targetpage, $pagestring); ?>  
                    <div class="alignleft actions">
                        &nbsp;<br class="clear">
                    </div>
                    <br class="clear">
                </div>
            </div>
        <?php } ?>
        
        <table cellspacing="0" class="widefat" style="font-size:10px;">
            
            <?php if($total_mlsid > 0) { ?>
                            
                    <th scope="row" style="background:#D7D7D7; text-align:center; padding-left:0px; padding-right:0px;" width="2%"><input type="checkbox" name="checkall" onclick="checkedAll(form_mlsid);" title="Select All" /></th>
                    <th scope="row" style="background:#D7D7D7;" width="3%">#</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="5%">Photo</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="7%">MLS</th>
                    <th scope="row" style="background:#D7D7D7;">Address</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="9%">ListingPrice</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="9%">SellingPrice</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="9%">SoldDate</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="9%">Represented</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="10%">Status</th>
                    <th scope="row" style="background:#D7D7D7; text-align:center;" width="5%">Action</th>
                </tr>
                
                <?php while($n < $rec_max) { ?> 
                    
                    <?php $value = $mlsid_list[$n]; ?>
                    
                    <?php $n++; ?>
                    
                   <?php  if($value['id'] > 0) {?>
        
                        <tr class="alternate">
                            <th scope="row" style="background:none; text-align:center; font-weight:normal; padding-left:0px; padding-right:0px;">
                                <input type="checkbox" name="id[]" value="<?php echo $value['id']; ?>">
                            </th>
                            <th scope="row" style="background:none; font-size:10px; font-weight:normal; color:#999999; font-family: Verdana, Arial, Helvetica, sans-serif;">
                               <?php echo $n; ?>.
                            </th>
                            <th scope="row" style="background:none; text-align:center; font-weight:normal;">
                                <?php if($value['DefaultThumbnailURL'] != '') { ?>
                                    <img src="<?php echo $value['DefaultThumbnailURL']; ?>" style="max-height:50px; max-width:50px; border:1px solid #CCCCCC; background-color:#CCCCCC;" />  
                            	<?php } else { ?>        
                                    <img src="<?php echo AEM_PLUGIN_URL; ?>/images/no_image_available.jpg" style="max-height:50px; max-width:50px; border:1px solid #CCCCCC; background-color:#CCCCCC;" />  
                            	<?php } ?>
                            </th>
                            <th scope="row" style="background:none; text-align:center;font-weight:normal;">
                                <?php echo $value['MLS']; ?> 
                            </th>
                           	<th scope="row" style="background:none; font-weight:normal;">
                                <a style="text-decoration:none;" target="_blank" href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $value['MLS']; ?>/<?php echo urlencode($value['Address']); ?>/">
									<?php echo $value['Address']; ?> 
                            	</a>
                            </th>
                            <th scope="row" style="background:none; font-weight:normal; text-align:center;">
                                $<?php echo number_format($value['ListingPrice']); ?> 
                            </th>
                            <th scope="row" style="background:none; font-weight:normal; text-align:center;">
								<?php if($value['SellingPrice'] != '') { ?>
									<?php echo '$'.number_format($value['SellingPrice']); ?>  
								<?php } else { ?>
									<span style="color:#999999;">-----</span>
								<?php } ?>
                            </th>
                            <th scope="row" style="background:none; font-weight:normal; text-align:center;">
								<?php if($value['SoldDate'] != '') { ?>
									<?php echo aem_formatDate($dateFormat="Y-m-d", $value['SoldDate']); ?>
								<?php } else { ?>
									<span style="color:#999999;">-----</span>
								<?php } ?>
                            </th>
                           	<th scope="row" style="background:none; font-weight:normal; text-align:center;">
                                <?php echo $value['Represented']; ?> 
                            </th>
                            <th scope="row" style="background:none; font-size:11px; font-weight:normal; text-align:center;">
								<?php echo $value['Status']; ?>
								<?php /*?>
								<?php if($value['Status'] == "Sold") { ?>
                                	<div class="property_status status_sold">
										<?php echo $value['Status']; ?>
                                    </div>
                                <?php } else { ?>
                                	<div class="property_status status_active">
										<?php echo $value['Status']; ?>
                                    </div>
                                <?php } ?>
                                <?php */?>
                            </th>
                            <th scope="row" style="background:none; font-weight:normal; text-align:center;">
                                <a style="text-decoration:none;" target="_blank" href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $value['MLS']; ?>/<?php echo urlencode($value['Address']); ?>/">
                                    <img src="<?php echo AEM_PLUGIN_URL; ?>/images/view.jpg" alt="[view]" title="View" border="0" />
                                </a> 
                                <a style="text-decoration:none;" href="admin.php?page=agenteasy-properties/edit-property.php&id=<?php echo $value['id']; ?>">
                                    <img src="<?php echo AEM_PLUGIN_URL; ?>/images/edit.png" alt="[edit]" title="Edit" border="0" />
                                </a> 
                                <a style="text-decoration:none; cursor:pointer;" onclick="javascript: if(confirm('Delete Property #<?php echo $value['MLS']; ?>?')) { location.href='admin.php?page=agenteasy-properties/properties.php&action=delete&id=<?php echo $value['id']; ?>'; }">
                                    <img src="<?php echo AEM_PLUGIN_URL; ?>/images/delete.gif" alt="[delete]" title="Delete" border="0" /> 
                                </a> 
                            </th>
                        </tr>
                       

                    <?php } ?>
                
                <?php } ?>
                
            <?php } else  {?>
            
                <tr class="alternate">
                    <td style="padding:10px; color:#999999;">No Property Found.</td>
                </tr>
                
            <?php } ?>
                    
        </table>   
        
        <?php if($total_mlsid > 0) { ?>
            <div class="tablenav">
                <div class="tablenav-pages">
                    <span class="displaying-num">Displaying <?php echo $rec_min+1; ?>&ndash;<?php if($rec_max > $total_mlsid) { echo $total_mlsid; } else { echo $rec_max; } ?> of <?php echo $total_mlsid; ?></span>
                    <?php echo aem_properties_getPaginationString($frontText, $pg, $total_mlsid, $limit, $adjacents, $targetpage, $pagestring); ?>  
                    <div class="alignleft actions">
                        &nbsp;<br class="clear">
                    </div>
                    <br class="clear">
                </div>
            </div>
        <?php } ?>

		<?php /*?>
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
            <tr>
                <td style="padding:10px; font-size:10px;">
                    <pre><?php print_r($mlsid_list); ?></pre>
                </td>
            </tr> 
        </table>                
        <?php */?>

    </form>
           
    <p>&nbsp;</p>

    <table cellspacing="0" class="widefat">
        <tr class="alternate">
            <th scope="row" style="background:#D7D7D7; font-size:14px;" width="25%">Shortcodes</th>
            <th scope="row" style="background:#D7D7D7; font-size:14px;">Description</th>
        </tr>
        <tr class="alternate">
            <td>
                <?php echo AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES; ?>
            </td>
            <td>
                 When the Agent MLSID is supplied in the <a href="admin.php?page=agenteasy-properties" style="text-decoration:none;">Plugin Settings</a> and the Shortcode is on a page, it will display the auto feed of the Agents Active Listings.
            </td>
        </tr>
        <tr class="alternate">
            <td>
                <?php echo AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES; ?>
            </td>
            <td>
                 When the Agent MLSID is supplied in the <a href="admin.php?page=agenteasy-properties" style="text-decoration:none;">Plugin Settings</a> and the Shortcode is on a page, it will display the auto feed of the Agents Sold Listings.
            </td>
        </tr>
    </table>

    <p>&nbsp;</p>
    
</div>


<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Misc Functions
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function aem_properties_field_name($field) {
		
		$name = '';
		$words = explode("_", $field);
		
		foreach($words as $word) {
			$name .= $word.' ';
		}
		
		return ucwords($name);
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
*  Pagination string
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function aem_properties_getPaginationString($frontText = "", $page = 1, $totalitems, $limit = 10, $adjacents = 1, $targetpage = "/", $pagestring = "&pg=") {		
		
		//defaults
		if(!$adjacents) $adjacents = 1;
		if(!$limit) $limit = 10;
		if(!$page) $page = 1;
		if(!$targetpage) $targetpage = "/";
		
		//other vars
		$prev = $page - 1;									//previous page is page - 1
		$next = $page + 1;									//next page is page + 1
		$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
		$lpm1 = $lastpage - 1;								//last page minus 1
		
		// Now we apply our rules and draw the pagination object. We're actually saving the code to a variable in case we want to draw it more than once.
		$pagination = "";
		if($lastpage > 1){	
		
			$pagination .= "";
			
			#$pagination .= "<span id=\"pagination-title\">$frontText</span>";
	
			//previous button
			if($page > 1) {
				$pagination .= "&nbsp;&nbsp;<a href=\"$targetpage$pagestring$prev\" class=\"prev page-numbers\">&laquo; PREV</a>&nbsp;&nbsp;";
			} else {
				//$pagination .= "<span class=\"disabled\">&laquo; prev</span>";	
			}
			
			//pages	
			if($lastpage < 7 +($adjacents * 2)) {	//not enough pages to bother breaking it up
				
				for($counter = 1; $counter <= $lastpage; $counter++)
				{
					if($counter == $page) {
						$pagination .= "<span class=\"page-numbers current\">$counter</span>";
					} else {
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" class=\"page-numbers\">$counter</a>";					
					}
				}
				
			} elseif($lastpage >= 7 +($adjacents * 2)) { //enough pages to hide some	
				
				//close to beginning; only hide later pages
				if($page < 1 +($adjacents * 3))		
				{
					for($counter = 1; $counter < 4 +($adjacents * 2); $counter++)
					{
						if($counter == $page) {
							$pagination .= "<span class=\"page-numbers current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" class=\"page-numbers\">$counter</a>";					
						}
					}
					$pagination .= "<span class=\"elipses\">....</span>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\" class=\"page-numbers\">$lpm1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\" class=\"page-numbers\">$lastpage</a>";		
				
				//in middle; hide some front and some back
				} elseif($lastpage -($adjacents * 2) > $page && $page >($adjacents * 2)) {
				
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\" class=\"page-numbers\">1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\" class=\"page-numbers\">2</a>";
					$pagination .= "<span class=\"elipses\">...</span>";
					for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if($counter == $page) {
							$pagination .= "<span class=\"page-numbers current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" class=\"page-numbers\">$counter</a>";					
						}
					}
					$pagination .= "...";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\" class=\"page-numbers\">$lpm1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\" class=\"page-numbers\">$lastpage</a>";		
				
				//close to end; only hide early pages
				} else {
				
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\" class=\"page-numbers\">1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\" class=\"page-numbers\">2</a>";
					$pagination .= "<span class=\"elipses\">....</span>";
					for($counter = $lastpage -(1 +($adjacents * 3)); $counter <= $lastpage; $counter++)
					{
						if($counter == $page) {
							$pagination .= "<span class=\"page-numbers current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" class=\"page-numbers\">$counter</a>";					
						}
					}
				}
			}
			
			//next button
			if($page < $counter - 1) {
				$pagination .= "&nbsp;&nbsp;<a href=\"" . $targetpage . $pagestring . $next . "\" class=\"next page-numbers\">NEXT &raquo;</a>";
			} else {
				//$pagination .= "<span class=\"disabled\">next &raquo;</span>";
			}
			
			$pagination .= "";
		}
		
		return $pagination;
	
	}
?>