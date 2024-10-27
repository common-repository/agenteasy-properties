<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Template for SEARCH Property Page
* ----------------------------------------------------------------------------------------------------------------------
*/
?>
<?php 
/** 
 |------------------------------------------------------------------------------------------------
 | Array Sorter Class
 |------------------------------------------------------------------------------------------------
*/

	require_once(AEM_PLUGIN_PATH.'/array-sorter.php');

/** 
 |------------------------------------------------------------------------------------------------
 | Search Results: Search Query
 |------------------------------------------------------------------------------------------------
*/

	require_once(AEM_PLUGIN_PATH.'/process-search.php');

?>
<?php 
if($continue_redirect == true) { // this will run if search form submitted & search process is done 
	?>
<script type="text/javascript" language="javascript">
	window.parent.location.href = '<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_results']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_results']; ?>/';
	</script>
<?php 
} 
if(isset($_POST['property_search'])) {
 	exit();
}
?>
<script type="text/javascript" language="javascript">

document.getElementById('content').style.width='650px';

<?php if(isset($_POST['property_search'])) { ?>
    document.getElementById('div_loading').style.display='block';
    document.getElementById('div_search').style.display='none';
<?php } ?>

function submitSearchForm() {
	window.scrollTo(0,screen.height);
	document.getElementById('div_loading').style.display='block';
	document.getElementById('div_search').style.display='none';
	document.form_property_search.submit();
}

</script>


<div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
  <iframe name="iframe_results" id="iframe_results" src="<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_base_search']; ?>/<?php echo $wp_plugin_aem_params['plugin_aem_option_template_page_search']; ?>/" height="0" width="0" frameborder="0" scrolling="no"></iframe>
  <link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
