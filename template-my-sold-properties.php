<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Template for MY SOLD PROPERTIES Page 
* ----------------------------------------------------------------------------------------------------------------------
*/
?>

<?php 
/** 
 |------------------------------------------------------------------------------------------------
 | XML Query >> Sold Listings
 |------------------------------------------------------------------------------------------------
*/
	
	$sold_listings = array();
	$MLSIDs = array();
	$error = 0;
	
	if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") {	
		$error++;
	}
	
	// check if no error
	if($error == 0) {
	
		if(count($db_listings['sold_listings']) > 0) {
			$sl = 0;
			foreach($db_listings['sold_listings'] as $db_sold_listings) {
				
				$sold_results = unserialize(base64_decode($db_sold_listings['full_property_details']));
				
				// set the property details
				$temp_sold_result = array();
				$temp_sold_result['MLS'] 										= $sold_results['MLS']['value'];
				$temp_sold_result['Represented'] 								= $sold_results['Represented']['value'];
				$temp_sold_result['Title'] 										= $sold_results['Title']['value'];
				$temp_sold_result['Address'] 									= $sold_results['Address']['value'];
				$temp_sold_result['Bedrooms'] 									= $sold_results['Bedrooms']['value'];
				$temp_sold_result['Bathrooms'] 									= $sold_results['Bathrooms']['value'];
				$temp_sold_result['PropertyType'] 								= $sold_results['PropertyType']['value'];
				$temp_sold_result['Neighborhood'] 								= $sold_results['Neighborhood']['value'];
				$temp_sold_result['Description']								= $sold_results['Description']['value'];
				$temp_sold_result['ListingPrice'] 								= $sold_results['ListingPrice']['value'];
				$temp_sold_result['SellingPrice'] 								= $sold_results['SellingPrice']['value'];
				$temp_sold_result['SoldDate']									= $sold_results['SoldDate']['value'];
				$temp_sold_result['ListingAgent'] 								= $sold_results['ListingAgent']['value'];
				$temp_sold_result['ListingOffice']								= $sold_results['ListingOffice']['value'];
				$temp_sold_result['Status']										= $sold_results['Status']['value'];
				$temp_sold_result['PrimaryDetails']['CrossStreet']				= $sold_results['PrimaryDetails']['CrossStreet']['value'];
				$temp_sold_result['PrimaryDetails']['ApproximateSqFt']			= $sold_results['PrimaryDetails']['ApproximateSqFt']['value'];
				$temp_sold_result['PrimaryDetails']['PricePerSqFt']				= $sold_results['PrimaryDetails']['PricePerSqFt']['value'];
				$temp_sold_result['PrimaryDetails']['YearBuilt']				= $sold_results['PrimaryDetails']['YearBuilt']['value'];
				$temp_sold_result['PrimaryDetails']['TotalRooms']				= $sold_results['PrimaryDetails']['TotalRooms']['value'];
				$temp_sold_result['PrimaryDetails']['HOADues']					= $sold_results['PrimaryDetails']['HOADues']['value'];
				$temp_sold_result['AdditionalDetails']['Parking']				= $sold_results['AdditionalDetails']['Parking']['value'];
				$temp_sold_result['AdditionalDetails']['Type']					= $sold_results['AdditionalDetails']['Type']['value'];
				$temp_sold_result['AdditionalDetails']['Style']					= $sold_results['AdditionalDetails']['Style']['value'];
				$temp_sold_result['AdditionalDetails']['Floors']				= $sold_results['AdditionalDetails']['Floors']['value'];
				$temp_sold_result['AdditionalDetails']['BathTypeIncludes']		= $sold_results['AdditionalDetails']['BathTypeIncludes']['value'];
				$temp_sold_result['AdditionalDetails']['Kitchen']				= $sold_results['AdditionalDetails']['Kitchen']['value'];
				$temp_sold_result['AdditionalDetails']['DiningRoom']			= $sold_results['AdditionalDetails']['DiningRoom']['value'];
				$temp_sold_result['AdditionalDetails']['LivingRoom']			= $sold_results['AdditionalDetails']['LivingRoom']['value'];
				$temp_sold_result['AdditionalDetails']['HeatingCoolingSystem'] 	= $sold_results['AdditionalDetails']['HeatingCoolingSystem']['value'];
				$temp_sold_result['AdditionalDetails']['LaundryAppliances']		= $sold_results['AdditionalDetails']['LaundryAppliances']['value'];
				$temp_sold_result['AdditionalDetails']['SpecialFeatures']		= $sold_results['AdditionalDetails']['SpecialFeatures']['value'];
				$temp_sold_result['AdditionalDetails']['CommonAreas']			= $sold_results['AdditionalDetails']['CommonAreas']['value'];
				$temp_sold_result['AdditionalDetails']['Transportation']		= $sold_results['AdditionalDetails']['Transportation']['value'];
				$temp_sold_result['AdditionalDetails']['Shopping']				= $sold_results['AdditionalDetails']['Shopping']['value'];
				$temp_sold_result['Comment']									= $sold_results['Comment']['value'];
				
				$MLSIDs[$db_sold_listings['MLS']] = $db_sold_listings['MLS'];
				$sold_listings[$sl] = $temp_sold_result;
				$sold_listings[$sl]['DefaultImageURL'] = $db_sold_listings['DefaultImageURL'];
            	$sold_listings[$sl]['DefaultThumbnailURL'] = $db_sold_listings['DefaultThumbnailURL'];
				
				$sl++;
				
			}
		}
	
		if($wp_plugin_aem_params['plugin_aem_option_xml_agent'] > 0 && $wp_plugin_aem_params['plugin_aem_option_xml_agent_enable'] == "Yes") {	

			// set xml query
			$xml_query_url = "query?status=sold&limit=100&page=0&agent=".$wp_plugin_aem_params['plugin_aem_option_xml_agent']."&apikey=".$wp_plugin_aem_params['plugin_aem_option_api_key'];
			
			// set the xml url (Baycentric Web Service URL with XML Query)
			$xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].rawurlencode($xml_query_url);
			#echo '<br/><a href="'.$xml_url.'">'.$xml_url.'</a>';
			
			// set response to null
			$response_xml = '';
			
			// initiate curl object
			$request = curl_init($xml_url); 
			
			// set to 0 to eliminate header info from response								
			curl_setopt($request, CURLOPT_HEADER, 0); 					
			
			// Returns response data instead of TRUE(1)
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 			
			
			// uncomment this line if you get no gateway response.
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); 
			
			// execute curl post and store results in $xml
			$response_xml = curl_exec($request); 
			
			// check CURL for error
			if ($curl_error = curl_error($request)) {
				$error++; // error counter
			}  
			
			// check if no response_xml
			if($response_xml == '') {
				$error++; // error counter
			}
				
			// close curl
			curl_close($request);
			
			// check if no error found
			if($error == 0) {
			
				// parse the xml content & store into array
				$arr = xml2array($response_xml);
				
				// check if array contains Listings array key
				if (sizeof($arr) == 1 && $arr["Listings"]) {
					
					// check if the Listings array key has Listings
					if (array_key_exists("Listing", $arr["Listings"])) {
						
						// check & get only the Listing 
						if(array_key_exists("MLS", $arr["Listings"]["Listing"])) {
							
							#$property_sold_results_listing[] = $arr["Listings"];
							$property_sold_results = $arr["Listings"];
						
						} else {
							
							// get the Listings
							$property_sold_results = $arr["Listings"]["Listing"];
							
						
						} // end if else -- if(array_key_exists("MLS", $arr["Listings"]["Listing"]))
						
						
						// check the total listings
						if(count($property_sold_results) > 0) { 
							
							// loop through the sold results (Listings)
							foreach($property_sold_results as $sold_results) {
								
								// MLS Listing#
								if($sold_results["MLS"]["value"] != "" || $sold_results["MLS"]["value"] > 0) {
									
									// re-check if the listing is already added
									if($MLSIDs[$sold_results["MLS"]["value"]] == "" && $MLSIDs[$sold_results["MLS"]["value"]] <= 0) {
										
										// set the property details
										$temp_sold_result = array();
										$temp_sold_result['MLS'] 										= $sold_results['MLS']['value'];
										$temp_sold_result['Represented'] 										= $sold_results['Represented']['value'];
										$temp_sold_result['Title'] 										= $sold_results['Title']['value'];
										$temp_sold_result['Address'] 									= $sold_results['Address']['value'];
										$temp_sold_result['Bedrooms'] 									= $sold_results['Bedrooms']['value'];
										$temp_sold_result['Bathrooms'] 									= $sold_results['Bathrooms']['value'];
										$temp_sold_result['PropertyType'] 								= $sold_results['PropertyType']['value'];
										$temp_sold_result['Neighborhood'] 								= $sold_results['Neighborhood']['value'];
										$temp_sold_result['Description']								= $sold_results['Description']['value'];
										$temp_sold_result['ListingPrice'] 								= $sold_results['ListingPrice']['value'];
										$temp_sold_result['SellingPrice'] 								= $sold_results['SellingPrice']['value'];
										$temp_sold_result['SoldDate']									= $sold_results['SoldDate']['value'];
										$temp_sold_result['ListingAgent'] 								= $sold_results['ListingAgent']['value'];
										$temp_sold_result['ListingOffice']								= $sold_results['ListingOffice']['value'];
										$temp_sold_result['Status']										= $sold_results['Status']['value'];
										$temp_sold_result['PrimaryDetails']['CrossStreet']				= $sold_results['PrimaryDetails']['CrossStreet']['value'];
										$temp_sold_result['PrimaryDetails']['ApproximateSqFt']			= $sold_results['PrimaryDetails']['ApproximateSqFt']['value'];
										$temp_sold_result['PrimaryDetails']['PricePerSqFt']				= $sold_results['PrimaryDetails']['PricePerSqFt']['value'];
										$temp_sold_result['PrimaryDetails']['YearBuilt']				= $sold_results['PrimaryDetails']['YearBuilt']['value'];
										$temp_sold_result['PrimaryDetails']['TotalRooms']				= $sold_results['PrimaryDetails']['TotalRooms']['value'];
										$temp_sold_result['PrimaryDetails']['HOADues']					= $sold_results['PrimaryDetails']['HOADues']['value'];
										$temp_sold_result['AdditionalDetails']['Parking']				= $sold_results['AdditionalDetails']['Parking']['value'];
										$temp_sold_result['AdditionalDetails']['Type']					= $sold_results['AdditionalDetails']['Type']['value'];
										$temp_sold_result['AdditionalDetails']['Style']					= $sold_results['AdditionalDetails']['Style']['value'];
										$temp_sold_result['AdditionalDetails']['Floors']				= $sold_results['AdditionalDetails']['Floors']['value'];
										$temp_sold_result['AdditionalDetails']['BathTypeIncludes']		= $sold_results['AdditionalDetails']['BathTypeIncludes']['value'];
										$temp_sold_result['AdditionalDetails']['Kitchen']				= $sold_results['AdditionalDetails']['Kitchen']['value'];
										$temp_sold_result['AdditionalDetails']['DiningRoom']			= $sold_results['AdditionalDetails']['DiningRoom']['value'];
										$temp_sold_result['AdditionalDetails']['LivingRoom']			= $sold_results['AdditionalDetails']['LivingRoom']['value'];
										$temp_sold_result['AdditionalDetails']['HeatingCoolingSystem'] 	= $sold_results['AdditionalDetails']['HeatingCoolingSystem']['value'];
										$temp_sold_result['AdditionalDetails']['LaundryAppliances']		= $sold_results['AdditionalDetails']['LaundryAppliances']['value'];
										$temp_sold_result['AdditionalDetails']['SpecialFeatures']		= $sold_results['AdditionalDetails']['SpecialFeatures']['value'];
										$temp_sold_result['AdditionalDetails']['CommonAreas']			= $sold_results['AdditionalDetails']['CommonAreas']['value'];
										$temp_sold_result['AdditionalDetails']['Transportation']		= $sold_results['AdditionalDetails']['Transportation']['value'];
										$temp_sold_result['AdditionalDetails']['Shopping']				= $sold_results['AdditionalDetails']['Shopping']['value'];
										$temp_sold_result['Comment']									= $sold_results['Comment']['value'];
										
										$temp_sold_result['DefaultImageURL'] 							= $sold_results['DefaultImageURL']['value'];
										$temp_sold_result['DefaultThumbnailURL'] 						= $sold_results['DefaultThumbnailURL']['value'];
										
										
										$sold_listings[] = $temp_sold_result; // $sold_results
										$MLSIDs[$sold_results["MLS"]["value"]] = $sold_results["MLS"]["value"];
									
									}
								
								} // end if else - if($sold_results["MLS"]["value"] == "" || $sold_results["MLS"]["value"] <= 0)
								
							
							} // end foreach -- foreach($property_sold_results as $sold_results)
						
						
						} // end if -- if(count($property_sold_results) > 0)
						
					
					} // end if -- if (array_key_exists("Listing", $arr["Listings"]))
					
				
				} // end if -- if (sizeof($arr) == 1 && $arr["Listings"])
					
			
			} // end if -- if($error == 0)
			
		}
		
		
		/** 
		 |------------------------------------------------------------------------------------------------
		 | Sort the Sold Listings
		 |------------------------------------------------------------------------------------------------
		*/
		
			// ARRAY_SORTER Class -- Handles Sorting of Array
			include_once(dirname(__FILE__) . '/array_sorter.class.php');
			
			$AEM_ARRAY_SORTER->backwards = true; // descending order
			$AEM_ARRAY_SORTER->numeric = false;
			$sorted_sold_listings = $AEM_ARRAY_SORTER->sort($sold_listings, 'SoldDate');
			$sold_listings = $sorted_sold_listings;
	
			
		/** 
		 |------------------------------------------------------------------------------------------------
		 | Search Results: Pagination
		 |------------------------------------------------------------------------------------------------
		*/
		
			// echo '<pre>'; print_r($sold_listings); echo '</pre>'; exit();
			
			// set pagination setting
			$frontText 		= "";
			$limit 			= 35;
			$adjacents 		= 1;
			$targetpage 	= "?";
			$pagestring 	= "pg=";
			$total_listings = count($sold_listings);
			
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
			
			// get listings page numbering
			$n = $offset;
		
	}
	
