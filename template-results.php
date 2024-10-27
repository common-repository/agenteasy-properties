<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Template for Search RESULTS Page
* ----------------------------------------------------------------------------------------------------------------------
*/
?>

<?php 
/** 
 |------------------------------------------------------------------------------------------------
 | Pagination
 |------------------------------------------------------------------------------------------------
*/

	// get current page 
	if($_GET['pg'] > 0) {
		$pg = $_GET['pg']; // get page from the url parameter ( eg. ?pg=2 )
	} else {
		$pg = 1; // default = page 1
	}
	
	$limit = $wp_plugin_aem_params['plugin_aem_option_limit'];
	
	
/** 
 |------------------------------------------------------------------------------------------------
 | Search Property: Process search query
 |------------------------------------------------------------------------------------------------
*/

	// base query
	$xml_query_url .= "query?";
	
	// sorting
	if(isset($_POST['submit_sort'])) {
		$_SESSION['property_search']['sort'] = $_POST['sort'];
		$_SESSION['listings'] = array();
		$_SESSION['MLSIDs'] = array();
		$_SESSION['listing_results'] = array();
		$_SESSION['xml_pages'] = array();

	}
	if($_SESSION['property_search']['sort'] != '') {
		$xmlsort = "&sort=".$_SESSION['property_search']['sort'];
	} else {
		$xmlsort = '';
	}

	// MLS Listing#
	if(!isset($_SESSION['property_search']['mlsid']) || empty($_SESSION['property_search']['mlsid'])) {
		$_SESSION['property_search']['mlsid'] = "";
	}

	// address
	if(!isset($_SESSION['property_search']['Address']) || empty($_SESSION['property_search']['Address'])) {
		$_SESSION['property_search']['Address'] = "";
	}
	if($_SESSION['property_search']['Address'] != "") {
		$Search_Address = explode("-", sanitize_title(strtolower($_SESSION['property_search']['Address'])));
	} else {
		$Search_Address = array();
	}

	// Street Suffixes (this is to make sure that if you search for "dr" will also search "drive" - or the other way around)
	$Street_Suffixes = array();
	$Street_Suffixes['avenue'] 	 = "ave";
	$Street_Suffixes['boulevard']= "blvd";
	$Street_Suffixes['circle'] 	 = "cir";
	$Street_Suffixes['court'] 	 = "ct";
	$Street_Suffixes['drive'] 	 = "dr";
	$Street_Suffixes['lane'] 	 = "ln";
	$Street_Suffixes['parkway']  = "pkwy";
	$Street_Suffixes['place'] 	 = "pl";
	$Street_Suffixes['road'] 	 = "rd";
	$Street_Suffixes['street'] 	 = "st";
	$Street_Suffixes['ave'] 	= "avenue";
	$Street_Suffixes['blvd']	= "boulevard";
	$Street_Suffixes['cir'] 	= "circle";
	$Street_Suffixes['ct'] 	 	= "court";
	$Street_Suffixes['dr'] 	 	= "drive";
	$Street_Suffixes['ln'] 	 	= "lane";
	$Street_Suffixes['pkwy']  	= "parkway";
	$Street_Suffixes['pl'] 	 	= "place";
	$Street_Suffixes['rd'] 	 	= "road";
	$Street_Suffixes['st'] 	 	= "street";
	
	// ListingPrice
	if(!isset($_SESSION['property_search']['ListingPrice']['min']) || empty($_SESSION['property_search']['ListingPrice']['min'])) {
		$_SESSION['property_search']['ListingPrice']['min'] = 0;
	}
	if(!isset($_SESSION['property_search']['ListingPrice']['max']) || empty($_SESSION['property_search']['ListingPrice']['max'])) {
		$_SESSION['property_search']['ListingPrice']['max'] = 0;
	}
	
	// Bedrooms
	if(!isset($_SESSION['property_search']['Bedrooms']['min'])) {
		$_SESSION['property_search']['Bedrooms']['min'] = 0;
	}
	
	// Bathrooms
	if(!isset($_SESSION['property_search']['Bathrooms']['min'])) {
		$_SESSION['property_search']['Bathrooms']['min'] = 0;
	}
	
	// property type
	if(!isset($_SESSION['property_search']['type']) || empty($_SESSION['property_search']['type'])) {
		$_SESSION['property_search']['type'] = "";
	}
	if(isset($_SESSION['property_search']['type']) && !empty($_SESSION['property_search']['type'])) {
		$xml_query_url .= "&type=".str_replace(' ','+',$_SESSION['property_search']['type']);
	} 
	
	// neighborhoods
	if(!isset($_SESSION['property_search']['neighborhoods']) || empty($_SESSION['property_search']['neighborhoods'])) {
		$_SESSION['property_search']['neighborhoods'] = array();
	}
	
	// Status
	if($wp_plugin_aem_params['plugin_aem_option_xml_status'] != "") {
		$plugin_aem_option_xml_status = $wp_plugin_aem_params['plugin_aem_option_xml_status'];
	} else {
		$plugin_aem_option_xml_status .= "active,pending";
	}
	$XML_Status = explode(",", strtolower($plugin_aem_option_xml_status));
			
	// search results holder
	if(!isset($_SESSION['listings']) || empty($_SESSION['listings'])) {
		$_SESSION['listings'] = array();
	}

	// ListingPrice - MIN
	if($_SESSION['property_search']['ListingPrice']['min'] > 0) {
		$xml_query_url .= "&price[min]=".($_SESSION['property_search']['ListingPrice']['min'] * 1000);
	}
	
	// ListingPrice - MAX
	if($_SESSION['property_search']['ListingPrice']['max'] > 0) {
		$xml_query_url .= "&price[max]=".($_SESSION['property_search']['ListingPrice']['max'] * 1000);
	}
	
	// neighborhoods
	if(isset($_SESSION['property_search']['neighborhoods']) && !empty($_SESSION['property_search']['neighborhoods']) && count($_SESSION['property_search']['neighborhoods']) > 0) {
		
		$neighborhoods = "";
		
		foreach($_SESSION['property_search']['neighborhoods'] as $neighborhood_id) {
			if($neighborhoods != "") { $neighborhoods .= ","; }
			$neighborhoods .= $neighborhood_id;
		}
		if($neighborhoods != "") {
			$xml_query_url .= "&neighborhoods=".$neighborhoods;
		}
		
	} else {
	
		$option_xml_neighborhoods = array();
		if($wp_plugin_aem_params['plugin_aem_option_xml_neighborhoods_serialize'] != "") {
			$option_xml_neighborhoods = html_entity_decode($wp_plugin_aem_params['plugin_aem_option_xml_neighborhoods_serialize'],ENT_QUOTES);
			$option_xml_neighborhoods = unserialize($option_xml_neighborhoods);
		} 
		if(count($option_xml_neighborhoods) > 0) {
			$neighborhoods = "";
			foreach($option_xml_neighborhoods as $neighborhood) {
				if($neighborhood['enable'] == "Yes") {
					if($neighborhood['id'] != "") {
						if($neighborhoods != "") { $neighborhoods .= ","; }
						$neighborhoods .= $neighborhood['id'];
					}
				}
			}
			if($neighborhoods != "") {
				$xml_query_url .= "&neighborhoods=".$neighborhoods;
			}
		}
		
	}

	// set the values to null
	$search_listings = array();
	$_SESSION['MLSIDs'] = array();
   
	$total_xml_status = count($XML_Status);

	// check if the xml listing query status value is not null
	if($total_xml_status > 0) {
		
		require_once(AEM_PLUGIN_PATH.'/xmlstr_to_array.php');
		
		// set & search for each listing status
		foreach($XML_Status as $Search_Status) { 
	
			// xml page to load
			$page = 0;
			$continue = true;
		
			while($continue == true) {
				
				if(!isset($_SESSION['listing_results'][$Search_Status][$page]) && empty($_SESSION['listing_results'][$Search_Status][$page])) {
					
					// set error counter to zero
					$error = 0;
					
					// set response to null
					$response_xml = '';
				
					// set the xml url (Baycentric Web Service URL with XML Query)
					$xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].rawurlencode($xml_query_url.'&status='.$Search_Status.''.$xmlsort.'&limit='.$limit.'&page='.$page.'&apikey='.$wp_plugin_aem_params['plugin_aem_option_api_key']);
					
					// store the parsed xml page number - this is for checking only
					$_SESSION['xml_pages'][$page] = '<a href="'.$xml_url.'">'.$page.'</a>';
				
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
					
					// close curl
					curl_close($request);
					
					// store into session to avoid parsing page again
					$_SESSION['listing_results'][$Search_Status][$page] = $response_xml;
				
					// check if no response_xml
					if($response_xml == '') {
						
						$error++; // error counter
					
					} else {
						
						$errorMsg = 'ZephyrError';
						
						// check if response_xml is an error message
						if (preg_match("/".$errorMsg."/i", $response_xml)) {
							$error++; // error counter
						}
					
					}
							
					// check if no error found
					if($error == 0) {
					
						$property_search_results = array();
						
						// Start: XML to Array =======================================================
						
						// parse the xml content & store into array
						$xml_array = xmlstr_to_array($response_xml);
						
						if (array_key_exists("Listing", $xml_array)) {
							if(count($xml_array['Listing']) > 0) { 
								$property_search_results = $xml_array['Listing'];
							}
						}
						
						// End: XML to Array =======================================================
					
						// check the total listings
						if(count($property_search_results) > 0) { 
							
							// loop through the search results (Listings)
							foreach($property_search_results as $search_results) {
								
								$add_listing = true;
								
								// MLS Listing#
								if($search_results["MLS"] == "" || $search_results["MLS"] <= 0) {
									
									$add_listing = false;
								
								} else {
									
									// re-check if the listing is already added
									if($_SESSION['MLSIDs'][$search_results["MLS"]] != "") {
										
										$add_listing = false;
									
									} else {
									
										// Search - MLS Listing#
										if($_SESSION['property_search']['mlsid'] != "" && $_SESSION['property_search']['mlsid'] > 0) {
											
											if($search_results["MLS"] != $_SESSION['property_search']['mlsid']) {
												$add_listing = false;
											}
											
										} else {
											
											// Bathrooms - MIN
											if($search_results["Bathrooms"] < $_SESSION['property_search']['Bedrooms']['min']) {
												$add_listing = false;
											}
											
											// Bedrooms - MIN
											if($search_results["Bedrooms"] < $_SESSION['property_search']['Bedrooms']['min']) {
												$add_listing = false;
											}
																					
											// Address 
											if($_SESSION['property_search']['Address'] != "") {
											
												$found = 0;
												if(count($Search_Address) > 0) {
													foreach($Search_Address as $SAddress) {
														if($SAddress != "") {
															if (preg_match("/".$SAddress."/i", $search_results["Address"])) {
																$found++;
															}
															if($Street_Suffixes[$SAddress] != "") {
																if (preg_match("/".$Street_Suffixes[$SAddress]."/i", $search_results["Address"])) {
																	$found++;
																}
															}
														}
													}
													if($found <= 0) {
														$add_listing = false;
													}
												}
													
											}  // end if - address
										
										} // end if - Search - MLS Listing#
										
									} // end if - MLS id already stored
								
								} // end if - MLS Listing#
								
								// check if pass all filter
								if($add_listing == true) {
								
									// store into the search result listings session
									$_SESSION['listings'][] = $search_results;
									
									// store temporary MLS id to avoid duplicate result listing
									$_SESSION['MLSIDs'][$search_results["MLS"]] = $search_results["MLS"]; // $search_results; 
									
								}
								
							} // end foreach
						
						} else {
							$continue = false;
						} // end if
						
					} else {
						$continue = false;
					}
					
				} // end if - 
				
				// count & check listings total 
				if(count($_SESSION['listings']) > ($limit * $pg)) { 
					
					$continue = false; // exit while
				
				} else {
				
					// xml page counter
					$page++;
					
				}
			
			} // end while
			
		} // end foreach	

	} // end if - status


