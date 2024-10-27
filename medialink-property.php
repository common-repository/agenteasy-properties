<?php
/**
* ----------------------------------------------------------------------------------------------------------------------
* Include WP files
* ----------------------------------------------------------------------------------------------------------------------
*/

	require_once('../../../wp-load.php');
	require_once(ABSPATH . 'wp-admin/includes/admin.php');

	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Set Image Parameters
* ----------------------------------------------------------------------------------------------------------------------
*/

	// Property ID
	$propertyID = (isset($_GET['propertyID']) && !empty($_GET['propertyID'])) ? $_GET['propertyID'] : 0;
	
	// image full url
	$img_src = '';
	
	if(isset($_GET['image']) && !empty($_GET['image'])) {
	
		// image wp media library attached class
		$img_src = (isset($_GET['image']) && !empty($_GET['image'])) ? $_GET['image'] : '';
		
	} else {
	
		// html output
		$html = '';
		
		// image wp media library attached id
		$id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : 0;
		
		// image params
		$caption = '';
		$title 	 = '';
		$align 	 = '';
		$url 	 = '';
		$rel 	 = false;
		$size	 ='full';
		$alt 	 = '';
		
		// get image
		list( $img_src, $width, $height ) = image_downsize($id, $size);
		  
		// set img html size string tags
		$hwstring = image_hwstring($width, $height);
		
		// set the img html class tag
		$class = 'align' . esc_attr($align) .' size-' . esc_attr($size) . ' wp-image-' . $id;
		
		// filter/apply the class
		$class = apply_filters('get_image_tag_class', $class, $id, $align, $size);
		
		// set the output img html tag
		$html = '<img src="' . esc_attr($img_src) . '" alt="' . esc_attr($alt) . '" title="' . esc_attr($title).'" '.$hwstring.' class="'.$class.'" />';
		
		// filter/apply the img html tag
		$html = apply_filters( 'get_image_tag', $html, $id, $alt, $title, $align, $size );
		
		// checck & apply if rel is set
		$rel = $rel ? ' rel="attachment wp-att-' . esc_attr($id).'"' : '';
		
		// check & apply anchor if anchor url is set
		if( $url ) $html = '<a href="' . esc_attr($url) . "\"$rel>$html</a>";
		
		// filter/apply the html output
		$html = apply_filters( 'image_send_to_editor', $html, $id, $caption, $title, $align, $url, $size, $alt );
		
		// display the html output (this is disabled because we only need the image src to be returned)
		# echo $html;
	
	}
	
	// clean the image src 
	$img_src = esc_attr($img_src);

	// return/display the image src
	echo $img_src;
	
	/* --------------------------------------------------
	// display the image src if the image file exists
	$handle = @fopen($img_src,'r');
	if($handle !== false){
		echo $img_src;
	}
	----------------------------------------------------- */
		

