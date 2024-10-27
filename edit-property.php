<?php
/**
* ----------------------------------------------------------------------------------------------------------------------
*  WP Admin Plugin Properties Page
* ----------------------------------------------------------------------------------------------------------------------
*/


	$MLSID = 0;	
	$mlsid_listing = array();	
	
	$wp_plugin_aem_params = wp_plugin_aem_params();
	$wp_aem_properties_id = 0;
	
	if($_GET['id'] > 0) {
		$wp_aem_properties_ids = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = '".$_GET['id']."' LIMIT 1", ARRAY_A);
		if(count($wp_aem_properties_ids) > 0) {
			$wp_aem_properties_id = $wp_aem_properties_ids[0]['id'];
		}
	}
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Process >> Add Property
* ----------------------------------------------------------------------------------------------------------------------
*/

	if($_POST['action'] == 'update' && $_POST['wp_aem_properties_id'] > 0 || isset($_POST['submit_add_photos'])) {
			
		$error = 0;
		
		if(!isset($_POST['Listing']['MLS']['value']) || empty($_POST['Listing']['MLS']['value']) || $_POST['Listing']['MLS']['value'] <= 0) {
			//$error++;
		}
		
		if(!isset($_POST['Listing']['Address']['value']) || empty($_POST['Listing']['Address']['value'])) {
			$error++;
		}

		// check if no duplicate on the database
		$add_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id !=  '".$_POST['wp_aem_properties_id']."' AND MLS = '".$_POST['Listing']['MLS']['value']."' AND address = '".$_POST['Listing']['Address']['value']."' LIMIT 1", ARRAY_A);
		if(count($add_mlsid_listings) > 0) { $error++; }
		
		//echo '<pre>'; print_r($add_mlsid_listings); echo '</pre>'; exit();
		
		if($error == 0) {
					
			// set & clean the POST_Listing details
			$_POST_Listing = $_POST['Listing'];
			$_POST_Listing['MLS']['value'] 										= stripslashes($_POST['Listing']['MLS']['value']);
			$_POST_Listing['Title']['value'] 									= stripslashes($_POST['Listing']['Title']['value']);
			$_POST_Listing['Address']['value'] 									= stripslashes($_POST['Listing']['Address']['value']);
			$_POST_Listing['Bedrooms']['value'] 								= stripslashes($_POST['Listing']['Bedrooms']['value']);
			$_POST_Listing['Bathrooms']['value'] 								= stripslashes($_POST['Listing']['Bathrooms']['value']);
			$_POST_Listing['PropertyType']['value'] 							= stripslashes($_POST['Listing']['PropertyType']['value']);
			$_POST_Listing['Neighborhood']['value'] 							= stripslashes($_POST['Listing']['Neighborhood']['value']);
			$_POST_Listing['Description']['value'] 								= stripslashes($_POST['content']);
			$_POST_Listing['ListingPrice']['value'] 							= stripslashes($_POST['Listing']['ListingPrice']['value']);
			$_POST_Listing['SellingPrice']['value'] 							= stripslashes($_POST['Listing']['SellingPrice']['value']);
			$_POST_Listing['SoldDate']['value'] 								= stripslashes($_POST['Listing']['SoldDate']['value']);
			$_POST_Listing['ListingAgent']['value'] 							= stripslashes($_POST['Listing']['ListingAgent']['value']);
			$_POST_Listing['ListingOffice']['value']							= stripslashes($_POST['Listing']['ListingOffice']['value']);
			$_POST_Listing['Status']['value'] 									= stripslashes($_POST['Listing']['Status']['value']);
			$_POST_Listing['PrimaryDetails']['CrossStreet']['value'] 			= stripslashes($_POST['Listing']['PrimaryDetails']['CrossStreet']['value']);
			$_POST_Listing['PrimaryDetails']['ApproximateSqFt']['value'] 		= stripslashes($_POST['Listing']['PrimaryDetails']['ApproximateSqFt']['value']);
			$_POST_Listing['PrimaryDetails']['PricePerSqFt']['value'] 			= stripslashes($_POST['Listing']['PrimaryDetails']['PricePerSqFt']['value']);
			$_POST_Listing['PrimaryDetails']['YearBuilt']['value'] 				= stripslashes($_POST['Listing']['PrimaryDetails']['YearBuilt']['value']);
			$_POST_Listing['PrimaryDetails']['TotalRooms']['value'] 			= stripslashes($_POST['Listing']['PrimaryDetails']['TotalRooms']['value']);
			$_POST_Listing['PrimaryDetails']['HOADues']['value'] 				= stripslashes($_POST['Listing']['PrimaryDetails']['HOADues']['value']);
			$_POST_Listing['AdditionalDetails']['Parking']['value'] 			= stripslashes($_POST['Listing']['AdditionalDetails']['Parking']['value']);
			$_POST_Listing['AdditionalDetails']['Type']['value']				= stripslashes($_POST['Listing']['AdditionalDetails']['Type']['value']);
			$_POST_Listing['AdditionalDetails']['Style']['value'] 				= stripslashes($_POST['Listing']['AdditionalDetails']['Style']['value']);
			$_POST_Listing['AdditionalDetails']['Floors']['value'] 				= stripslashes($_POST['Listing']['AdditionalDetails']['Floors']['value']);
			$_POST_Listing['AdditionalDetails']['BathTypeIncludes']['value']	= stripslashes($_POST['Listing']['AdditionalDetails']['BathTypeIncludes']['value']);
			$_POST_Listing['AdditionalDetails']['Kitchen']['value'] 			= stripslashes($_POST['Listing']['AdditionalDetails']['Kitchen']['value']);
			$_POST_Listing['AdditionalDetails']['DiningRoom']['value'] 			= stripslashes($_POST['Listing']['AdditionalDetails']['DiningRoom']['value']);
			$_POST_Listing['AdditionalDetails']['LivingRoom']['value'] 			= stripslashes($_POST['Listing']['AdditionalDetails']['LivingRoom']['value']);
			$_POST_Listing['AdditionalDetails']['HeatingCoolingSystem']['value']= stripslashes($_POST['Listing']['AdditionalDetails']['HeatingCoolingSystem']['value']);
			$_POST_Listing['AdditionalDetails']['LaundryAppliances']['value'] 	= stripslashes($_POST['Listing']['AdditionalDetails']['LaundryAppliances']['value']);
			$_POST_Listing['AdditionalDetails']['SpecialFeatures']['value'] 	= stripslashes($_POST['Listing']['AdditionalDetails']['SpecialFeatures']['value']);
			$_POST_Listing['AdditionalDetails']['CommonAreas']['value'] 		= stripslashes($_POST['Listing']['AdditionalDetails']['CommonAreas']['value']);
			$_POST_Listing['AdditionalDetails']['Transportation']['value'] 		= stripslashes($_POST['Listing']['AdditionalDetails']['Transportation']['value']);
			$_POST_Listing['AdditionalDetails']['Shopping']['value'] 			= stripslashes($_POST['Listing']['AdditionalDetails']['Shopping']['value']);
			$_POST_Listing['Comment']['value'] 									= stripslashes($_POST['Listing']['Comment']['value']);

			#echo '<pre>'; print_r($_POST_Listing); echo '</pre>'; exit(); // for testing only
			
			$removePhotos = $_POST['removedPhoto'];
			$_POST_Listing['Photos']['Photo'] = array();
			
			if(count($_POST['Listing']['Photos']['Photo']) > 0) {
				foreach($_POST['Listing']['Photos']['Photo'] as $listing_photos_key => $listing_photos_val) {
					#echo "<br/>listing_photos_key: $listing_photos_key = ".$_POST['removedPhoto'][$listing_photos_key];
					if(isset($_POST['removedPhoto'][$listing_photos_key])) {
						// don't add photo
					} else {
						$_POST['Listing']['Photos']['Photo'][$listing_photos_key]['ThumbnailURL'] = $_POST['Listing']['Photos']['Photo'][$listing_photos_key]['URL'];
						$_POST_Listing['Photos']['Photo'][] = $_POST['Listing']['Photos']['Photo'][$listing_photos_key];
					}	
				}
			}
			
			if(count($_POST['images']) > 0) {
				foreach($_POST['images'] as $image_url) {
					if($image_url!= "") {
						$_POST_Listing['Photos']['Photo'][] = array('URL' => array('value' => $image_url), 'ThumbnailURL' => array('value' => $image_url));;
					}
				}
			}
			
			#echo "<pre>"; print_r($_POST_Listing); echo "</pre>"; exit();
			
			$POST_Listing =  base64_encode(serialize($_POST_Listing)); 
			if($POST_Listing == '') { $error++; }
		
			if($error == 0) {
		
				$sql = "UPDATE ".AEM_PLUGIN_DB_Table." SET
						MLS 					= '".aem_clean($_POST['Listing']['MLS']['value'])."',
						Title 					= '".aem_clean($_POST['Listing']['Title']['value'])."',
						URL						= '".aem_clean($_POST['Listing']['URL']['value'])."',
						DefaultImageURL 		= '".aem_clean($_POST['Listing']['Photos']['Photo'][$_POST['DefaultImage']]['URL']['value'])."',
						DefaultThumbnailURL 	= '".aem_clean($_POST['Listing']['Photos']['Photo'][$_POST['DefaultImage']]['ThumbnailURL']['value'])."',
						PropertyType 			= '".aem_clean($_POST['Listing']['PropertyType']['value'])."',
						Address 				= '".aem_clean($_POST['Listing']['Address']['value'])."',
						Bedrooms 				= '".aem_clean($_POST['Listing']['Bedrooms']['value'])."',
						Bathrooms 				= '".aem_clean($_POST['Listing']['Bathrooms']['value'])."',
						ListingPrice 			= '".aem_clean($_POST['Listing']['ListingPrice']['value'])."',
						ListingDate 			= '".aem_clean($_POST['Listing']['ListingDate']['value'])."',
						SellingPrice 			= '".aem_clean($_POST['Listing']['SellingPrice']['value'])."',
						SoldDate 				= '".aem_clean($_POST['Listing']['SoldDate']['value'])."',
						Status 					= '".aem_clean($_POST['Listing']['Status']['value'])."',
						Description				= '".aem_clean($_POST['content'])."',
						ListingAgent 			= '".aem_clean($_POST['Listing']['ListingAgent']['value'])."',
						ListingOffice 			= '".aem_clean($_POST['Listing']['ListingOffice']['value'])."',
						Represented 			= '".aem_clean($_POST['Listing']['Represented']['value'])."',
						Comment 				= '".aem_clean($_POST['Listing']['Comment']['value'])."',
						full_property_details 	= '".$POST_Listing."',
						date_updated 		  	= CURDATE()
						WHERE id 				= '".aem_clean($_POST['wp_aem_properties_id'])."'
						LIMIT 1";
		
				$update = $wpdb->query($sql);
				
				#echo '<pre>'; print_r($_POST_Listing); echo '</pre>'; 
				#echo '<hr/>SQL: '.$sql.'<hr/>';
				//exit();
			
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Property Successfully Updated.';
				echo '</strong></p></div>';
				
			} else {
				
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Failed Updating Property.';
				echo '</strong></p></div>';
				
			}
		
		} else {
			
			if(count($add_mlsid_listings) > 0) {
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Property already exists. <a href="admin.php?page=agenteasy-properties/edit-property.php&id='.$add_mlsid_listings[0]['id'].'" style="text-decoration:none;">[view property]</a>';
				echo '</strong></p></div>';
			}
	
			echo '<div id="message" class="updated fade"><p><strong>';
			echo 'Failed Updating Property.';
			echo '</strong></p></div>';
			
		}
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Process >> Get Property
* ----------------------------------------------------------------------------------------------------------------------
*/	

	if($wp_aem_properties_id > 0) {
		
		$sql = "SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = '".$wp_aem_properties_id."' LIMIT 1";
		#echo $sql;
		
		$mlsid_listings = $wpdb->get_results($sql, ARRAY_A);
		#echo "<pre>"; print_r($mlsid_listings); echo "<pre>";
	
		$mlsid_listing_db = unserialize(base64_decode($mlsid_listings[0]['full_property_details']));
		$MLSID = $mlsid_listings[0]['MLS'];	
		
		$mlsid_listing = unserialize(base64_decode($mlsid_listings[0]['full_property_details']));
		#echo "<pre>"; print_r($mlsid_listing); echo "</pre>";
		
	}
	
	if(isset($_POST['submit_get_mlsid_details']) && $_POST['MLSID'] > 0) {
	
		// set & parse the xml 
		$xml_query_url = "property?mlsid=".$_POST['MLSID'];
		$xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].urlencode($xml_query_url.'&apikey='.$wp_plugin_aem_params['plugin_aem_option_api_key']);
		
		// open the xml url
		if ($f = @fopen($xml_url, 'r')) {
		
			$xml = '';
		
			while (!feof($f)) {
				$xml .= fgets($f, 4096);
			}
			
			fclose($f);
		
			$arr = xml2array($xml);
			//print_r($arr);
		
			if (sizeof($arr) == 1 && $arr["Listing"]){
				
				if (array_key_exists("MLS", $arr["Listing"])) {
					$mlsid_listing = $arr["Listing"];
				} else {
					$mlsid_listing = $arr["Listing"];
				}
				
				#echo "<pre>"; print_r($mlsid_listing); echo "</pre>";
			
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Property details successfully retrieved. Click the \'Update\' button to save & update the property details.';
				echo '</strong></p></div>';
				
			} else {
			
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Failed retrieving property details. MLSID doesn\'t exists';
				echo '</strong></p></div>';
			}
			
		}
		
	}
	
	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Set Form fields & the property details
