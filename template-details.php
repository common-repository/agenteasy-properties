<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Template for Property DETAILS Page
* ----------------------------------------------------------------------------------------------------------------------
*/
?>


<?php 
// for testing only ------------------------------------------------------------------------------	
//	
//	// set default value as null
//	$mlsid = 0;	
//	$address = "";	
//	
//	// get the url params on the property details page
//	$aem_property_details = get_query_var('aem_property_details');
//	$property_vars = explode('/',$aem_property_details);
//	
//	// check if 
//	if(is_array($property_vars)) {
//	
//		echo '<pre>'; print_r($property_vars); echo '</pre>'; 
//		
//		// get the mlsid on property url (permalink)
//		if(count($property_vars) > 0) {
//			if($property_vars[0] > 0) {
//				$mlsid = $property_vars[0];	
//			}
//			
//		}
//		
//		// get the address on property url (permalink)
//		if(count($property_vars) > 1) {
//			if($property_vars[1] != "") {
//				$address = urldecode($property_vars[1]);	
//			}
//		}
//		
//	}
//	
//   echo "<br/><strong>mlsid:</strong> $mlsid"; echo "<br/><strong>address:</strong> $address"; 
//	
// -----------------------------------------------------------------------------------------------	

if(count($db_listings['details_listings']) > 0) {
		
	foreach($db_listings['details_listings'] as $db_details_listing) {
		$listing = unserialize(base64_decode($db_details_listing['full_property_details']));
	}
	
	// is photos array nested or not
	if(array_key_exists("URL", $listing["Photos"]["Photo"])) {
		$photos = $listing["Photos"];
	} else {
		$photos = $listing["Photos"]["Photo"];
	}
	
	
	// exclude the no_image_available.jpg
	 $photos_new = array();
	 if(count($photos) > 0) {
	  foreach($photos as $photo) {
	   if($photo["URL"]["value"] != $wp_plugin_aem_params['AEM_PLUGIN_URL']."/images/no_image_available.jpg") {
		$photos_new[] = $photo; 
	   }
	  }
	 }
	 if(count($photos_new) > 0) {
	  $photos = $photos_new;
	 }	
	
	
	#echo "<pre>"; print_r($listing); echo "</pre>";

} else { 

	// set & parse the xml 
	$xml_query_url = "property?mlsid=".$mlsid;
	$xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].urlencode($xml_query_url.'&apikey='.$wp_plugin_aem_params['plugin_aem_option_api_key']);
	#echo '<br/><a href="'.$xml_url.'">'.$xml_url.'</a>';
	
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
				$listing = $arr["Listing"];
			} else {
				$listing = $arr["Listing"];
			}
			
		}
	}

	// check listing exists/found 
	if(count($listing) > 0) {
	
		if(count($arr["Listing"]["Photos"]["Photo"]) > 0) {	
		
			// is photos array nested or not
			if(array_key_exists("URL", $arr["Listing"]["Photos"]["Photo"])) {
				$photos = $arr["Listing"]["Photos"];
			} else {
				$photos = $arr["Listing"]["Photos"]["Photo"];
			}
			
		}	
		
	}
	
}


$_SERVER_HTTP_REFERER = $_SERVER['HTTP_REFERER'];

?>

<div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
	<link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
</div>