</div>
<div class="property_search" id="property_search">
  <div id="div_loading" style="display:none; padding:50px; text-align:center; vertical-align:middle;">
    <h1 style="text-align:center;">Searching...</h1>
    <img src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/loading.gif" alt="Searching..." title="Searching..." /> </div>
  <div id="div_search">
    <?php if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { ?>
    <div class="property_details-noresult"> Search Property is Disabled
      <div style="font-size:10px; color:#990000;"> AgentEasy Properties Plugin API Key is not set. </div>
    </div>
    <?php } else { ?>
    <form name="form_property_search" id="form_property_search" action="" target="iframe_results" method="post" onsubmit="javascript: submitSearchForm();">
      <div class="property_search-criteria-box" style="width:200px; float:left; ">
        <div class="clearfix">&nbsp;</div>
        <?php 
                    // Property Types (select option values)
                    $property_types = array();
                    $property_types[] = "Single-Family Homes";
                    $property_types[] = "Condominium";
                    $property_types[] = "Tenancy In Common";
                    $property_types[] = "Loft Condominium";
                    $property_types[] = "2 Units";
                    $property_types[] = "3 Units";
                    $property_types[] = "4 Units";
                    $property_types[] = "Lots & Acreage";
                    ?>
        <?php if(count($property_types) > 0) {?>
        <select name="type" id="type">
          <option  value="" />Property Type</option>
          <?php foreach($property_types as $type) { ?>
          <?php 
                                if($_SESSION['property_search']['type'] == $type) { 
                                    $checked = 'selected="selected"'; 
                                } else {
                                    $checked = '';
                                }
                                ?>
          <option  value="<?php echo $type; ?>"  <?php echo $checked; ?> />
          <?php echo $type; ?>
          </option>
          <?php } ?>
        </select>
        <div class="clearfix">&nbsp;</div>
        <?php } ?>
        <div >
          <h2 class="property_h2">Price Range:</h2>
          <input name="ListingPrice[min]" type="text" class="price_range" style="width:35px; text-align:right;" value="<?php if($_SESSION['property_search']['ListingPrice']['min'] > 0) { echo $_SESSION['property_search']['ListingPrice']['min']; } else { echo '0'; } ?>" size="5" />
          ,000&nbsp;<strong>to</strong>&nbsp;
          <input name="ListingPrice[max]" type="text" class="price_range" style="width:35px; text-align:right;" value="<?php if($_SESSION['property_search']['ListingPrice']['max'] > 0) { echo $_SESSION['property_search']['ListingPrice']['max']; } else { echo '0'; } ?>" size="5" />,000</div>
        <div class="clearfix">&nbsp;</div>
        <div style="min-width:230px; width:auto; margin:auto; float:left;">
          <div style="width:auto; float:left; margin:auto 5px;">
            <h2 class="property_h2">Bedrooms:</h2>
            <select name="Bedrooms[min]" id="Bedrooms[min]" style="width:60px;">
              <option value="0">0+</option>
              <?php $n=1; while($n <= 4) { ?>
              <option value="<?=$n;?>" <?php if ($n == $_SESSION['property_search']['Bedrooms']['min']) { echo 'selected="selected"'; } ?>>
              <?=$n;?>
              +</option>
              <?php $n++; } ?>
            </select>
          </div>
          <div style="width:auto; margin:auto; float:left; margin:auto 5px;">
            <h2 class="property_h2">Bathrooms:</h2>
            <select name="Bathrooms[min]" id="Bathrooms[min]" style="width:60px;">
              <option value="0">0+</option>
              <?php $n=1; while($n <= 4) { ?>
              <option value="<?=$n;?>" <?php if ($n == $_SESSION['property_search']['Bathrooms']['min']) { echo 'selected="selected"'; } ?>>
              <?=$n;?>
              +</option>
              <?php $n++; } ?>
            </select>
          </div>
        </div>
        <div class="clearfix">&nbsp;</div>
        <?php /*?>       <div style="min-width:230px; width:auto; margin:auto; float:left; margin:5px;">
                        <h2 class="property_h2">Address / Location:</h2> 
                        <input type="text" name="Address" value="<?php echo $_SESSION['property_search']['Address']; ?>" style="width:220px;"  />
                    </div>
                     
                    <div class="clearfix">&nbsp;</div>
                   
                    <div style="min-width:230px; width:auto; margin:auto; float:left; margin:5px;">
                        <h2 class="property_h2">MLS Listing#</h2> 
                        <input type="text" name="mlsid" value="<?php echo $_SESSION['property_search']['mlsid']; ?>" style="width:150px;" />
                    </div>
                                


                    <div class="clearfix">&nbsp;</div>    <?php */?>
        <?php 
                    $option_xml_neighborhoods = array();
                    if($wp_plugin_aem_params['plugin_aem_option_xml_neighborhoods_serialize'] != "") {
                        $option_xml_neighborhoods = html_entity_decode($wp_plugin_aem_params['plugin_aem_option_xml_neighborhoods_serialize'],ENT_QUOTES);
                        $option_xml_neighborhoods = unserialize($option_xml_neighborhoods);
                    } 

					$district = array(); 
					if(count($option_xml_neighborhoods) > 0) {
						foreach($option_xml_neighborhoods as $neighborhood) {
							if($neighborhood['enable'] == "Yes") { 
								$district[$neighborhood['district']][$neighborhood['id']] = $neighborhood;
							}
						}
					} 
					?>
        <script language="JavaScript">
                    function showOrHide_divlinks(divlink) {
                     	if (document.getElementById) {
							var divid = document.getElementById(divlink).style;
							if (divid.display == "block") {
								divid.display = 'none';
						   	} else {
								divid.display = 'block';
							} 
                      		return false;
                     	} else {
                     	 	return true;
                     	}
                    }
         
					function toggleSelectDistrictNeighborhood(source, divlink, selectedDN) {
						document.getElementById(divlink).style.display = 'block';
						checkboxes = document.getElementById(divlink).getElementsByTagName('input');
						for (var i = 0; i < checkboxes.length; i++) {
							checkboxes[i].checked = source.checked;
						}
					}
					</script>
        <div class="clear" style="height:15px;">&nbsp;</div>
        <div class="property_search-selection" id="divlocations">
          <h2 class="property_h2" style="margin-bottom:10px;">Locations:</h2>
          <ul>
            <?php $d = 0; ?>
            <?php foreach($district as $district_key => $district_val) { ?>
            <?php $d++; ?>
            <li style="border-bottom:1px dashed #CCCCCC; padding:5px;">
              <?php 
									// count the neighborhood selected per district
									$property_search_destrict_neighborhoods = 0;
									foreach($district[$district_key] as $neighborhood) {
                                        if(count($_SESSION['property_search']['neighborhoods']) > 0) { 
                                            if(in_array($neighborhood['id'],$_SESSION['property_search']['neighborhoods'])) { 
                                                $property_search_destrict_neighborhoods++;
                                            } 
                                        }
                                    } 
									?>
              <input name="district[]" type="checkbox" value="<?php echo $district_key; ?>" <?php if($property_search_destrict_neighborhoods == count($district[$district_key])) { echo 'checked="checked"'; } ?> onclick="javascript: toggleSelectDistrictNeighborhood(this, 'divlink_<?php echo $d; ?>', '<?php echo $property_search_destrict_neighborhoods; ?>');" />
              <img src="<?php echo AEM_PLUGIN_URL; ?>/images/orange-arrow-right.gif" alt="" border="0" title="" /> <a style="color:#FF641A; font-weight:bold; text-decoration:none;" href="#" onclick="javascript: return showOrHide_divlinks('divlink_<?php echo $d; ?>');"> <?php echo $district_key; ?> </a>
              <div id="divlink_<?php echo $d; ?>" style=" <?php if($property_search_destrict_neighborhoods > 0) { echo 'display:block;'; } else { echo 'display:none;'; }?>">
                <ul style="margin:0px 10px;">
                  <?php foreach($district[$district_key] as $neighborhood) {?>
                  <?php 
												$checked = '';
                                                if(count($_SESSION['property_search']['neighborhoods']) > 0) { 
                                                    if(in_array($neighborhood['id'],$_SESSION['property_search']['neighborhoods'])) { 
                                                        $checked = 'checked="checked"'; 
                                                    } 
                                                }
                                                ?>
                  <li>
                    <label>
                      <input name="neighborhoods[]" type="checkbox" value="<?php echo $neighborhood['id']; ?>" <?php echo $checked; ?> />
                      <?php echo $neighborhood['title']; ?></label>
                  </li>
                  <?php } ?>
                </ul>
              </div>
            </li>
            <?php } ?>
          </ul>
        </div>
        <div class="clearfix" style="height:0px;">&nbsp;</div>
        <input type="submit" class="submit_property_search" name="property_search" value="Search" />
        <div class="clearfix">&nbsp;</div>
      </div>
      <div class="property_search-criteria-box" style="margin-left:20px; float:left;">
        <p><strong>City of San Francisco</strong></p>
        <img src="<?php echo AEM_PLUGIN_URL; ?>/media/map.png" width="340" ></div>
      <div class="clearfix">&nbsp;</div>
    </form>
    <?php } ?>
  </div>
</div>