* ----------------------------------------------------------------------------------------------------------------------
*/

	if($mlsid_listing['Represented']['value'] == "") {
		$tmp_ml['Represented']['value'] 	= "Both";
	} else {	
		$tmp_ml['Represented']['value'] 	= $mlsid_listing['Represented']['value'];
	}
	
	$tmp_ml['MLS']['value'] 			= $mlsid_listing['MLS']['value'];
	$tmp_ml['Title']['value'] 			= $mlsid_listing['Title']['value'];
	$tmp_ml['Address']['value'] 		= $mlsid_listing['Address']['value'];
	$tmp_ml['Bedrooms']['value'] 		= $mlsid_listing['Bedrooms']['value'];
	$tmp_ml['Bathrooms']['value'] 		= $mlsid_listing['Bathrooms']['value'];
	$tmp_ml['PropertyType']['value'] 	= $mlsid_listing['PropertyType']['value'];
	$tmp_ml['Neighborhood']['value'] 	= $mlsid_listing['Neighborhood']['value'];
	$tmp_ml['Description']['value'] 	= $mlsid_listing['Description']['value'];
	$tmp_ml['ListingPrice']['value'] 	= $mlsid_listing['ListingPrice']['value'];
	$tmp_ml['SellingPrice']['value'] 	= $mlsid_listing['SellingPrice']['value'];
	$tmp_ml['SoldDate']['value'] 		= $mlsid_listing['SoldDate']['value'];
	$tmp_ml['ListingAgent']['value'] 	= $mlsid_listing['ListingAgent']['value'];
	$tmp_ml['ListingOffice']['value']	= $mlsid_listing['ListingOffice']['value'];
	$tmp_ml['Status']['value'] 			= $mlsid_listing['Status']['value'];

	$tmp_ml['PrimaryDetails']['CrossStreet']['value'] 			= $mlsid_listing['PrimaryDetails']['CrossStreet']['value'];
	$tmp_ml['PrimaryDetails']['ApproximateSqFt']['value'] 		= $mlsid_listing['PrimaryDetails']['ApproximateSqFt']['value'];
	$tmp_ml['PrimaryDetails']['PricePerSqFt']['value'] 			= $mlsid_listing['PrimaryDetails']['PricePerSqFt']['value'];
	$tmp_ml['PrimaryDetails']['YearBuilt']['value'] 			= $mlsid_listing['PrimaryDetails']['YearBuilt']['value'];
	$tmp_ml['PrimaryDetails']['TotalRooms']['value'] 			= $mlsid_listing['PrimaryDetails']['TotalRooms']['value'];
	$tmp_ml['PrimaryDetails']['HOADues']['value'] 				= $mlsid_listing['PrimaryDetails']['HOADues']['value'];
	
	$tmp_ml['AdditionalDetails']['Parking']['value'] 			= $mlsid_listing['AdditionalDetails']['Parking']['value'];
	$tmp_ml['AdditionalDetails']['Type']['value']				= $mlsid_listing['AdditionalDetails']['Type']['value'];
	$tmp_ml['AdditionalDetails']['Style']['value'] 				= $mlsid_listing['AdditionalDetails']['Style']['value'];
	$tmp_ml['AdditionalDetails']['Floors']['value'] 			= $mlsid_listing['AdditionalDetails']['Floors']['value'];
	$tmp_ml['AdditionalDetails']['BathTypeIncludes']['value']	= $mlsid_listing['AdditionalDetails']['BathTypeIncludes']['value'];
	$tmp_ml['AdditionalDetails']['Kitchen']['value'] 			= $mlsid_listing['AdditionalDetails']['Kitchen']['value'];
	$tmp_ml['AdditionalDetails']['DiningRoom']['value'] 		= $mlsid_listing['AdditionalDetails']['DiningRoom']['value'];
	$tmp_ml['AdditionalDetails']['LivingRoom']['value'] 		= $mlsid_listing['AdditionalDetails']['LivingRoom']['value'];
	$tmp_ml['AdditionalDetails']['HeatingCoolingSystem']['value']= $mlsid_listing['AdditionalDetails']['HeatingCoolingSystem']['value'];
	$tmp_ml['AdditionalDetails']['LaundryAppliances']['value'] 	= $mlsid_listing['AdditionalDetails']['LaundryAppliances']['value'];
	$tmp_ml['AdditionalDetails']['SpecialFeatures']['value'] 	= $mlsid_listing['AdditionalDetails']['SpecialFeatures']['value'];
	$tmp_ml['AdditionalDetails']['CommonAreas']['value'] 		= $mlsid_listing['AdditionalDetails']['CommonAreas']['value'];
	$tmp_ml['AdditionalDetails']['Transportation']['value'] 	= $mlsid_listing['AdditionalDetails']['Transportation']['value'];
	$tmp_ml['AdditionalDetails']['Shopping']['value'] 			= $mlsid_listing['AdditionalDetails']['Shopping']['value'];
	$tmp_ml['Comment']['value'] 								= $mlsid_listing['Comment']['value'];

	$tmp_ml['Photos'] = $mlsid_listing['Photos'];
	
	if( ($mlsid_listing['Photos']['Photo'][0]['URL']['value'] == "") || ($mlsid_listing['Photos']['Photo'][0]['URL']['value'] == AEM_PLUGIN_URL."/images/no_image_available.jpg") ) {
		$tmp_ml['Photos']['Photo'][0]['URL']['value'] = AEM_PLUGIN_URL."/images/no_image_available.jpg";
	}
	
	if( ($mlsid_listing['Photos']['Photo'][0]['ThumbnailURL']['value'] == "") || ($mlsid_listing['Photos']['Photo'][0]['ThumbnailURL']['value'] == AEM_PLUGIN_URL."/images/no_image_available.jpg") ) {
		$tmp_ml['Photos']['Photo'][0]['ThumbnailURL']['value'] = AEM_PLUGIN_URL."/images/no_image_available.jpg";
	}
	
	$mlsid_listing = array();
	$mlsid_listing = $tmp_ml;

	// re-query db property details
	if($_GET['id'] > 0) {
		$wp_aem_properties_ids = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = '".$_GET['id']."' LIMIT 1", ARRAY_A);
		if(count($wp_aem_properties_ids) > 0) {
			$wp_aem_properties_id = $wp_aem_properties_ids[0]['id'];
		}
	}
	
	