<div class="property_details">

    <?php if($_SERVER_HTTP_REFERER != "") { ?>
    	<p><a href="<?php echo $_SERVER_HTTP_REFERER; ?>" style="font-size:14px; font-weight:bold;">&laquo; Return to Property Results</a></p>    
    <?php } else { ?>
        <p><a href="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_results']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_results']; ?>/<?php echo $back_page; ?>" style="font-size:13px; font-weight:bold;">&laquo; Return to Search Results</a></p>    
  	<?php } ?> 
     
    <?php if(count($listing) > 0) { // check listing exists/found ?>
    
        <h2 style="margin-bottom:5px;"><?php if($listing["Title"]["value"] != "") { echo $listing["Title"]["value"]; } else { echo $listing["Address"]["value"] ;} ?> - $<?php echo number_format($listing["ListingPrice"]["value"]); ?></h2>
        
        <?php if($listing["Title"]["value"] != "") { ?>
            <strong><?php echo $listing["Address"]["value"]; ?></strong><br>
		<?php } ?>
       
        <br>
        
		<?php if ($listing["Bedrooms"]["value"]) { ?>
            <?php echo number_format($listing["Bedrooms"]["value"], 0, '', ''); ?> Beds,
        <?php } ?>
        <?php if ($listing["Bathrooms"]["value"]) { ?>
            <?php echo number_format($listing["Bathrooms"]["value"], 0, '', ''); ?> Baths
        <?php } ?>
        
        <?php if ($listing["Status"]["value"]) { ?>
            <br>Status: <?php echo $listing["Status"]["value"]; ?>
        <?php } ?>

        <br>MLS Listing#:  <?php echo $listing["MLS"]["value"]; ?>
      <?php if($listing["SellingPrice"]["value"]) { ?>
        <br>Sold Price:   $<?php echo number_format($listing["SellingPrice"]["value"]); ?>
		
	
    <?php } ?>    
        
        <br>
        
        <?php if ($listing["Description"]["value"]) { ?>
        	<div class="clear">&nbsp;</div>
            <p><?php echo $listing["Description"]["value"];?></p>
        <?php } ?>
            
        <?php if(count($photos) > 0 && $photos[0]["URL"]["value"] != $wp_plugin_aem_params['AEM_PLUGIN_URL']."/images/no_image_available.jpg") { ?>	
           
            <div class="clear">&nbsp;</div>
       
            <?php if(count($photos) > 1) { ?>	
            	<p><strong>Photos</strong></p>
            <?php } ?>
            
            <?php if(count($photos) > 1) { ?>	
	<?php /*?>			<script type="text/javascript" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/js/jquery-1.5.2.min.js"></script>
                <script type="text/javascript">
                $.noConflict();
                jQuery(document).ready(function($) {
                    jQuery('#images_thumbnails a').click(function(){
                      var newImageSrc = jQuery(this).attr('href');
                      jQuery('#images_full img').attr({'src': newImageSrc });
                      return false;
                    });
                });
                </script><?php */?>
            <?php } ?>  
              
              <script type="text/javascript">
    function changeImage(a) {
        document.getElementById("main_img").src=a;
    }
</script>
              
              
            <div id="images_full" align="center">
				<?php $nphoto = 0; ?>
				<?php foreach($photos as $photo){ ?>
                    <?php $nphoto++; ?>
					<?php if($nphoto <= 1) { ?>
                        <img src="<?php echo $photo["URL"]["value"]; ?>" title="" alt="" id="main_img"/>
                    <?php } ?>
                <?php } ?>
            </div>
            
            <?php if(count($photos) > 1) { ?>	
                <div id="images_thumbnails">
                    <?php foreach($photos as $photo){ ?>
                       <?php /*?> <a href="<?php echo $photo["URL"]["value"]; ?>" style="text-decoration:none;"><?php */?>
                            <img width="70" src="<?php echo $photo["ThumbnailURL"]["value"]; ?>" title="" alt="" onclick='changeImage("<?php echo $photo["URL"]["value"]; ?>");' />
                 <?php /*?>       </a><?php */?>
                    <?php } ?>
                    <div class="clear">&nbsp;</div>
                </div>
            <?php } ?>  
              
        <?php } ?>
        
        <?php if($listing["Address"]["value"]) { //google map ?>            
          
            <div class="clear">&nbsp;</div>

            <p><strong>Location</strong></p>
            
            <script src="http://maps.google.com/maps/api/js?sensor=true"></script>		
            <script>
            function setMapAddress(address) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode( { address : address }, function( results, status ) {
                    if( status == google.maps.GeocoderStatus.OK ) {
                        var latlng = results[0].geometry.location;
                        var options = {
                            zoom: 15,
                            center: latlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP, 
                            streetViewControl: true
                        };
                        var mymap = new google.maps.Map( document.getElementById( 'map' ), options );   
                        var marker = new google.maps.Marker({
                        map: mymap, 
                        position: results[0].geometry.location
                    });		
                    }
                } );
            }
            setMapAddress( "<?php echo $listing["Address"]["value"]; ?>" );
            </script>
          
            <div id="location" class="singlecol right last">
                <div id="map" class="singlecol right last">Loading Map...</div>
            </div>
            <a href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo urlencode($listing["Address"]["value"]); ?>" target="_blank" style="text-decoration:none; font-size:11px;">View Larger Map</a>
            
        <?php } ?>    
         
       <div class="clear" style="height:30px;">&nbsp;</div>
               
<?php /*?>        <!-- AddThis Button BEGIN (remove addthis_32x32_style to have small icons) -->
        <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
            <a class="addthis_button_compact"></a> 
            <a class="addthis_button_facebook"></a>
            <a class="addthis_button_email"></a>
            <a class="addthis_button_twitter"></a>
            <a class="addthis_button_print"></a>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d91919a33ca085b"></script>
        </div>
        <!-- AddThis Button END --><?php */?>
    
        <div class="clear" style="height:30px;">&nbsp;</div>
       
        <div class="property_details-listing-courtesy">
            This listing courtesy of <?php echo $listing["ListingAgent"]["value"]; ?>, <?php echo $listing["ListingOffice"]["value"]; ?>
        </div>
        
        <div class="clear" style="height:30px;">&nbsp;</div>
        
	 <?php } else { ?>
    
     	<div class="property_details-noresult">
        	Property Not Found
            <?php if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { ?>
                <div style="font-size:10px; color:#990000;">
                    AgentEasy Properties Plugin API Key is not set.
                </div>
            <?php } ?>
        </div>
    
    <?php } ?> 
    
    <?php /*?>
    <div style="padding:10px; margin:10px; font-size:10px; border:1px solid #999999;">
        <pre><?php print_r($listing); ?></pre>
    </div>
    <?php */?>
     
</div>


