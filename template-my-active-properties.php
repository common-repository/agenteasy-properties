<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Template for MY ACTIVE PROPERTIES Page 
* ----------------------------------------------------------------------------------------------------------------------
*/
?>

<?php 
/** 
 |------------------------------------------------------------------------------------------------
 | XML Query >> Active Listings
 |------------------------------------------------------------------------------------------------
*/
	
	$active_listings = array();
	$MLSIDs = array();
	$error = 0;
	
	if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") {	
		$error++;
	}
	
	// check if no error
	if($error == 0) {
	
		if(count($db_listings['active_listings']) > 0) {
			$sl = 0;
			foreach($db_listings['active_listings'] as $db_active_listings) {
				$MLSIDs[$db_active_listings['MLS']] = $db_active_listings['MLS'];
				$active_listings[$sl] = unserialize(base64_decode($db_active_listings['full_property_details']));
				$active_listings[$sl]['DefaultImageURL']['value'] = $db_active_listings['DefaultImageURL'];
            	$active_listings[$sl]['DefaultThumbnailURL']['value'] = $db_active_listings['DefaultThumbnailURL'];
				$sl++;
			}
		}
		
		if($wp_plugin_aem_params['plugin_aem_option_xml_agent_enable'] == "Yes" && $wp_plugin_aem_params['plugin_aem_option_xml_agent'] > 0) {	
	
			$my_active_listings_status = array();
			$my_active_listings_status[] = "Active";
			$my_active_listings_status[] = "Act. Cont.";
			
			foreach($my_active_listings_status as $listing_status) {
			
				// set xml query
				$xml_query_url = "query?status=".urlencode($listing_status)."&limit=100&page=0&agent=".$wp_plugin_aem_params['plugin_aem_option_xml_agent']."&apikey=".$wp_plugin_aem_params['plugin_aem_option_api_key'];
				
				// set the xml url (Baycentric Web Service URL with XML Query)
				$xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].rawurlencode($xml_query_url);
				
				#echo '<a href="'.$xml_url.'" target="_blank">'.$listing_status.'</a>';
				
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
								
								#$property_active_results_listing[] = $arr["Listings"];
								$property_active_results = $arr["Listings"];
							
							} else {
								
								// get the Listings
								$property_active_results = $arr["Listings"]["Listing"];
							
							} // end if else -- if(array_key_exists("MLS", $arr["Listings"]["Listing"]))	
								
								
							// check the total listings
							if(count($property_active_results) > 0) { 
								
								// loop through the active results (Listings)
								foreach($property_active_results as $active_results) {
									
									// MLS Listing#
									if($active_results["MLS"]["value"] != "" || $active_results["MLS"]["value"] > 0) {
										
										// re-check if the listing is already added
										if($MLSIDs[$active_results["MLS"]["value"]] == "" && $MLSIDs[$active_results["MLS"]["value"]] <= 0) {
											
											$MLSIDs[$active_results["MLS"]["value"]] = $active_results["MLS"]["value"];
											$active_listings[] = $active_results;
										
										} // end if else
									
									} // end if else - if($active_results["MLS"]["value"] == "" || $active_results["MLS"]["value"] <= 0)
									
								
								} // end foreach -- foreach($property_active_results as $active_results)
							
							
							} // end if -- if(count($property_active_results) > 0)
													
							
						} // end if -- if (array_key_exists("Listing", $arr["Listings"]))
						
					} // end if -- if (sizeof($arr) == 1 && $arr["Listings"])
				
				
				} // end if -- if($error == 0) {
					
			} // end of foreach
	
		} // end of if
		
		// Search Results: Pagination
		$frontText 		= "";
		$limit 			= 10;
		$adjacents 		= 1;
		$targetpage 	= "index.php?";
		$pagestring 	= "pg=";
		$total_listings = count($active_listings);
		
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

    <div id="div_loading_my_active_properties" style="padding:50px; text-align:center; vertical-align:middle;">
        <h1 style="text-align:center;">Loading...</h1>
        <img src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/loading.gif" alt="Loading Search Results..." title="Loading Search Results..." />
    </div>
        
    <div id="div_my_active_properties" style="display:none;">        
        
		<?php if(count($active_listings) > 0) { // count & check if has search results (listings) ?>

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
                
				<?php $listing = $active_listings[$n]; ?>
				
				<?php if($listing["MLS"]["value"] != "" || $listing["Address"]["value"] != "") { ?>
                
                	<?php
                    if($listing["MLS"]["value"] == "") { 
                    	$listing["MLS"]["value"] = 0;
					}
                    ?>
                
                    <div class="property_my_properties-item" id="#<?=$n+1;?>" <?php if($n == $rec_min) { echo 'style="border-top: 1px solid #CCCCCC;"'; } ?>>
                        <div class="property_my_properties-thumb-container">
                           <div class="sash status-active"><?php echo $listing["Status"]["value"]; ?></div>
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]["value"]; ?>/<?php echo urlencode($listing["Address"]["value"]); ?>/">
                                <?php // if(strstr($listing["DefaultThumbnailURL"]["value"], $listing["MLS"]["value"])) { ?>
								<?php if($listing["DefaultThumbnailURL"]["value"] != "") { ?>
                                    <img height="125" border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $listing["DefaultThumbnailURL"]["value"]; ?>" style="margin:0px; padding:0px; border:none;" />
                                <?php } else { ?>
                                    <img height="125" border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/no_image_available.jpg" style="margin:0px; padding:0px; border:none;" />
                                <?php } ?>
                            </a>
                        </div>
                        <div class="property_my_properties-detail"> 
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]["value"]; ?>/<?php echo urlencode($listing["Address"]["value"]); ?>/">
                                <?php echo $listing["Address"]["value"]; ?>
                            </a>
                            <br/><?php echo $listing["PropertyType"]["value"]; ?>
                            <br/>Offered at: $<?php echo number_format($listing["ListingPrice"]["value"], 2); ?> 
                        </div>
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
           
        <?php } else { ?>  
            
            <div class="property_my_active_properties-noresult">
                No Active Properties
				<?php if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { ?>
                    <div style="font-size:10px; color:#990000;">
                        AgentEasy Properties Plugin API Key is not set.
                    </div>
                <?php } ?>
            </div>
                  
		<?php } ?> 
        
        <?php /*?>
        <div style="padding:10px; margin:10px; font-size:10px; border:1px solid #999999;">
            <pre><?php print_r($active_listings); ?></pre>
        </div>
        <?php */?>  
                     
    </div>
                
</div>
        
<script language="JavaScript">
document.getElementById('div_loading_my_active_properties').style.display='none';
document.getElementById('div_my_active_properties').style.display='block';
</script> 
	

