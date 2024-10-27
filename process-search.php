<?php 
/** 
 |------------------------------------------------------------------------------------------------
 | Search Property: Process search query
 |------------------------------------------------------------------------------------------------
*/
	
	// redirect to search results page
	$continue_redirect = false;
	
	// form search filter
	if(isset($_POST['property_search'])) {
		
		$_SESSION['listings'] = array();
		$_SESSION['MLSIDs'] = array();
		$_SESSION['listing_results'] = array();
		$_SESSION['xml_pages'] = array();
		
		$_SESSION['property_search']['neighborhoods'] 		= $_POST['neighborhoods'];
		$_SESSION['property_search']['type'] 				= $_POST['type'];
		$_SESSION['property_search']['Address'] 			= $_POST['Address'];
		$_SESSION['property_search']['mlsid'] 				= $_POST['mlsid'];
		
		if($_POST['ListingPrice']['min'] > 0) { 
			$ListingPrice_min = $_POST['ListingPrice']['min']; 
		} else { 
			$ListingPrice_min = 0; 
		}
		$_SESSION['property_search']['ListingPrice']['min']	= $ListingPrice_min;
		
		if($_POST['ListingPrice']['max'] > 0) { 
			$ListingPrice_max = $_POST['ListingPrice']['max']; 
		} else { 
			$ListingPrice_max = 0; 
		}
		$_SESSION['property_search']['ListingPrice']['max'] = $ListingPrice_max;
	
		if($_POST['Bedrooms']['min'] > 0) { 
			$Bedrooms_min = $_POST['Bedrooms']['min']; 
		} else { 
			$Bedrooms_min = 0; 
		}
		$_SESSION['property_search']['Bedrooms']['min'] 	= $Bedrooms_min; 
		
		if($_POST['Bathrooms']['min'] > 0) { 
			$Bathrooms_min = $_POST['Bathrooms']['min']; 
		} else { 
			$Bathrooms_min = 0; 
		}
		$_SESSION['property_search']['Bathrooms']['min']	= $Bathrooms_min; 
		
		// redirect to search results page
		$continue_redirect = true;

	}

?>