/**
* ----------------------------------------------------------------------------------------------------------------------
* Automatic Insert/Update the Property Images/Photos
* ----------------------------------------------------------------------------------------------------------------------
*/

	// check if propertyID is set
	if($propertyID > 0) {
	
		// check if the property images will be inserted/updated
		if($handle !== false){
			
			$error = 0;
			
			$sql = "SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE id = '".$propertyID."' LIMIT 1";
			
			$mlsid_listings = $wpdb->get_results($sql, ARRAY_A);
		
			$mlsid_listing_db = unserialize(base64_decode($mlsid_listings[0]['full_property_details']));
			$MLSID = $mlsid_listings[0]['MLS'];	
			
			$mlsid_listing = unserialize(base64_decode($mlsid_listings[0]['full_property_details']));
		
			if($mlsid_listing['Represented']['value'] == "") {
				$tmp_ml['Represented']['value'] = "Both";
			} else {	
				$tmp_ml['Represented']['value'] = $mlsid_listing['Represented']['value'];
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

			if($error == 0) {
						
				// set & clean the POST_Listing details
				$_POST_Listing = $mlsid_listing;
				$_POST_Listing['MLS']['value'] 										= stripslashes($mlsid_listing['MLS']['value']);
				$_POST_Listing['Title']['value'] 									= stripslashes($mlsid_listing['Title']['value']);
				$_POST_Listing['Address']['value'] 									= stripslashes($mlsid_listing['Address']['value']);
				$_POST_Listing['Bedrooms']['value'] 								= stripslashes($mlsid_listing['Bedrooms']['value']);
				$_POST_Listing['Bathrooms']['value'] 								= stripslashes($mlsid_listing['Bathrooms']['value']);
				$_POST_Listing['PropertyType']['value'] 							= stripslashes($mlsid_listing['PropertyType']['value']);
				$_POST_Listing['Neighborhood']['value'] 							= stripslashes($mlsid_listing['Neighborhood']['value']);
				$_POST_Listing['Description']['value'] 								= stripslashes($mlsid_listing['Description']['value']);
				$_POST_Listing['ListingPrice']['value'] 							= stripslashes($mlsid_listing['ListingPrice']['value']);
				$_POST_Listing['SellingPrice']['value'] 							= stripslashes($mlsid_listing['SellingPrice']['value']);
				$_POST_Listing['SoldDate']['value'] 								= stripslashes($mlsid_listing['SoldDate']['value']);
				$_POST_Listing['ListingAgent']['value'] 							= stripslashes($mlsid_listing['ListingAgent']['value']);
				$_POST_Listing['ListingOffice']['value']							= stripslashes($mlsid_listing['ListingOffice']['value']);
				$_POST_Listing['Status']['value'] 									= stripslashes($mlsid_listing['Status']['value']);
				$_POST_Listing['PrimaryDetails']['CrossStreet']['value'] 			= stripslashes($mlsid_listing['PrimaryDetails']['CrossStreet']['value']);
				$_POST_Listing['PrimaryDetails']['ApproximateSqFt']['value'] 		= stripslashes($mlsid_listing['PrimaryDetails']['ApproximateSqFt']['value']);
				$_POST_Listing['PrimaryDetails']['PricePerSqFt']['value'] 			= stripslashes($mlsid_listing['PrimaryDetails']['PricePerSqFt']['value']);
				$_POST_Listing['PrimaryDetails']['YearBuilt']['value'] 				= stripslashes($mlsid_listing['PrimaryDetails']['YearBuilt']['value']);
				$_POST_Listing['PrimaryDetails']['TotalRooms']['value'] 			= stripslashes($mlsid_listing['PrimaryDetails']['TotalRooms']['value']);
				$_POST_Listing['PrimaryDetails']['HOADues']['value'] 				= stripslashes($mlsid_listing['PrimaryDetails']['HOADues']['value']);
				$_POST_Listing['AdditionalDetails']['Parking']['value'] 			= stripslashes($mlsid_listing['AdditionalDetails']['Parking']['value']);
				$_POST_Listing['AdditionalDetails']['Type']['value']				= stripslashes($mlsid_listing['AdditionalDetails']['Type']['value']);
				$_POST_Listing['AdditionalDetails']['Style']['value'] 				= stripslashes($mlsid_listing['AdditionalDetails']['Style']['value']);
				$_POST_Listing['AdditionalDetails']['Floors']['value'] 				= stripslashes($mlsid_listing['AdditionalDetails']['Floors']['value']);
				$_POST_Listing['AdditionalDetails']['BathTypeIncludes']['value']	= stripslashes($mlsid_listing['AdditionalDetails']['BathTypeIncludes']['value']);
				$_POST_Listing['AdditionalDetails']['Kitchen']['value'] 			= stripslashes($mlsid_listing['AdditionalDetails']['Kitchen']['value']);
				$_POST_Listing['AdditionalDetails']['DiningRoom']['value'] 			= stripslashes($mlsid_listing['AdditionalDetails']['DiningRoom']['value']);
				$_POST_Listing['AdditionalDetails']['LivingRoom']['value'] 			= stripslashes($mlsid_listing['AdditionalDetails']['LivingRoom']['value']);
				$_POST_Listing['AdditionalDetails']['HeatingCoolingSystem']['value']= stripslashes($mlsid_listing['AdditionalDetails']['HeatingCoolingSystem']['value']);
				$_POST_Listing['AdditionalDetails']['LaundryAppliances']['value'] 	= stripslashes($mlsid_listing['AdditionalDetails']['LaundryAppliances']['value']);
				$_POST_Listing['AdditionalDetails']['SpecialFeatures']['value'] 	= stripslashes($mlsid_listing['AdditionalDetails']['SpecialFeatures']['value']);
				$_POST_Listing['AdditionalDetails']['CommonAreas']['value'] 		= stripslashes($mlsid_listing['AdditionalDetails']['CommonAreas']['value']);
				$_POST_Listing['AdditionalDetails']['Transportation']['value'] 		= stripslashes($mlsid_listing['AdditionalDetails']['Transportation']['value']);
				$_POST_Listing['AdditionalDetails']['Shopping']['value'] 			= stripslashes($mlsid_listing['AdditionalDetails']['Shopping']['value']);
				$_POST_Listing['Comment']['value'] 									= stripslashes($mlsid_listing['Comment']['value']);
	
				$_POST_Listing['Photos']['Photo'] = array();
				foreach($mlsid_listing['Photos']['Photo'] as $listing_photos_key => $listing_photos_val) {
					$mlsid_listing['Photos']['Photo'][$listing_photos_key]['ThumbnailURL'] = $mlsid_listing['Photos']['Photo'][$listing_photos_key]['URL'];
					$_POST_Listing['Photos']['Photo'][] = $mlsid_listing['Photos']['Photo'][$listing_photos_key];
				}
				
				// add the image --------------------------------
				$_POST_Listing['Photos']['Photo'][] = array('URL' => array('value' => $img_src), 'ThumbnailURL' => array('value' => $img_src));;
				
				$POST_Listing =  base64_encode(serialize($_POST_Listing)); 
				if($POST_Listing == '') { $error++; }
			
				if($error == 0) {
			
					$sql = "UPDATE ".AEM_PLUGIN_DB_Table." SET
							full_property_details = '".$POST_Listing."'
							WHERE id = '".aem_clean($propertyID)."'
							LIMIT 1";
			
					$update = $wpdb->query($sql);
								
				}
				
			}
			
		}
		
	}

?>