?>

<div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
    <link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
</div>

<div class="property_my_properties">

		<?php if(count($sold_listings) > 0) { // count & check if has search results (listings) ?>

        <div id="div_loading_my_sold_properties" style="padding:50px; text-align:center; vertical-align:middle;">
            <h1 style="text-align:center;">Loading...</h1>
            <img src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/loading.gif" alt="Loading Search Results..." title="Loading Search Results..." />
        </div>
            
        <div id="div_my_sold_properties" style="display:none;">        
            
            <?php 
            // pagination >> get & set the listings that will be lists
            $rec_min = ($offset);
            if($total_listings > $limit) {		 
                $rec_max = ($offset) + $limit;
            } else {
                $rec_max = $total_listings;
            }
            $n = $rec_min; // counter
            ?> 
            
            <?php while($n < $rec_max) { ?> 
                
				<?php $listing = $sold_listings[$n]; ?>
				
				<?php if($listing["MLS"] != "" || $listing["Address"] != "") { ?>
                
					<?php
                    if($listing["MLS"] == "") { 
                    	$listing["MLS"] = 0;
					}
                    ?>
                
                    <div class="property_my_properties-item" id="#<?=$n+1;?>" <?php if($n == $rec_min) { echo 'style="border-top: 1px solid #CCCCCC;"'; } ?>>
                        <div class="property_my_properties-thumb-container">
                           <div class="sash status-sold"><?php echo $listing["Status"]; ?></div>
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]; ?>/<?php echo urlencode($listing["Address"]); ?>/">
								<?php if($listing["DefaultThumbnailURL"] != "") { ?>
                                    <img border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $listing["DefaultThumbnailURL"]; ?>" style="margin:0px; padding:0px; border:none;" />
                                <?php } else { ?>
                                    <img height="125" border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/no_image_available.jpg" style="margin:0px; padding:0px; border:none;" />
                                <?php } ?>
                            </a>
                            
                        </div>
                        <div class="property_my_properties-detail"> 
                            
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]; ?>/<?php echo urlencode($listing["Address"]); ?>/">
                                <?php echo $listing["Address"]; ?>
                            </a>
                            
                            <br/><?php echo $listing["PropertyType"]; ?>
                            
                            <?php if ($listing["SellingPrice"]) { ?>
                            	<br/>Sold Price: $<?php echo number_format($listing["SellingPrice"], 2); ?> 
							<?php } ?>
                            
                            <?php if ($listing["SoldDate"]) { ?>
                            	<br/>Sold Date: <?php echo aem_formatDate($dateFormat="Y-m-d", $listing["SoldDate"]); ?>
                          	<?php } ?>
                            <?php if ($listing["Represented"]) { ?>
                            	<br/>Represented: <?php echo $listing["Represented"]; ?>
                          	<?php } ?>
                              
                        </div>
                        <div style="clear:both;width:100%;"></div>
                    </div>
                    
                    <div class="clearfix">&nbsp;</div>
                    
                <?php } ?>
                
                <?php $n++; // counter ?>
        
            <?php } // end while ?>
                    
            <div class="clearfix" style="height:15px;">&nbsp;</div>
            
            <!-- Pagination -->
            <?php echo plugin_aem_getPaginationString($frontText, $pg, $total_listings, $limit, $adjacents, $targetpage, $pagestring); ?>
    
            <?php /*?>
			<p>Total Search Results: <?php echo $total_listings; ?></p>
			<?php */?>
            
       		<div class="clearfix">&nbsp;</div>
        
        </div>
        
	<?php } else { ?>  
        
        <div class="property_results-noresult">
             No Sold Properties
            <?php if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { ?>
                <div style="font-size:10px; color:#990000;">
                    AgentEasy Properties Plugin API Key is not set.
                </div>
            <?php } ?>
        </div>
              
    <?php } ?> 
    
    
    <?php /*?>
  	 <div style="padding:10px; margin:10px; text-align:left; font-size:10px; border:1px solid #999999;">
        <pre><?php print_r($sold_listings); ?></pre>
    </div>
    <?php */?>
    
                        
</div>

<script language="JavaScript">
document.getElementById('div_loading_my_sold_properties').style.display='none';
document.getElementById('div_my_sold_properties').style.display='block';
</script> 

        