// =====================================================================================================================
?>

<div class="wrap">

    <div class="icon32" id="icon-edit"><br></div>
    <h2>Edit Property</h2>
    
    <p><a href="admin.php?page=agenteasy-properties/properties.php">&laquo; Back to Properties</a></p>
        
    <br/>

	<?php if($wp_aem_properties_id > 0) { ?>
    
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
            <?php /*?>
            <form name="form_get_mlsid_details" method="post"  action="" />
                <tr class="alternate">
                    <th scope="row" width="15%" style="background:#D7D7D7; font-size:14px; text-align:right;">
                        Property MLSID:
                    </th>
                    <th scope="row"  style="background:#D7D7D7; font-size:14px; text-align:left;">
                        <input type="text" name="MLSID" value="<?php echo $MLSID; ?>" style="border:1px solid #CCCCCC; font-size:14px; padding:3px; height:30px; width:100px; text-align:center;" />
                        <input type="submit" value="Get MLSID Property details from XML" name="submit_get_mlsid_details"  class="button-primary" style="padding:5px;" />
                    </th>
                </tr>
            </form>
            <?php */?>
            <tr class="alternate">
                <th colspan="2" style="background:#D7D7D7; font-size:14px; text-align:left;">
                    Property Details:
                </th>
            </tr>
            <tr class="alternate">
                <td colspan="2" style="padding:10px 0px 0px;">
                
                    <?php if(count($mlsid_listing) > 0) { ?>
                        <form name="form_update" id="form_update" method="post"  action="admin.php?page=agenteasy-properties/edit-property.php&id=<?php echo $_GET['id']; ?>" style="border:none; padding:0px; margin:0px;"/>
                            <input type="hidden" name="wp_aem_properties_id" value="<?php echo $wp_aem_properties_id; ?>">
                            <input type="hidden" name="action" value="update">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                                
                                <?php $Represented = $mlsid_listing['Represented']['value']; ?>
                                <?php foreach($mlsid_listing as $property_key => $property_val) { ?>
                                        
                                    <?php if($property_key == "Represented" || $Represented == "") { ?> 
                                        
                                        <?php 
                                        if(count($mlsid_listing_db) > 0) {
                                            $Represented = $mlsid_listing_db['Represented']['value'];
                                        } else {
                                            $Represented = "Both";
                                        }	
                                        ?>
                                        
                                        <tr>
                                            <td align="right" width="15%"><strong>Represented:</strong></td>
                                            <td>
                                                <input type="radio" name="Listing[Represented][value]" value="Seller" <?php $repval = "Seller"; if($Represented == $repval){ echo 'checked="checked"'; } ?> /> Seller
                                                &nbsp;&nbsp;
                                                <input type="radio" name="Listing[Represented][value]" value="Buyer" <?php $repval = "Buyer"; if($Represented == $repval){ echo 'checked="checked"'; } ?> /> Buyer
                                                &nbsp;&nbsp;
                                                <input type="radio" name="Listing[Represented][value]" value="Both" <?php $repval = "Both"; if($Represented == $repval){ echo 'checked="checked"'; } ?>/> Both
                                            </td>
                                        </tr>
                                        
                                    <?php } elseif($property_key == "PrimaryDetails" || $property_key == "AdditionalDetails") { ?> 
                                        
                                        <?php if(count($mlsid_listing[$property_key]) > 0) {	?>
                                            <tr>
                                                <th colspan="2" style="background-color:#DFDFDF; font-size:14px;">
                                                    <?php echo str_replace(array('PrimaryDetails', 'AdditionalDetails'), array('Primary Details', 'Additional Details'), $property_key); ?>:
                                                </th>
                                            </tr>
                                            <?php foreach($mlsid_listing[$property_key] as $property_PrimaryDetails_key => $property_PrimaryDetails_val) { ?>
                                                <tr>
                                                    <td align="right" width="15%"><strong><?php echo $property_PrimaryDetails_key; ?>:</strong></td>
                                                    <td><input type="text" name="Listing[<?php echo $property_key; ?>][<?php echo $property_PrimaryDetails_key; ?>][value]" value="<?php echo $property_PrimaryDetails_val['value']; ?>" style="width:70%; border:1px solid #CCCCCC;" /></td>
                                                </tr>
                                            <?php } ?>      
                                        <?php } ?>   
                                           
                                    <?php } elseif($property_key == "Description") { ?> 
                                        
                                        <tr>
                                            <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                            <td>
                                                <div id="poststuff">
                                                    <div id="postdivrich">
                                                     <?php the_editor($property_val['value'], $id = 'content', $prev_id = 'Listing['.$property_key.'][value]', $media_buttons = true, $tab_index = 9); ?>
                                                    </div>
                                                </div>
                                                <?php /*?>
                                            	<textarea name="Listing[<?php echo $property_key; ?>][value]" cols="31" rows="10" style="border:1px solid #CCCCCC; width:70%;"><?php echo $property_val['value']; ?></textarea>
                                             	<?php */?>
                                             </td>
                                        </tr>
                                           
                                    <?php } elseif($property_key == "Comment") { ?> 
                                        
                                        <tr>
                                            <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                             <td><textarea name="Listing[<?php echo $property_key; ?>][value]" cols="31" rows="4" style="border:1px solid #CCCCCC; width:70%;"><?php echo $property_val['value']; ?></textarea></td>
                                        </tr>
                                    
                                    <?php } elseif($property_key == "PropertyType") { ?> 
                                       
                                       <?php 
                                        $property_types = array();
                                        $property_types[] = "Single-Family Homes";
                                        $property_types[] = "Condominium";
                                        $property_types[] = "Tenancy In Common";
                                        $property_types[] = "Loft Condominium";
                                        $property_types[] = "2 Units";
                                        $property_types[] = "3 Units";
                                        $property_types[] = "4 Units";
                                        $property_types[] = "Lots & Acreage";
                                       
                                        if($property_val['value'] != "" && !in_array($property_val['value'], $property_types)) {
                                            $property_types[] = $property_val['value'];
                                        }
                                       ?>
                                       
                                        <tr>
                                            <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                            <td>
                                                <?php if(count($property_types) > 0) { ?>
                                                    <select name="Listing[<?php echo $property_key; ?>][value]">
                                                        <?php foreach($property_types as $ptype) { ?>
                                                            <?php 
                                                            if($property_val['value'] == $ptype) { 
                                                                $selected = 'selected="selected"'; 
                                                            } elseif($property_val['value'] == "" && $ptype == "Sold") { 
                                                                $selected = 'selected="selected"'; 
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $ptype; ?>" <?php echo $selected; ?>><?php echo $ptype; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>     
                                                    <input type="text" name="Listing[<?php echo $property_key; ?>][value]" value="<?php echo $property_val['value']; ?>" style="width:70%; border:1px solid #CCCCCC;" />
                                                <?php } ?>	
                                            </td>
                                        </tr>
                                   
                                    <?php } elseif($property_key == "Status") { ?> 
                                       
                                       <?php 
                                       $property_status = array();
                                       $property_status[] = "Active";
                                       $property_status[] = "Act. Cont."; //Active Contingent
                                       $property_status[] = "Pending";
                                       $property_status[] = "Sold";
                                       $property_status[] = "Coming Soon";
                                       $property_status[] = "Withdrawn";
                                       $property_status[] = "Removed";
                                       
                                       if($property_val['value'] != "" && !in_array($property_val['value'], $property_status)) {
                                            $property_status[] = $property_val['value'];
                                       }
                                       ?>
                                       
                                        <tr>
                                            <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                            <td>
                                                <?php if(count($property_status) > 0) { ?>
                                                    <select name="Listing[<?php echo $property_key; ?>][value]">
                                                        <?php foreach($property_status as $pstatus) { ?>
                                                            <?php 
                                                            if($property_val['value'] == $pstatus) { 
                                                                $selected = 'selected="selected"'; 
                                                            } elseif($property_val['value'] == "" && $pstatus == "Sold") { 
                                                                $selected = 'selected="selected"'; 
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $pstatus; ?>" <?php echo $selected; ?>><?php echo $pstatus; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                <?php } else { ?>     
                                                    <input type="text" name="Listing[<?php echo $property_key; ?>][value]" value="<?php echo $property_val['value']; ?>" style="width:70%; border:1px solid #CCCCCC;" />
                                                <?php } ?>	
                                            </td>
                                        </tr>
                                       
                                    <?php } elseif($property_key == "Photos") { ?> 
                                        
                                        <?php // -- nothing to display -- ?>
                                    
                                    <?php } else { ?>  
                                    
                                        <tr>
                                            <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                            <td>
                                            	<input type="text" name="Listing[<?php echo $property_key; ?>][value]" value="<?php echo $property_val['value']; ?>" style="width:70%; border:1px solid #CCCCCC;" />
                                            	<?php if($property_key == "SoldDate") { ?>
                                                    <span style="color:#999999;">YYYY-mm-dd</span>
                                                <?php } ?>
                                                <?php if($property_key == "Address") { ?>
                                                    <span style="color: #993300;">* required</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        
                                    <?php } // end of if elseif else condition ?>
                                    
                                <?php } // end of foreach loop ?>
                        
								<?php 
                                if(@array_key_exists("URL", $mlsid_listing[$property_key]['Photo'])) {
                                    $mlsid_photos = $mlsid_listing[$property_key];
                                } else {
                                    $mlsid_photos = $mlsid_listing[$property_key]['Photo'];
                                }
                                ?>
                                <tr>
                                    <th colspan="2" style="background-color:#DFDFDF; font-size:14px; border-right:1px solid #CCCCCC; vertical-align:middle;">
                                    	<div style="width:233px; float:right; padding:0px; margin:0px;">
											<?php 
                                            // set the plugin & upload dirs
                                            $ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__));
                                            ?>
                                            <script language="JavaScript">
                                            jQuery(document).ready(function() {
                                            
                                                var uploadID = ''; /*setup the var in a global scope*/
                                            
                                                jQuery('.upload-button').click(function() {
                                                    uploadID = jQuery(this).prev('input'); /*set the uploadID variable to the value of the input before the upload button*/
                                                    formfield = jQuery('.upload').attr('name');
                                                    //tb_show('', 'media-upload.php?type=image&amp;tab=library&amp;TB_iframe=true');
													tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                                                    return false;
                                                });
                                                
                                                window.send_to_editor = function(html) {
                                                    
                                                    tb_remove();
                                                    document.getElementById('div_add_photos').innerHTML = html;
                                                    
                                                    jQuery('#div_add_photos img').each(function() {
                                                        
                                                        var imgsrc = jQuery(this).attr('src');
                                                        
                                                        // get the uploaded image data from wp media library
                                                        jQuery.get("<?php echo $ae_fileupload_dir; ?>medialink-property.php?propertyID=<?php echo $_GET['id']; ?>&image=" + imgsrc + "", {"id" : ""}, function(data){
                                                            if(data != '') { // check if data is returned
                                                             
                                                                // increment the photo counter hidden field
                                                                var current_photo_counter = parseInt(jQuery('#photo_counter').val()) + 1; 									
                                                                jQuery('#photo_counter').val(current_photo_counter); 
                                                                
                                                                // append the added image into the Property Photos
                                                                jQuery('#property_photos').append('<div style="float:left; margin:3px; padding:3px; border:1px solid #999999; width:70px; text-align:center; vertical-align:middle; font-size:10px; color:#333333;">' +
                                                                                                        '<a href="' + data + '" target="_blank">' +
                                                                                                            '<img src="' + data + '" alt="" title="" border="0" style="margin:3px; border:2px solid #CCCCCC; width:60px; height:60px;" />' +
                                                                                                        '</a>' +
                                                                                                        '<br/>' +
                                                                                                        '<input type="checkbox" name="removedPhoto[' + current_photo_counter +']" value="' + current_photo_counter + '" /> Remove' +
                                                                                                        '<br/>' +
                                                                                                        '<input type="radio" name="DefaultImage" id="DefaultImage" value="' + current_photo_counter + '" /> Default' +
                                                                                                        '<input type="hidden" name="Listing[<?php echo $property_key; ?>][Photo][' + current_photo_counter + '][URL][value]" id="Listing_Photo_URL_<?php echo $pht; ?>" value="' + data + '" /> ' +
                                                                                                    '</div>');
                                                            
                                                            }
                                                       });
                                                        
                                                    });
                                                    
                                                   document.getElementById('div_add_photos').innerHTML = '';
                                                                                                                                
                                                };
                                            });
                                            </script>
                                            <input type="hidden" name="add_photos" id="add_photos" value=""  class="upload" />
                                            <input class="upload-button" name="wsl-image-add" type="button" value="Upload/Browse Media Library" style="padding:5px; margin:0px; cursor:pointer; width:100%; font-size:12px;" />
                                            <div id="div_add_photos" style="display:none;"></div>                        
                                			<div style="clear:both; height:0px; display:none;">&nbsp;</div>
                                        </div>
                                        Property Images:
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:10px; font-size:10px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:1px solid #CCCCCC; margin:0px;">
                                            <tr>
                                                <td>
                                                    <?php $pht = 0; ?>
                                                    <input name="photo_counter" id="photo_counter" type="hidden" style="width:20px; text-align:center;" value="<?php echo $pht; ?>" />
                                                    <div id="property_photos">
                                                        <?php if(count($mlsid_photos) > 0) { ?>
                                                            <?php foreach($mlsid_photos as $mlsid_photo) { ?>
                                                                <?php if(@fopen($mlsid_photo['ThumbnailURL']['value'],'r') !== false) { ?>	
                                                                    <div style="float:left; margin:3px; padding:3px; border:1px solid #999999; width:70px; text-align:center; vertical-align:middle; font-size:10px; color:#333333;"> 
                                                                        <a href="<?php echo $mlsid_photo['URL']['value']; ?>" target="_blank">
                                                                            <img src="<?php echo $mlsid_photo['ThumbnailURL']['value']; ?>" alt="" title="" border="0" style="margin:3px; border:2px solid #CCCCCC; width:60px; height:60px;" />
                                                                        </a>
                                                                        <br/>
                                                                        <script type="text/javascript">
                                                                        jQuery('#photo_counter').val('<?php echo $pht; ?>'); 
                                                                        </script>
                                                                        <input type="checkbox" name="removedPhoto[<?php echo $pht; ?>]" value="<?php echo $pht; ?>" /> Remove
                                                                        <br/>
                                                                        <input type="radio" name="DefaultImage" id="DefaultImage" value="<?php echo $pht; ?>" <?php if($wp_aem_properties_ids[0]['DefaultImageURL'] == $mlsid_photo['URL']['value']){ echo 'checked="checked"'; } ?> /> Default
                                                                        <input type="hidden" name="Listing[<?php echo $property_key; ?>][Photo][<?php echo $pht; ?>][URL][value]" id="Listing_Photo_URL_<?php echo $pht; ?>" value="<?php echo $mlsid_photo['URL']['value']; ?>" /> 
                                                                        <?php $pht++; ?>
                                                                    </div>  
                                                                <?php } ?>   
                                                            <?php } ?>
                                                        <?php } ?>   
                                                    </div> 
                                                    <div style="clear:both; visibility:hidden; height:0px;">&nbsp;</div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table id="files"></table>
                                        <div id="div_add_photos" style="display:none;"></div>   
                                        <div style="clear:both; height:0px; display:none;">&nbsp;</div>                    
                                    </td>
                            	</tr> 
                                <tr>
                                	<th colspan="2" style="background-color:#DFDFDF; font-size:14px; border-right:1px solid #CCCCCC;">&nbsp;</th>
                            	</tr> 
                                <tr>
                                    <td colspan="2" style="padding:10px">
                                        <input type="submit" value="Update" title="Update Property" name="submit_update"  class="button-primary" style="width:100px;" />
                                        <input type="button" value="Cancel" onclick="javascript: location.href='admin.php?page=agenteasy-properties/properties.php';" class="button-primary"  style="width:100px;" />
                                    </td>
                                </tr>
                        	</table>                
						</form>

                    <?php } else { ?> 
                        
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                            <tr>
                                <td style="padding:20px; font-size:12px; color:#CCCCCC;">
                                    No Property Found.
                                </td>
                            </tr> 
                        </table>                
                                       
                    <?php } ?> 
                    
                    <?php /*?>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                        <tr>
                            <td style="padding:10px; font-size:10px;">
                                <pre><?php print_r($mlsid_listing); ?></pre>
                            </td>
                        </tr> 
                    </table>                
                    <?php */?>
                    
                </td>
            </tr>
        </table>   
        
        <div style="clear:both;">&nbsp;</div>

	<?php } else { ?>        
        
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:1px solid #CCCCCC; margin:0px;">
            <tr>
                <td style="padding:40px; font-size: 14px; text-align: center; font-weight:bold; color:#333333;">
                   <img src="<?php echo AEM_PLUGIN_URL; ?>/images/warn.gif" alt="" title="" border="0" /> Property Not Found.
                   <br/><br/>
                   <a href="admin.php?page=agenteasy-properties/properties.php" style="text-decoration:none;">&laquo; go back to property list.</a>
                </td>
            </tr> 
        </table> 
                       
	<?php } ?>
    
    <p>&nbsp;</p>       
    
</div>

