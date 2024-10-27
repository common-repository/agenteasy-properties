<div class="wrap">

    <div class="icon32" id="icon-edit"><br></div>
    <h2>Add Property</h2>
   
    <p><a href="admin.php?page=agenteasy-properties/properties.php">&laquo; Back to Properties</a></p>
        
    <br/>

    <div id="div_add_loading" style="text-align:center;">
    	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
        	<tr class="alternate">
                <td style="padding:40px;">
                	<img src="<?php echo AEM_PLUGIN_URL; ?>/images/circle-loading.gif" alt="Loading..." title="Loading..." border="0" />             
    			</td>
            </tr>
        </table>	
    </div>
    
    <div id="div_add_form" style="display:none;">	
    
		<?php
        /**
        * ----------------------------------------------------------------------------------------------------------------------
        * Function To Process Image Uploading into WP Media Library
        * ----------------------------------------------------------------------------------------------------------------------
        */
        
            function add_xml_image_to_wp_media_ibrary($image, $id) { 
            
                require_once(ABSPATH . '/wp-admin/includes/file.php');
                require_once(ABSPATH . '/wp-admin/includes/media.php');
                require_once(ABSPATH . '/wp-admin/includes/image.php');
            
                $results = array();
            
                $binary_data = @file_get_contents($image);
            
                $name = basename($image); // name
                
                $getimagesize = @getimagesize($image_url); // type
                $type = $getimagesize['mime'];
                
                $tmp_name = tempnam(dirname(__FILE__)."/tmp", $img_name); // tmp_name
                $handle = @fopen($tmp_name, "w");
                @fwrite($handle, $binary_data);
                @fclose($handle);
                
                $size = @filesize($tmp_name); // size
        
                 //array to mimic $_FILES
                $array = array(
                    'name' 		=> $name,		
                    'type' 		=> $type, 		
                    'tmp_name' 	=> $tmp_name, 	
                    'error' 	=> 0, 			
                    'size' 		=> $size 
                );
        
                //the actual image processing, that is, move to upload directory, generate thumbnails and image sizes and writing into the database happens here
                $mhs = media_handle_sideload($array, $id); 
            
                // store thr processing result/details into an array
                $results = array('file' => $array, 'id' => $mhs);
            
                @unlink($tmp_name);
            
                return $results;
                
            }
        
            
        /**
        * ----------------------------------------------------------------------------------------------------------------------
        *  Variables
        * ----------------------------------------------------------------------------------------------------------------------
        */
        
            $MLSID 					= 0;	
            $mlsid_listing 			= array();	
            $property_id 			= 0;	
            $error 					= 0;
            $wp_plugin_aem_params 	= wp_plugin_aem_params();
            
        
        /**
        * ----------------------------------------------------------------------------------------------------------------------
        * Process >> Get Property
        * ----------------------------------------------------------------------------------------------------------------------
        */
        
            // check if 
            if(isset($_GET['mlsid']) && !empty($_GET['mlsid']) && !isset($_POST['action'])) {
                
                $MLSID = $_GET['mlsid'];
            
                // set & parse the xml 
                $xml_query_url = "property?mlsid=".$MLSID;
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
                        
                        echo '<div id="message" class="updated fade"><p><strong>';
                        echo 'Property details successfully retrieved. Click the \'Add Property\' button to save the property details.';
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
        * Process >> Add Property
        * ----------------------------------------------------------------------------------------------------------------------
        */
        
            if($_POST['action'] == 'insert') {
            
                $error = 0;
                
                // check if MLS is set
                if(!isset($_POST['Listing']['MLS']['value']) || empty($_POST['Listing']['MLS']['value']) || $_POST['Listing']['MLS']['value'] <= 0) {
                    //$error++;
                }
                
                // check if Address is set
                if(!isset($_POST['Listing']['Address']['value']) || empty($_POST['Listing']['Address']['value'])) {
                    $error++;
                }
        
                // check if no duplicate on the database
                $add_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE MLS = '".$_POST['Listing']['MLS']['value']."' AND address = '".$_POST['Listing']['address']['value']."' LIMIT 1", ARRAY_A);
                if(count($add_mlsid_listings) > 0) { $error++; }
                
                //check if no error
                if(error == 0) {
                
                    $_POST_Listing = $_POST['Listing']; 
                    $_POST_Listing['Description']['value'] = $_POST['content'];
                    
                    $POST_Listing =  base64_encode(serialize($_POST_Listing)); 
                    
                    #echo "<pre>"; print_r($_POST_Listing); echo "</pre>"; exit(); // for testing
                    
                    if($POST_Listing == '') {
                        $error++;
                    }
                    
                    if(!isset($_POST['Listing']['Address']['value']) || empty($_POST['Listing']['Address']['value'])) {
                        $error++;
                    }
            
                    if($error == 0) {
                
                        $sql = "INSERT INTO ".AEM_PLUGIN_DB_Table." (
                                    MLS,
                                    Title,
                                    URL,
                                    DefaultImageURL,
                                    DefaultThumbnailURL,
                                    PropertyType,
                                    Address,
                                    Bedrooms,
                                    Bathrooms,
                                    ListingPrice,
                                    ListingDate,
                                    SellingPrice,
                                    SoldDate,
                                    Description,
                                    Status,
                                    ListingAgent,
                                    ListingOffice,
                                    Represented,
                                    Comment,
                                    full_property_details,
                                    date_updated, 
                                    date_added
                                ) VALUES(
                                    '".aem_clean($_POST['Listing']['MLS']['value'])."',
                                    '".aem_clean($_POST['Listing']['Title']['value'])."',
                                    '".aem_clean($_POST['Listing']['URL']['value'])."',
                                    '".aem_clean($_POST['Listing']['Photos']['Photo'][0]['URL']['value'])."',
                                    '".aem_clean($_POST['Listing']['Photos']['Photo'][0]['ThumbnailURL']['value'])."',
                                    '".aem_clean($_POST['Listing']['PropertyType']['value'])."',
                                    '".aem_clean($_POST['Listing']['Address']['value'])."',
                                    '".aem_clean($_POST['Listing']['Bedrooms']['value'])."',
                                    '".aem_clean($_POST['Listing']['Bathrooms']['value'])."',
                                    '".aem_clean($_POST['Listing']['ListingPrice']['value'])."',
                                    '".aem_clean($_POST['Listing']['ListingDate']['value'])."',
                                    '".aem_clean($_POST['Listing']['SellingPrice']['value'])."',
                                    '".aem_clean($_POST['Listing']['SoldDate']['value'])."',
                                    '".aem_clean($_POST['content'])."',
                                    '".aem_clean($_POST['Listing']['Status']['value'])."',
                                    '".aem_clean($_POST['Listing']['ListingAgent']['value'])."',
                                    '".aem_clean($_POST['Listing']['ListingOffice']['value'])."',
                                    '".aem_clean($_POST['Listing']['Represented']['value'])."',
                                    '".aem_clean($_POST['Listing']['Comment']['value'])."',
                                    '".$POST_Listing."',
                                    CURDATE(), 
                                    CURDATE()
                                )";
                
                        $wpdb->query($sql);
                        
                        $property_id = $wpdb->insert_id;
                        
                        if($property_id > 0) {	
                        
                            $photo_updated = 0;
                            
                            $POST['Listing']['Photos']['Photo'] = array();
                        
                            if(count($_POST['Listing']['Photos']['Photo']) > 0) {
                                foreach($_POST['Listing']['Photos']['Photo'] as $key => $val) {
                                    
                                    $image = $_POST['Listing']['Photos']['Photo'][$key]['URL']['value'];
                                    
                                    if($image != AEM_PLUGIN_URL."/images/no_image_available.jpg") {
                                        if(@fopen($image,'r') !== false) { 
                                            
                                            // upload into wp media library
                                            $add_to_wpml_result = add_xml_image_to_wp_media_ibrary($image, $id);
											
											#echo '<hr/>'; echo '<pre>'; print_r($add_to_wpml_result); echo '</pre>';
                                            
                                            // check if added into wp media library 
                                            if(is_int($add_to_wpml_result['id'])) {
                                                if($add_to_wpml_result['id'] > 0) {
                                                
													#echo '<p>Image added into WP Media Library.</p>';
												
                                                    // get image
                                                    @list( $img_src, $width, $height ) = image_downsize($add_to_wpml_result['id'], 'full');
                                                    
                                                    if(@fopen($img_src,'r') !== false) { 
                                                        $image = $img_src; // update the value of the image url
                                                        $photo_updated++;
														#echo '<p>Image retrived from WP Media Library. ('.$image.')</p>';
                                                    } else {
														#echo '<p>Failed getting Image URL from WP Media Library.</p>';
													}
                                                    
                                                } else {
													#echo '<p>Failed adding image into WP Media Library.</p>';
												} 
                                            } else {
												#echo '<p>Failed adding image into WP Media Library.</p>';
											} 
                                    
                                       	} else {
											#echo '<p>Image doesn\'t exists.</p>';
									    } 
                                    } else {
										//echo '<p>Image is ignore. </p>';
									} 
                                    
                                    // update the property photos array ....
                                    $POST['Listing']['Photos']['Photo'][] = array('URL' => array('value' => $image), 'ThumbnailURL' => array('value' => $image));
                                
                                } // end foreach
                            } // end if
                            
                            if($photo_updated > 0) {
							
								#echo '<p>Updating Property Protos...</p>';
                            
                                // update the property db table record ....
                                $_POST_Listing['Photos']['Photo'] = $POST['Listing']['Photos']['Photo']; // updated property photos
								$POST_Listing =  base64_encode(serialize($_POST_Listing)); // updated full_property_details
                    
                                $update = $wpdb->query("UPDATE ".AEM_PLUGIN_DB_Table." SET
                                                         DefaultImageURL 		= '".aem_clean($_POST_Listing['Photos']['Photo'][0]['URL']['value'])."',
                                                         DefaultThumbnailURL 	= '".aem_clean($_POST_Listing['Photos']['Photo'][0]['ThumbnailURL']['value'])."',
                                                         full_property_details 	= '".$POST_Listing."'
                                                        WHERE id = '".aem_clean($property_id)."'
                                                        LIMIT 1");
                            
                            	#echo '<p>Property Protos Updated.</p>';
							
							} else {
								#echo '<p>Property Protos Not Updated.</p>';
							}
                            
                        } else {
							#echo '<p>Failed Adding Property.</p>';
						}
                        
						// for testing/checking only
                        #echo '<pre>'; print_r($_POST); echo '</pre>';
                        #echo 'SQL: '.$sql.', MEMBER ID: '.$agents_id;
                    
                    }
                
                }
                
                if($property_id > 0) {
                    
                    echo '<div id="message" class="updated fade"><p><strong>';
                    echo 'Property Successfully Added.';
                    echo '</strong></p></div>';
                
                } else {
                    
                    $add_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." 
                                                              WHERE MLS = '".$_POST['Listing']['MLS']['value']."' 
                                                              AND Address = '".$_POST['Listing']['Address']['value']."' 
                                                              LIMIT 1", ARRAY_A);
                    
                    if(count($add_mlsid_listings) > 0) {
                        echo '<div id="message" class="updated fade"><p><strong>';
                        echo 'Property already exists. <a href="admin.php?page=agenteasy-properties/edit-property.php&id='.$add_mlsid_listings[0]['id'].'" style="text-decoration:none;">[view property]</a>';
                        echo '</strong></p></div>';
                    }
            
                    echo '<div id="message" class="updated fade"><p><strong>';
                    echo 'Failed Adding Property.';
                    echo '</strong></p></div>';
                    
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
            
        ?>
    
		<?php if($property_id <= 0) { ?>
			<form name="form_get_mlsid_details" id="form_get_mlsid_details" method="post"  action="" />
   				<table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
                    <tr class="alternate">
                        <th scope="row" width="120" style="font-size:13px; text-align:right;">
                            Property MLSID:
                        </th>
                        <th scope="row"  style="font-size:14px; text-align:left;">
                            <input type="text" name="MLSID" id="MLSID" value="<?php echo $MLSID; ?>" style="border:1px solid #CCCCCC; font-size:14px; padding:3px; height:30px; width:100px; text-align:center;" />
                            <input type="button" value="Get MLSID Property details from XML" name="submit_get_mlsid_details"  class="button-primary" style="padding:5px;" onclick="javascript: if(confirm('Get MLSID Property details from XML?')) { submit_get_mlsid(); } " />
                        </th>
                    </tr>
         		</table>   
            </form>
            <script type="text/javascript">
            function submit_get_mlsid() {
                if(document.form_get_mlsid_details.MLSID.value > 0) {  
                    document.getElementById('div_add_form').style.display = 'none';
                    document.getElementById('div_add_loading').style.display = 'block';
                    location.href='admin.php?page=agenteasy-properties/add-property.php&mlsid=' + document.form_get_mlsid_details.MLSID.value; 
                } else { 
                    alert('MLSID is empty!');
                }
            }
            </script>
            <br/>
		<?php } ?>
            
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
            <?php if($property_id > 0) { /* nothing to do... */ } else { ?>
                <tr class="alternate">
                    <th colspan="2" style="background:#D7D7D7; font-size:14px; text-align:left;">
                        Property Details:
                    </th>
                </tr>
            <?php } ?>
            <tr class="alternate">
                <td colspan="2" style="padding:10px 0px 0px;">
                
					<?php if(count($mlsid_listing) > 0 && $property_id <= 0) { ?>
                        <form name="form_add" id="form_add" method="post"  action="" onsubmit="javascript: return confirm('Are you sure?');" />
                            <input type="hidden" name="action" value="insert">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                               
                                <?php foreach($mlsid_listing as $property_key => $property_val) { ?>
                                    
                                    <?php if($property_key == "Photos") { ?> 
                                        
                                        <?php if(count($mlsid_listing['Photos']) > 0) {	?>
                                            <?php 
                                            if(@array_key_exists("URL", $mlsid_listing[$property_key]['Photo'])) {
                                                $mlsid_photos = $mlsid_listing[$property_key];
                                            } else {
                                                $mlsid_photos = $mlsid_listing[$property_key]['Photo'];
                                            }
                                            ?>
                                            <?php if(count($mlsid_photos) > 0) { ?>
                                                <tr <?php if($mlsid_photos[0]['URL']['value'] == AEM_PLUGIN_URL."/images/no_image_available.jpg") { echo 'style="display:none;"'; } ?>>
                                                    <th colspan="2" style="background-color:#DFDFDF; font-size:14px;">
                                                        Property Photos:
                                                        <span style="margin-left:10px; font-size:11px; font-weight:normal;">
                                                            The Following Retrieved XML Property Images will be added into Wordpress Media Library after form success submit.
                                                        </span>
                                                    </th>
                                                </tr>
                                                <tr <?php if($mlsid_photos[0]['URL']['value'] == AEM_PLUGIN_URL."/images/no_image_available.jpg") { echo 'style="display:none;"'; } ?>>
                                                    <td colspan="2">
                                                        <?php $pht = 0; ?>
                                                        <?php foreach($mlsid_photos as $mlsid_photo) { ?>
                                                            <input type="hidden" name="Listing[<?php echo $property_key; ?>][Photo][<?php echo $pht; ?>][URL][value]" id="Listing_Photo_URL_<?php echo $pht; ?>" value="<?php echo $mlsid_photo['URL']['value']; ?>" />
                                                            <input type="hidden" name="Listing[<?php echo $property_key; ?>][Photo][<?php echo $pht; ?>][ThumbnailURL][value]"  id="Listing_Photo_ThumbnailURL_<?php echo $pht; ?>" value="<?php echo $mlsid_photo['ThumbnailURL']['value']; ?>" />
                                                            <a href="<?php echo $mlsid_photo['URL']['value']; ?>" target="_blank" style="text-decoration:none; border:none;">
                                                                <img src="<?php echo $mlsid_photo['ThumbnailURL']['value']; ?>" alt="" title="<?php echo basename($mlsid_photo['URL']['value']); ?>" style="float:left; margin:2px; border:2px solid #CCCCCC; width:50px; cursor:pointer;" onclick="javascript:updateDefaultPropertyPhoto('<?php echo $mlsid_photo['URL']['value']; ?>', '<?php echo $mlsid_photo['ThumbnailURL']['value']; ?>');" />
                                                            </a> 
                                                            <?php $pht++; ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr> 
                                            <?php } ?>   
                                        <?php } ?>        
                                           
                                    <?php } elseif($property_key == "Represented") { ?> 
                                       
                                        <tr>
                                            <td align="right" width="15%"><strong>Represented:</strong></td>
                                            <td>
                                                <input type="radio" name="Listing[Represented][value]" value="Seller" checked="checked" /> Seller
                                                &nbsp;&nbsp;
                                                <input type="radio" name="Listing[Represented][value]" value="Buyer" /> Buyer
                                                &nbsp;&nbsp;
                                                <input type="radio" name="Listing[Represented][value]" value="Both" /> Both
                                            </td>
                                        </tr>
                                   
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
                                
                               <tr>
                                    <th colspan="2" style="background-color:#DFDFDF; font-size:14px;">&nbsp;</th>
                                </tr>
                               <tr>
                                    <td colspan="2" style="padding:10px; border-top:1px solid #DFDFDF;">
                                        <script type="text/javascript">
                                        function submit_add_form() {
                                            document.getElementById('div_add_form').style.display = 'none';
                                            document.getElementById('div_add_loading').style.display = 'block';
                                            document.form_add.submit();
                                        }
										function submit_reset_form() {
                                            document.getElementById('div_add_form').style.display = 'none';
                                            document.getElementById('div_add_loading').style.display = 'block';
                                            location.href='admin.php?page=agenteasy-properties/add-property.php';
                                        }
                                        </script>
                                        <input type="hidden" value="Add Property" name="submit_add" />
                                        <input type="button" value="Add Property" name="btn_add"  class="button-primary" onclick="javascript: if(confirm('Submit?')) { submit_add_form(); }" />
                                        <input type="button" value="Reset Form" name="btn_reset" class="button-primary" onclick="javascript: if(confirm('Reset Form?')) { submit_reset_form(); }" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                        
                    <?php } else { ?> 
                	
						<?php  if($property_id > 0) { ?>
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                                <tr>
                                    <td style="padding:40px; font-size:18px; text-align: center; font-weight:bold; color:#333333; font-family:Georgia, 'Times New Roman', Times, serif;">
                                       <img src="<?php echo AEM_PLUGIN_URL; ?>/images/tick.png" alt="" title="" border="0" />
                                       &nbsp;Property Successfully Added. 
                                       <br/><br/>
                                       <a href="admin.php?page=agenteasy-properties/edit-property.php&id=<?php echo $property_id; ?>" style="text-decoration:none;">View/Edit Property</a>
                                    </td>
                                </tr> 
                            </table>                
    
                        <?php } else { ?>
                            
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat" style="border:none; margin:0px;">
                                <tr>
                                    <td style="padding:20px; font-size:12px; text-align:center; color:#999999;">
                                        No Property Found.
                                    </td>
                                </tr> 
                            </table>  
                                          
                        <?php } ?>
                                                           
                    <?php } ?>                    
                
                </td>
            </tr>
        </table>   
	
    </div>
    
    <p>&nbsp;</p>       
    
</div>
<script type="text/javascript">
document.getElementById('div_add_form').style.display = 'block';
document.getElementById('div_add_loading').style.display = 'none';
</script>