/** 
 |------------------------------------------------------------------------------------------------
 | Search Results: Pagination
 |------------------------------------------------------------------------------------------------
*/

	$listings = $_SESSION['listings'];
	$total_listings = count($listings);
	
	// check if current page value is greater than 1 (page 2 and above)
	if($pg > 1) {
		$offset = (($pg - 1) * $limit);// set the start number of displaying records
	} else {
		$offset = 0; // default: page 1 will start displaying records from record 1
	}
	
	// pagination >> get & set the listings that will be lists
	$rec_min = $offset;
	if($total_listings > $limit) {		 
		$rec_max = ($offset) + $limit;
	} else {
		$rec_max = $total_listings;
	}
	
	$n = $rec_min; // counter
	$c = 0;

	// set current result page number
	$_SESSION['current_search_results_page'] = $pg;


// =======================================================================================================================================
?>

<div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
	<link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
</div>

<div class="property_results">
     
    <div id="div_loading" style="padding:50px; text-align:center; vertical-align:middle;">
    	<h1 style="text-align:center;">Loading...</h1>
    	<img src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/loading.gif" alt="Loading Search Results..." title="Loading Search Results..." />
    </div>
        
    <div id="div_results" style="display:none;">
    
        <div style="margin:0px;">
            <div class="clearfix">&nbsp;</div>
            <form name="form_sort" id="form_sort" method="post" action="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_results']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_results']; ?>/" style="margin:0px; padding:0px;">
                <div style="float:left; width:auto;">
                    <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_search']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_search']; ?>/" style="font-size:14px; font-weight:bold;">&laquo; Change Search Criteria</a>
                </div>
				<?php if(count($listings) > 0) { // count & check if has search results (listings) ?>
                    <div style="float:right; width:auto;">
                        <select id="sort" name="sort" onchange="javascript:document.form_sort.submit();">
                            <option value="">Newest First</option>
                            <option value="ol" <?php if($_SESSION['property_search']['sort'] == "ol") { echo 'selected="selected"'; } ?>>Oldest First</option>
                            <option value="pl" <?php if($_SESSION['property_search']['sort'] == "pl") { echo 'selected="selected"'; } ?>>Price Lowest to Highest</option>
                            <option value="ph" <?php if($_SESSION['property_search']['sort'] == "ph") { echo 'selected="selected"'; } ?>>Price Highest to Lowest</option>
                        </select>
                        <input type="hidden" name="submit_sort" id="submit_sort" value="sort" />
                	</div>
                	<div style="float:right; width:auto;">
                        Sort by:&nbsp;&nbsp;
                	</div>
				<?php } ?>
            </form>
            <div class="clearfix">&nbsp;</div>
        </div>
        
        <div class="property_line-separator">&nbsp;</div>
       
        <?php if(count($listings) > 0) { // count & check if has search results (listings) ?>
         
            <?php while($n < $rec_max) { ?> 
                
                <?php $listing = $listings[$n]; ?>
                
				<?php if($listing["MLS"] != "") { ?>
                    
                    <div class="property_results-item" id="#<?=$n+1;?>">
                        <div class="property_results-thumb-container">
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]; ?>/<?php echo urlencode($listing["Address"]); ?>/">
                                <?php if(strstr($listing["DefaultThumbnailURL"], $listing["MLS"])) { ?>
                                    <img height="125" border="0" width="125" class="property_results-thumbnail" src="<?php echo $listing["DefaultThumbnailURL"]; ?>" style="margin:0px; padding:0px; border:none;" />
                                <?php } else { ?>
                                    <img height="125" border="0" width="125" class="property_results-thumbnail" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/no_image_available.jpg" style="margin:0px; padding:0px; border:none;" />
                                <?php } ?>
                            </a>
                        </div>
                        <div class="property_results-detail"> 
                            <a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_details']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_details']; ?>/<?php echo $listing["MLS"]; ?>/<?php echo urlencode($listing["Address"]); ?>/">
                                <?php echo $listing["Address"]; ?>
                            </a>
                            <br/><? if ($listing["Bedrooms"]) { ?><?php echo number_format($listing["Bedrooms"], 0, '', ''); ?> Bedroom,<? } ?> <? if ($listing["Bathrooms"]) { ?> <?php echo number_format($listing["Bathrooms"], 0, '', ''); ?> Bath <? } ?>
                            <br/><?php echo $listing["PropertyType"]; ?>
                            <br/>Offered at: $<?php echo number_format($listing["ListingPrice"], 2); ?> 
                        </div>
                    </div>
                    
                    <div class="clearfix">&nbsp;</div>
               		<?php $c++; // counter ?>
                
				<?php } ?>
                
                <?php $n++; // counter ?>
        
            <?php } // end while ?>
                    
            <?php if($c <= 0) { ?>  
                <div class="property_results-noresult" style="margin:30px 0px 0px;">No Result Found</div>
            <?php } ?> 

            <div class="clearfix" style="height:10px;">&nbsp;</div>
           
            <!-- Pagination -->
            <div class="property_results-pagination">
            	<?php if($pg > 1) { ?>
                	<a href="index.php?pg=<?php echo $pg - 1; ?>" style="font-size:11px;"><span style="font-size:14px;">&laquo;</span> PREV</a>
                <?php } ?>
                <?php if($pg > 1 && $total_listings > 0 && $c >= 30) { ?>
                	<span style="font-size:10px;">|</span>
                <?php } ?>
                <?php if($total_listings > 0 && $c >= $limit) { ?>
                	<a href="?pg=<?php echo $pg + 1; ?>" style="font-size:11px;">NEXT <span style="font-size:14px;">&raquo;</span></a>
           		<?php } ?>
            </div>
            
            <div class="clearfix">&nbsp;</div>
                            
        <?php } else { ?>  
            
            <div class="property_results-noresult">
                No Result Found
				<?php if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { ?>
                    <div style="font-size:10px; color:#990000;">
                        AgentEasy Properties Plugin API Key is not set.
                    </div>
                <?php } ?>
            </div>
                  
        <?php } ?> 
       
	</div>
            
</div>

<script language="JavaScript">
document.getElementById('div_loading').style.display='none';
document.getElementById('div_results').style.display='block';
</script> 
