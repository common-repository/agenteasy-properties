<?php
/*
Plugin Name: AgentEasy Properties
Description: Property Search and Listing Manager. 
Version: 1.0.5
Plugin URI: http://wordpress.org/extend/plugins/agenteasy-properties/ 
Author: AgentEasy
Author URI: http://agenteasy.com
*/


/*  
	Copyright 2012  AgentEasy 
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; If not, see <http://www.gnu.org/licenses/>.
*/


/**
* ----------------------------------------------------------------------------------------------------------------------
* Enable & Start WP Session
* ----------------------------------------------------------------------------------------------------------------------
*/

	function cp_admin_init() {
		if (!session_id()) session_start();
	}
	
	add_action('init', 'cp_admin_init');
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Set Global variables
* ----------------------------------------------------------------------------------------------------------------------
*/

	/** wp global vars */
	global $wpdb;
	
	/** Max Execution Time */
	if(!ini_get('safe_mode')) {
		set_time_limit(0);
		ini_set('max_execution_time', '3600'); // 3600 seconds =  1 hour 
	}
	
	/** unlimited memory limit */
	ini_set('memory_limit', '-1'); 
	
	/** Plugin Version */
	define('AEM_PLUGIN_Version', "1.0");
	
	/** Plugin DB Table */
	define('AEM_PLUGIN_DB_Table', $wpdb->prefix . "aem_properties");
	
	/** Plugin Folder name */
	if (!defined('AEM_PLUGIN_FOLDER')) {
		define('AEM_PLUGIN_FOLDER', 'agenteasy-properties');
	}

	/** Plugin Title/Name */
	if (!defined('AEM_PLUGIN_TITLE')) {
		#define('AEM_PLUGIN_TITLE', 'AgentEasy Properties');
		define('AEM_PLUGIN_TITLE', 'AE Search');
	}
	
	/** Plugin Full URL */
	if (!defined('AEM_PLUGIN_URL')) {
		define('AEM_PLUGIN_URL', get_option('siteurl').'/wp-content/plugins/'.AEM_PLUGIN_FOLDER);
	}

	/** Plugin Full DIR Path */
	if (!defined('AEM_PLUGIN_PATH')) {
		define('AEM_PLUGIN_PATH', 'wp-content/plugins/'.AEM_PLUGIN_FOLDER);
	}

	/** Plugin Options */
	
	// set here the url where baycentric-web-service.php file is located 
	$baycentric_web_service = 'http://agenteasy.com/ext/property-search/baycentric-web-service.php'; 
		
	if (!defined('AEM_PLUGIN_OPTION_XML_PARSER')) {
		define('AEM_PLUGIN_OPTION_XML_PARSER', $baycentric_web_service.'?xml='); 
	}

	if (!defined('AEM_PLUGIN_OPTION_API_KEY')) {
		define('AEM_PLUGIN_OPTION_API_KEY', '');
	}

	if (!defined('AEM_PLUGIN_OPTION_XML_LIMIT')) {
		define('AEM_PLUGIN_OPTION_XML_LIMIT', 30);
	}

	if (!defined('AEM_PLUGIN_OPTION_XML_AGENT')) {
		define('AEM_PLUGIN_OPTION_XML_AGENT', 0);
	}

	if (!defined('AEM_PLUGIN_OPTION_XML_AGENT_ENABLE')) {
		define('AEM_PLUGIN_OPTION_XML_AGENT_ENABLE', 'No');
	}

	if (!defined('AEM_PLUGIN_OPTION_XML_STATUS')) {
		define('AEM_PLUGIN_OPTION_XML_STATUS', 'active,pending');
	}
	
	if (!defined('AEM_PLUGIN_OPTION_XML_NEIGHBORHOODS')) {
		
		$plugin_aem_option_xml_neighborhoods = array();
				
		$plugin_aem_option_xml_neighborhoods[1010] = array('enable' => 'Yes', 'id' => 1010, 'district' => 'District 1', 'title' => 'Central Richmond');
		$plugin_aem_option_xml_neighborhoods[1020] = array('enable' => 'Yes', 'id' => 1020, 'district' => 'District 1', 'title' => 'Inner Richmond');
		$plugin_aem_option_xml_neighborhoods[1030] = array('enable' => 'Yes', 'id' => 1030, 'district' => 'District 1', 'title' => 'Jordan Pk/Laurel');
		$plugin_aem_option_xml_neighborhoods[1040] = array('enable' => 'Yes', 'id' => 1040, 'district' => 'District 1', 'title' => 'Lake Street');
		$plugin_aem_option_xml_neighborhoods[1050] = array('enable' => 'Yes', 'id' => 1050, 'district' => 'District 1', 'title' => 'Outer Richmond');
		$plugin_aem_option_xml_neighborhoods[1060] = array('enable' => 'Yes', 'id' => 1060, 'district' => 'District 1', 'title' => 'Sea Cliff');
		$plugin_aem_option_xml_neighborhoods[1070] = array('enable' => 'Yes', 'id' => 1070, 'district' => 'District 1', 'title' => 'Lone Mountain');
		
		$plugin_aem_option_xml_neighborhoods[2010] = array('enable' => 'Yes', 'id' => 2010, 'district' => 'District 2', 'title' => 'Golden Gate Hts');
		$plugin_aem_option_xml_neighborhoods[2020] = array('enable' => 'Yes', 'id' => 2020, 'district' => 'District 2', 'title' => 'Outer Parkside');
		$plugin_aem_option_xml_neighborhoods[2030] = array('enable' => 'Yes', 'id' => 2030, 'district' => 'District 2', 'title' => 'Outer Sunset');
		$plugin_aem_option_xml_neighborhoods[2040] = array('enable' => 'Yes', 'id' => 2040, 'district' => 'District 2', 'title' => 'Parkside');
		$plugin_aem_option_xml_neighborhoods[2050] = array('enable' => 'Yes', 'id' => 2050, 'district' => 'District 2', 'title' => 'Central Sunset');
		$plugin_aem_option_xml_neighborhoods[2060] = array('enable' => 'Yes', 'id' => 2060, 'district' => 'District 2', 'title' => 'Inner Sunset');
		$plugin_aem_option_xml_neighborhoods[2070] = array('enable' => 'Yes', 'id' => 2070, 'district' => 'District 2', 'title' => 'Inner Parkside');
		
		$plugin_aem_option_xml_neighborhoods[3010] = array('enable' => 'Yes', 'id' => 3010, 'district' => 'District 3', 'title' => 'Lake Shore');
		$plugin_aem_option_xml_neighborhoods[3020] = array('enable' => 'Yes', 'id' => 3020, 'district' => 'District 3', 'title' => 'Merced Heights');
		$plugin_aem_option_xml_neighborhoods[3030] = array('enable' => 'Yes', 'id' => 3030, 'district' => 'District 3', 'title' => 'Pine Lake Park');
		$plugin_aem_option_xml_neighborhoods[3040] = array('enable' => 'Yes', 'id' => 3040, 'district' => 'District 3', 'title' => 'Stonestown');
		$plugin_aem_option_xml_neighborhoods[3050] = array('enable' => 'Yes', 'id' => 3050, 'district' => 'District 3', 'title' => 'Lakeside');
		$plugin_aem_option_xml_neighborhoods[3060] = array('enable' => 'Yes', 'id' => 3060, 'district' => 'District 3', 'title' => 'Merced Manor');
		$plugin_aem_option_xml_neighborhoods[3070] = array('enable' => 'Yes', 'id' => 3070, 'district' => 'District 3', 'title' => 'Ingleside Heights');
		$plugin_aem_option_xml_neighborhoods[3080] = array('enable' => 'Yes', 'id' => 3080, 'district' => 'District 3', 'title' => 'Ingleside');
		$plugin_aem_option_xml_neighborhoods[3090] = array('enable' => 'Yes', 'id' => 3090, 'district' => 'District 3', 'title' => 'Oceanview');
		
		$plugin_aem_option_xml_neighborhoods[4010] = array('enable' => 'Yes', 'id' => 4010, 'district' => 'District 4', 'title' => 'Balboa Terrace');
		$plugin_aem_option_xml_neighborhoods[4020] = array('enable' => 'Yes', 'id' => 4020, 'district' => 'District 4', 'title' => 'Diamond Heights');
		$plugin_aem_option_xml_neighborhoods[4030] = array('enable' => 'Yes', 'id' => 4030, 'district' => 'District 4', 'title' => 'Forest Hill');
		$plugin_aem_option_xml_neighborhoods[4040] = array('enable' => 'Yes', 'id' => 4040, 'district' => 'District 4', 'title' => 'Forest Knolls');
		$plugin_aem_option_xml_neighborhoods[4050] = array('enable' => 'Yes', 'id' => 4050, 'district' => 'District 4', 'title' => 'Ingleside Terrace');
		$plugin_aem_option_xml_neighborhoods[4060] = array('enable' => 'Yes', 'id' => 4060, 'district' => 'District 4', 'title' => 'Midtown Terrace');
		$plugin_aem_option_xml_neighborhoods[4070] = array('enable' => 'Yes', 'id' => 4070, 'district' => 'District 4', 'title' => 'St. Francis Wood');
		$plugin_aem_option_xml_neighborhoods[4080] = array('enable' => 'Yes', 'id' => 4080, 'district' => 'District 4', 'title' => 'Miraloma Park');
		$plugin_aem_option_xml_neighborhoods[4090] = array('enable' => 'Yes', 'id' => 4090, 'district' => 'District 4', 'title' => 'Forest Hill Ext');
		$plugin_aem_option_xml_neighborhoods[4100] = array('enable' => 'Yes', 'id' => 4100, 'district' => 'District 4', 'title' => 'Sherwood Forest');
		$plugin_aem_option_xml_neighborhoods[4110] = array('enable' => 'Yes', 'id' => 4110, 'district' => 'District 4', 'title' => 'Monterey Heights');
		$plugin_aem_option_xml_neighborhoods[4120] = array('enable' => 'Yes', 'id' => 4120, 'district' => 'District 4', 'title' => 'Mount Davidson Manor');
		$plugin_aem_option_xml_neighborhoods[4130] = array('enable' => 'Yes', 'id' => 4130, 'district' => 'District 4', 'title' => 'Westwood Highlands');
		$plugin_aem_option_xml_neighborhoods[4140] = array('enable' => 'Yes', 'id' => 4140, 'district' => 'District 4', 'title' => 'Westwood Park');
		$plugin_aem_option_xml_neighborhoods[4150] = array('enable' => 'Yes', 'id' => 4150, 'district' => 'District 4', 'title' => 'Sunnyside');
		$plugin_aem_option_xml_neighborhoods[4160] = array('enable' => 'Yes', 'id' => 4160, 'district' => 'District 4', 'title' => 'West Portal');
		
		$plugin_aem_option_xml_neighborhoods[5010] = array('enable' => 'Yes', 'id' => 5010, 'district' => 'District 5', 'title' => 'Glen Park');
		$plugin_aem_option_xml_neighborhoods[5020] = array('enable' => 'Yes', 'id' => 5020, 'district' => 'District 5', 'title' => 'Haight Ashbury');
		$plugin_aem_option_xml_neighborhoods[5030] = array('enable' => 'Yes', 'id' => 5030, 'district' => 'District 5', 'title' => 'Noe Valley');
		$plugin_aem_option_xml_neighborhoods[5040] = array('enable' => 'Yes', 'id' => 5040, 'district' => 'District 5', 'title' => 'Twin Peaks');
		$plugin_aem_option_xml_neighborhoods[5050] = array('enable' => 'Yes', 'id' => 5050, 'district' => 'District 5', 'title' => 'Cole Valley / Parnassus Hts');
		$plugin_aem_option_xml_neighborhoods[5060] = array('enable' => 'Yes', 'id' => 5060, 'district' => 'District 5', 'title' => 'Buena Vista / Ashbury Hts');
		$plugin_aem_option_xml_neighborhoods[5070] = array('enable' => 'Yes', 'id' => 5070, 'district' => 'District 5', 'title' => 'Corona Heights');
		$plugin_aem_option_xml_neighborhoods[5080] = array('enable' => 'Yes', 'id' => 5080, 'district' => 'District 5', 'title' => 'Clarendon Heights');
		$plugin_aem_option_xml_neighborhoods[5090] = array('enable' => 'Yes', 'id' => 5090, 'district' => 'District 5', 'title' => 'Duboce Triangle');
		$plugin_aem_option_xml_neighborhoods[5100] = array('enable' => 'Yes', 'id' => 5100, 'district' => 'District 5', 'title' => 'Eureka Valley / Dolores');
		$plugin_aem_option_xml_neighborhoods[5110] = array('enable' => 'Yes', 'id' => 5110, 'district' => 'District 5', 'title' => 'Mission Dolores');
		
		$plugin_aem_option_xml_neighborhoods[6010] = array('enable' => 'Yes', 'id' => 6010, 'district' => 'District 6', 'title' => 'Anza Vista');
		$plugin_aem_option_xml_neighborhoods[6020] = array('enable' => 'Yes', 'id' => 6020, 'district' => 'District 6', 'title' => 'Hayes Valley');
		$plugin_aem_option_xml_neighborhoods[6030] = array('enable' => 'Yes', 'id' => 6030, 'district' => 'District 6', 'title' => 'Lwr Pacific Hts');
		$plugin_aem_option_xml_neighborhoods[6040] = array('enable' => 'Yes', 'id' => 6040, 'district' => 'District 6', 'title' => 'Western Addition');
		$plugin_aem_option_xml_neighborhoods[6050] = array('enable' => 'Yes', 'id' => 6050, 'district' => 'District 6', 'title' => 'Alamo Square');
		$plugin_aem_option_xml_neighborhoods[6060] = array('enable' => 'Yes', 'id' => 6060, 'district' => 'District 6', 'title' => 'North Panhandle');
		
		$plugin_aem_option_xml_neighborhoods[7010] = array('enable' => 'Yes', 'id' => 7010, 'district' => 'District 7', 'title' => 'Marina');
		$plugin_aem_option_xml_neighborhoods[7020] = array('enable' => 'Yes', 'id' => 7020, 'district' => 'District 7', 'title' => 'Pacific Heights');
		$plugin_aem_option_xml_neighborhoods[7030] = array('enable' => 'Yes', 'id' => 7030, 'district' => 'District 7', 'title' => 'Presidio Heights');
		$plugin_aem_option_xml_neighborhoods[7040] = array('enable' => 'Yes', 'id' => 7040, 'district' => 'District 7', 'title' => 'Cow Hollow');
		
		$plugin_aem_option_xml_neighborhoods[8010] = array('enable' => 'Yes', 'id' => 8010, 'district' => 'District 8', 'title' => 'Downtown');
		$plugin_aem_option_xml_neighborhoods[8020] = array('enable' => 'Yes', 'id' => 8020, 'district' => 'District 8', 'title' => 'Financial District / Barbary Coast');
		$plugin_aem_option_xml_neighborhoods[8030] = array('enable' => 'Yes', 'id' => 8030, 'district' => 'District 8', 'title' => 'Nob Hill');
		$plugin_aem_option_xml_neighborhoods[8040] = array('enable' => 'Yes', 'id' => 8040, 'district' => 'District 8', 'title' => 'North Beach');
		$plugin_aem_option_xml_neighborhoods[8050] = array('enable' => 'Yes', 'id' => 8050, 'district' => 'District 8', 'title' => 'Russian Hill');
		$plugin_aem_option_xml_neighborhoods[8060] = array('enable' => 'Yes', 'id' => 8060, 'district' => 'District 8', 'title' => 'Van Ness/Civ Ctr');
		$plugin_aem_option_xml_neighborhoods[8070] = array('enable' => 'Yes', 'id' => 8070, 'district' => 'District 8', 'title' => 'Telegraph Hill');
		$plugin_aem_option_xml_neighborhoods[8080] = array('enable' => 'Yes', 'id' => 8080, 'district' => 'District 8', 'title' => 'North Waterfront');
		$plugin_aem_option_xml_neighborhoods[8090] = array('enable' => 'Yes', 'id' => 8090, 'district' => 'District 8', 'title' => 'Tenderloin');
		
		$plugin_aem_option_xml_neighborhoods[9010] = array('enable' => 'Yes', 'id' => 9010, 'district' => 'District 9', 'title' => 'Bernal Heights');
		$plugin_aem_option_xml_neighborhoods[9020] = array('enable' => 'Yes', 'id' => 9020, 'district' => 'District 9', 'title' => 'Inner Mission');
		$plugin_aem_option_xml_neighborhoods[9030] = array('enable' => 'Yes', 'id' => 9030, 'district' => 'District 9', 'title' => 'Mission Bay');
		$plugin_aem_option_xml_neighborhoods[9040] = array('enable' => 'Yes', 'id' => 9040, 'district' => 'District 9', 'title' => 'Potrero Hill');
		$plugin_aem_option_xml_neighborhoods[9050] = array('enable' => 'Yes', 'id' => 9050, 'district' => 'District 9', 'title' => 'South of Market');
		$plugin_aem_option_xml_neighborhoods[9060] = array('enable' => 'Yes', 'id' => 9060, 'district' => 'District 9', 'title' => 'South Beach');
		$plugin_aem_option_xml_neighborhoods[9070] = array('enable' => 'Yes', 'id' => 9070, 'district' => 'District 9', 'title' => 'Ctrl Waterfront / Dogpatch');
		$plugin_aem_option_xml_neighborhoods[9080] = array('enable' => 'Yes', 'id' => 9080, 'district' => 'District 9', 'title' => 'Yerba Buena');
		
		$plugin_aem_option_xml_neighborhoods[10010] = array('enable' => 'Yes', 'id' => 10010, 'district' => 'District 10', 'title' => 'Bayview');
		$plugin_aem_option_xml_neighborhoods[10020] = array('enable' => 'Yes', 'id' => 10020, 'district' => 'District 10', 'title' => 'Crocker Amazon');
		$plugin_aem_option_xml_neighborhoods[10030] = array('enable' => 'Yes', 'id' => 10030, 'district' => 'District 10', 'title' => 'Excelsior');
		$plugin_aem_option_xml_neighborhoods[10040] = array('enable' => 'Yes', 'id' => 10040, 'district' => 'District 10', 'title' => 'Outer Mission');
		$plugin_aem_option_xml_neighborhoods[10050] = array('enable' => 'Yes', 'id' => 10050, 'district' => 'District 10', 'title' => 'Visitacion Valley');
		$plugin_aem_option_xml_neighborhoods[10060] = array('enable' => 'Yes', 'id' => 10060, 'district' => 'District 10', 'title' => 'Portola');
		$plugin_aem_option_xml_neighborhoods[10070] = array('enable' => 'Yes', 'id' => 10070, 'district' => 'District 10', 'title' => 'Silver Terrace');
		$plugin_aem_option_xml_neighborhoods[10080] = array('enable' => 'Yes', 'id' => 10080, 'district' => 'District 10', 'title' => 'Mission Terrace');
		$plugin_aem_option_xml_neighborhoods[10090] = array('enable' => 'Yes', 'id' => 10090, 'district' => 'District 10', 'title' => 'Hunters Point');
		$plugin_aem_option_xml_neighborhoods[10100] = array('enable' => 'Yes', 'id' => 10100, 'district' => 'District 10', 'title' => 'Bayview Heights');
		$plugin_aem_option_xml_neighborhoods[10110] = array('enable' => 'Yes', 'id' => 10110, 'district' => 'District 10', 'title' => 'Candlestick Point');
		$plugin_aem_option_xml_neighborhoods[10120] = array('enable' => 'Yes', 'id' => 10120, 'district' => 'District 10', 'title' => 'Little Hollywood');
		
		$plugin_aem_option_xml_neighborhoods[11010] = array('enable' => 'Yes', 'id' => 11010, 'district' => 'District 11', 'title' => 'Original Daly City');
		$plugin_aem_option_xml_neighborhoods[11020] = array('enable' => 'Yes', 'id' => 11020, 'district' => 'District 11', 'title' => 'Serramonte');
		$plugin_aem_option_xml_neighborhoods[11030] = array('enable' => 'Yes', 'id' => 11030, 'district' => 'District 11', 'title' => 'Southern Hills');
		$plugin_aem_option_xml_neighborhoods[11040] = array('enable' => 'Yes', 'id' => 11040, 'district' => 'District 11', 'title' => 'Westlake #1 / Olympic');
		$plugin_aem_option_xml_neighborhoods[11050] = array('enable' => 'Yes', 'id' => 11050, 'district' => 'District 11', 'title' => 'Westlake Highlands');
		$plugin_aem_option_xml_neighborhoods[11060] = array('enable' => 'Yes', 'id' => 11060, 'district' => 'District 11', 'title' => 'Westlake Knolls');
		$plugin_aem_option_xml_neighborhoods[11070] = array('enable' => 'Yes', 'id' => 11070, 'district' => 'District 11', 'title' => 'Broadmoor');
		$plugin_aem_option_xml_neighborhoods[11080] = array('enable' => 'Yes', 'id' => 11080, 'district' => 'District 11', 'title' => 'Westlake Terrace');
		$plugin_aem_option_xml_neighborhoods[11090] = array('enable' => 'Yes', 'id' => 11090, 'district' => 'District 11', 'title' => 'St. Francis Hts');
		$plugin_aem_option_xml_neighborhoods[11100] = array('enable' => 'Yes', 'id' => 11000, 'district' => 'District 11', 'title' => 'Westlake Palisades');
		$plugin_aem_option_xml_neighborhoods[11110] = array('enable' => 'Yes', 'id' => 11110, 'district' => 'District 11', 'title' => 'Blossom Valley');
		$plugin_aem_option_xml_neighborhoods[11120] = array('enable' => 'Yes', 'id' => 11120, 'district' => 'District 11', 'title' => 'Crown Colony');
		$plugin_aem_option_xml_neighborhoods[11130] = array('enable' => 'Yes', 'id' => 11130, 'district' => 'District 11', 'title' => 'Colma');
		$plugin_aem_option_xml_neighborhoods[11140] = array('enable' => 'Yes', 'id' => 11140, 'district' => 'District 11', 'title' => 'Brisbane');
		$plugin_aem_option_xml_neighborhoods[11150] = array('enable' => 'Yes', 'id' => 11150, 'district' => 'District 11', 'title' => 'Bayridge / Linda Vista');
		
		if(count($plugin_aem_option_xml_neighborhoods) > 0) {
			$plugin_aem_option_xml_neighborhoods_serialize = serialize($plugin_aem_option_xml_neighborhoods);
			$plugin_aem_option_xml_neighborhoods_serialize = htmlentities($plugin_aem_option_xml_neighborhoods_serialize,ENT_QUOTES);
		} else {
			$plugin_aem_option_xml_neighborhoods_serialize = "";
		}	
		
		define('AEM_PLUGIN_OPTION_XML_NEIGHBORHOODS', $plugin_aem_option_xml_neighborhoods_serialize);

	}
	
	
	/** Plugin Miscellaneous Settings */
	
	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_PARENT')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_PARENT', 'Properties');
	}

	if (!defined('AEM_THEME_ACTIVE')) {
		$aem_plugin_template_page_slug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_PARENT);
		if(plugin_aem_get_ID_by_slug($aem_plugin_template_page_slug) > 0) {
			define('AEM_THEME_ACTIVE', TRUE);
		} else {
			define('AEM_THEME_ACTIVE', FALSE);
		}	
	}

	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_PARENT')) {
		if(AEM_THEME_ACTIVE == true) {
			define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_PARENT', AEM_PLUGIN_TEMPLATE_PAGE_PARENT);
		} else {	
			define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_PARENT', '');
		}
	}
	
	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT', sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_PARENT));
	}
	
	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_SEARCH')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_SEARCH', 'Search Property'); 
	}

	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS', 'Search Results');
	}
	
	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS', 'Property Details');
	}
	

	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_ACTIVE_PROPERTIES')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_ACTIVE_PROPERTIES', 'My Active Properties');
	}
	
	if (!defined('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_SOLD_PROPERTIES')) {
		define('AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_SOLD_PROPERTIES', 'My Sold Properties');
	}

	if (!defined('AEM_PLUGIN_SHORTCODE_SEARCH')) {
		define('AEM_PLUGIN_SHORTCODE_SEARCH', '[plugin_aem_template_search]');
	}
	
	if (!defined('AEM_PLUGIN_SHORTCODE_RESULTS')) {
		define('AEM_PLUGIN_SHORTCODE_RESULTS', '[plugin_aem_template_results]');
	}
	
	if (!defined('AEM_PLUGIN_SHORTCODE_DETAILS')) {
		define('AEM_PLUGIN_SHORTCODE_DETAILS', '[plugin_aem_template_details]');
	}
	
	if (!defined('AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES')) {
		define('AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES', '[plugin_aem_template_my_active_properties]');
	}
	
	if (!defined('AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES')) {
		define('AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES', '[plugin_aem_template_my_sold_properties]');
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin Install/Remove 
* ----------------------------------------------------------------------------------------------------------------------
*/

	// Runs when plugin is activated 
	register_activation_hook(__FILE__,'plugin_aem_plugin_install'); 
	
	// Runs on plugin deactivation
	register_deactivation_hook( __FILE__, 'plugin_aem_plugin_remove' );



/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin Database: Properties >> Create Database Table if NOT exists
* ----------------------------------------------------------------------------------------------------------------------
*/

	if($wpdb->get_var("SHOW TABLES LIKE '".AEM_PLUGIN_DB_Table."'") != AEM_PLUGIN_DB_Table) {
		
		$sql = "CREATE TABLE IF NOT EXISTS `".AEM_PLUGIN_DB_Table."` (
				  `id` int(100) NOT NULL AUTO_INCREMENT,
				  `Represented` varchar(255) DEFAULT 'Both',
				  `MLS` int(100) DEFAULT 0,
				  `Title` varchar(255) DEFAULT NULL,
				  `URL` varchar(255) DEFAULT NULL,
				  `DefaultImageURL` varchar(255) DEFAULT NULL,
				  `DefaultThumbnailURL` varchar(255) DEFAULT NULL,
				  `PropertyType` varchar(255) DEFAULT NULL,
				  `Address` text,
				  `Bedrooms` varchar(255) DEFAULT 0,
				  `Bathrooms` varchar(255) DEFAULT 0,
				  `ListingPrice` varchar(255) DEFAULT 0,
				  `ListingDate` varchar(255) DEFAULT NULL,
				  `SellingPrice` varchar(255) DEFAULT 0,
				  `SoldDate` varchar(255) DEFAULT NULL,
				  `Description` text,
				  `Status` varchar(255) DEFAULT NULL,
				  `ListingAgent` varchar(255) DEFAULT NULL,
				  `ListingOffice` varchar(255) DEFAULT NULL,
				  `Comment` text,
				  `full_property_details` longtext,
				  `date_added` date DEFAULT NULL,
				  `date_updated` date DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			
		$wpdb->query($sql);
		
		if($wpdb->get_var("SHOW TABLES LIKE '".AEM_PLUGIN_DB_Table."'") == AEM_PLUGIN_DB_Table) {	
			$plugin_aem_db_creation[AEM_PLUGIN_DB_Table] = '<h3>DB Table: '.AEM_PLUGIN_DB_Table.' Created Successfully</h3>';
		} else {
			$plugin_aem_db_creation[AEM_PLUGIN_DB_Table] = '<h3>Failed Creating DB Table: '.AEM_PLUGIN_DB_Table.'</h3>';
		}
		
		#echo $plugin_aem_db_creation[AEM_PLUGIN_DB_Table];
	
	}
	
	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin Install/Active
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_plugin_install() {
	
		global $wpdb;
		
		// cretae the pages
		$result_create_page = plugin_aem_create_page(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_SEARCH, AEM_PLUGIN_SHORTCODE_SEARCH);
		$result_create_page = plugin_aem_create_page(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS, AEM_PLUGIN_SHORTCODE_RESULTS);
		$result_create_page = plugin_aem_create_page(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS, AEM_PLUGIN_SHORTCODE_DETAILS);
		
		$result_create_page = plugin_aem_create_page(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_ACTIVE_PROPERTIES, "<h2>".AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_ACTIVE_PROPERTIES."</h2> ".AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES);
		$result_create_page = plugin_aem_create_page(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_SOLD_PROPERTIES, "<h2>".AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_SOLD_PROPERTIES."</h2> ".AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES);

		// update the permalink of the current blog site
		update_option('permalink_structure', "/%postname%/");
			
		// Export Properties into Property Listings
		export_properties_into_propertylistings();

	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin Uninstall/Deactivate 
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function plugin_aem_plugin_remove() {
	
		global $wpdb;
	    $force_delete = true;
		$parent_slug = '';
		
		// check if the pages has parent page & get the parent slug/permalink
		if(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT != "") { 
			$parent_slug .= AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT.'/'; 
		} 
		

		// delete database tables created by the plugin
		if($wpdb->get_var("SHOW TABLES LIKE '".AEM_PLUGIN_DB_Table."'") == AEM_PLUGIN_DB_Table) {
	
			#$wpdb->query("DROP TABLE IF EXISTS ".AEM_PLUGIN_DB_Table.";");
			
			if($wpdb->get_var("SHOW TABLES LIKE '".AEM_PLUGIN_DB_Table."'") != AEM_PLUGIN_DB_Table) {	
				#$plugin_aem_db_removed[AEM_PLUGIN_DB_Table] = '<h3>DB Table: '.AEM_PLUGIN_DB_Table.' Removed Successfully</h3>';
			} else {
				#$plugin_aem_db_removed[AEM_PLUGIN_DB_Table] = '<h3>Failed Removing DB Table: '.AEM_PLUGIN_DB_Table.'</h3>';
			}
			
			#echo $plugin_aem_db_removed[AEM_PLUGIN_DB_Table];
		
		}


		/* ------------------------------------------------------------------------------
		
		// delete the page from the wp_post database table
		$delete_thePageIDx = wp_delete_post( plugin_aem_get_ID_by_slug($parent_slug.get_option("plugin_aem_option_template_page_search")), $force_delete );
		$delete_thePageIDx = wp_delete_post( plugin_aem_get_ID_by_slug($parent_slug.get_option("plugin_aem_option_template_page_results")), $force_delete );
		$delete_thePageIDx = wp_delete_post( plugin_aem_get_ID_by_slug($parent_slug.get_option("plugin_aem_option_template_page_details")), $force_delete );

		// delete the page from the wp_post database table
		$slug_my_active_properties = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_ACTIVE_PROPERTIES);
		$slug_my_sold_properties = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_MY_SOLD_PROPERTIES);
		$delete_thePageIDx = wp_delete_post( plugin_aem_get_ID_by_slug($parent_slug.$slug_my_active_properties), $force_delete );
		$delete_thePageIDx = wp_delete_post( plugin_aem_get_ID_by_slug($parent_slug.$slug_my_sold_properties), $force_delete );
		
		--------------------------------------------------------------------------------- */

		// delete the option from the wp_options database table
		
		# delete_option("plugin_aem_option_api_key");
		# delete_option("plugin_aem_option_limit");
		# delete_option("plugin_aem_option_xml_agent");
		# delete_option("plugin_aem_option_xml_agent_enable");
		  delete_option("plugin_aem_option_xml_neighborhoods_serialize"); // code fix: reset the neighborhood arrays 
		
		# delete_option("plugin_aem_option_template_page_base_search");
		# delete_option("plugin_aem_option_template_page_base_results");
		# delete_option("plugin_aem_option_template_page_base_details");
		
		# delete_option("plugin_aem_option_template_page_search");
		# delete_option("plugin_aem_option_template_page_results");
		# delete_option("plugin_aem_option_template_page_details");
		
	}

	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Export Properties into Property Listings
* ----------------------------------------------------------------------------------------------------------------------
*/

	function export_properties_into_propertylistings() {
	
		global $wpdb;
		global $current_user;
		
		$listing = array();
		
		// Property Details
		$listing['Represented']			= 'Seller';
		$listing['Title'] 				= '';
		$listing['Address'] 			= '';
		$listing['MLS'] 				= '';
		$listing['Bedrooms'] 			= '';
		$listing['Bathrooms'] 			= '';
		$listing['PropertyType'] 		= '';
		$listing['Neighborhood'] 		= '';
		$listing['Description'] 		= '';
		$listing['ListingPrice'] 		= '';
		$listing['SellingPrice'] 		= '';
		$listing['SoldDate'] 			= '';
		$listing['ListingAgent'] 		= '';
		$listing['ListingOffice']		= '';
		$listing['Status'] 				= '';
		
		// PrimaryDetails
		$listing['CrossStreet'] 		= '';
		$listing['ApproximateSqFt'] 	= '';
		$listing['PricePerSqFt'] 		= '';
		$listing['YearBuilt'] 			= '';
		$listing['TotalRooms'] 			= '';
		$listing['HOADues'] 			= '';
		
		// AdditionalDetails
		$listing['Parking'] 			= '';
		$listing['Type']				= '';
		$listing['Style'] 				= '';
		$listing['Floors'] 				= '';
		$listing['BathTypeIncludes']	= '';
		$listing['Kitchen'] 			= '';
		$listing['DiningRoom'] 			= '';
		$listing['LivingRoom'] 			= '';
		$listing['HeatingCoolingSystem']= '';
		$listing['LaundryAppliances'] 	= '';
		$listing['SpecialFeatures'] 	= '';
		$listing['CommonAreas'] 		= '';
		$listing['Transportation'] 		= '';
		$listing['Shopping'] 			= '';
		//$listing['Comment'] 			= '';
		
		// photos/images
		$listing['FeaturedImage'] 		= '';
		$listing['Photos'] 				= '';
		
		$mlsid_listings = array();
	
		$tmp_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table."", ARRAY_A);
		if(count($tmp_listings)) {
			foreach($tmp_listings as $tmp_listing) {
				
				// store the details into array
				$arr["Listing"] = unserialize(base64_decode($tmp_listing['full_property_details']));
				$propertylisting = array();
				
				if(is_array($listing)) {
					foreach($listing as $field_key => $field_value) {
						if(isset($arr["Listing"]['PrimaryDetails'][$field_key])) {
							$propertylisting[$field_key] = $arr["Listing"]['PrimaryDetails'][$field_key]['value'];
						} elseif(isset($arr["Listing"]['AdditionalDetails'][$field_key])) {
							$propertylisting[$field_key] = $arr["Listing"]['AdditionalDetails'][$field_key]['value'];
						} else {
							$propertylisting[$field_key] = $arr["Listing"][$field_key]['value'];
						}
					}
				}
											
				if(is_array($arr["Listing"]['Photos']['Photo'])) {
					if(count($arr["Listing"]['Photos']['Photo']) > 0) {
						foreach($arr["Listing"]['Photos']['Photo'] as $Photos_photo) {
							if(!empty($Photos_photo['URL']['value']) && $Photos_photo['URL']['value'] != AEM_PLUGIN_URL."/images/no_image_available.jpg") {
								$propertylisting['Photos'][] = $Photos_photo['URL']['value'];
							}
						}
					}
				}
				
				if(!isset($propertylisting['Represented']) || empty($propertylisting['Represented'])) {
					$propertylisting['Represented'] = 'Seller';
				}

				// Create post object
				$my_post = array(
					 'post_title' 	=> $propertylisting['Title'],
					 'post_content' => $propertylisting['Description'],
					 'post_status' 	=> 'publish',
					 'post_type' 	=> 'property_listings',
					 'post_author' 	=> $current_user->ID
				);
			
				// Insert the post into the database
				$inserted_post_id = wp_insert_post( $my_post );

				if($inserted_post_id > 0) {
					
					$mlsid_listings['added'][] = $propertylisting;
					
					foreach($listing as $listing_key => $listing_value) {
						if(!in_array($listing_key, array('Title'))) {
							update_post_meta($inserted_post_id, $listing_key, $propertylisting[$listing_key]);
						}
					}
					
					// delete the old record
					$deleted_property = $wpdb->query("DELETE FROM ".AEM_PLUGIN_DB_Table." WHERE id = '".$tmp_listing['id']."' LIMIT 1");
				
				}


			}
		}
				
	}

	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Get Current User
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_wp_get_current_user() {
	
		$current_user = wp_get_current_user();
		
		if ( !($current_user instanceof WP_User) ) {
     		return $current_user;
		} else { 
			return null;
		}
		
	}
	
	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Get the WordPress Page ID by Slug/Permalink
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function plugin_aem_get_ID_by_slug($page_slug) {
		
		$page = get_page_by_path($page_slug);
		
		if ($page) {
			return $page->ID;
		} else {
			return null;
		}
		
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin Admin Menu
* ----------------------------------------------------------------------------------------------------------------------
*/

	// create custom plugin settings menu
	add_action('admin_menu', 'plugin_aem_create_menu');
	
	function plugin_aem_create_menu() {
		
		/*--------------------------------------------------------
		   1. Page title – Title of the page or screen that the menu links to. If you are rendering your own page, then the page title value is stored in the global $title.
		   2. Menu title – Title of the menu.
		   3. Capability – Defines what permissions a user must have in order to access this menu. Here are a list of capabilities. (10 = Administrator)
		   4. Menu ID – Unique id of the menu.
		   5. Menu display function – Function containing HTML and PHP code on how to display the page or screen that the menu is linked to.
		   6. Menu icon – (optional) URL of the icon to use for this menu. Here is a tutorial on how to flexibly customize your menu icons.
		   7. Menu position – (optional) If left unspecified, new menu will appear at the bottom. Otherwise, standard WordPress menus are specified in positions of 5s. For example Dashboard = 0, Post = 5, Media = 10, and so on. Since we want our menu to appear after post, we set its position to 26. If we set it to 25 it will replace the Comments menu.
		----------------------------------------------------------*/
		add_menu_page(AEM_PLUGIN_TITLE, AEM_PLUGIN_TITLE, 10, 'agenteasy-properties', 'plugin_aem_settings_page', plugins_url('/images/house.gif', __FILE__), 99);
		add_submenu_page('', 'Properties', 'Properties', 10, 'agenteasy-properties/properties.php');
		add_submenu_page('', 'Add Property', 'Add Property', 10, 'agenteasy-properties/add-property.php');
		add_submenu_page('', 'Edit Property', 'Edit Property', 10, 'agenteasy-properties/edit-property.php');

	}
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Enable WYSIWYG & WP Media Library editor in plugin, 
* ----------------------------------------------------------------------------------------------------------------------
*/

	if($_GET['page'] == "agenteasy-properties/add-property.php" || $_GET['page'] == "agenteasy-properties/edit-property.php") {
		add_action( 'init', 'ae_plugin_init' );
	}

	function ae_plugin_init(){
		if (current_user_can('upload_files')) {
			add_action('admin_print_scripts', 'ae_load_jquery');
			add_action('admin_print_styles', 'ae_load_styles' );
			//add_action('admin_menu', 'ae_file_uploader_add_metabox');
		}
	}
	
	function ae_load_jquery(){
		$ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__));
		wp_enqueue_script('jquery');
		wp_enqueue_script('ae-fileupload', $ae_fileupload_dir . 'jquery.fileupload.js');
		wp_enqueue_script('ae-fileupload-ui', $ae_fileupload_dir . 'jquery.fileupload-ui.js');
	}
	
	function ae_load_styles(){
		$ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__));
		wp_enqueue_style('ae-fileupload-style', $ae_fileupload_dir . 'jquery.fileupload-ui.css');
	}

//	if($_GET['post'] > 0 && $_GET['action'] == "edit" || $_GET['post_type'] == "referrals") {
//		add_action( 'init', 'agent_plugin_init' );
//	}
//	
//	function agent_plugin_init() {
//		
//		wp_enqueue_script('editor');
//		wp_enqueue_script('thickbox');
//		wp_enqueue_script('media-upload');
//		add_action( 'admin_head', 'wp_tiny_mce' );
//
//	}
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Create WordPress Page for the Template
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_create_page($plugin_aem_page_title, $plugin_aem_page_content) {
	
		global $wpdb;
		
		// get the page slug/permalink
		$plugin_aem_page_slug = sanitize_title($plugin_aem_page_title);
		
		
		if(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT == "") {
		
			// get the page id if exists
			$plugin_aem_page_id = plugin_aem_get_ID_by_slug($plugin_aem_page_slug);
			
		} else {
			
			// get the page id if exists
			$plugin_aem_page_id = plugin_aem_get_ID_by_slug(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT.'/'.$plugin_aem_page_slug);
			
		}
		
		// check the page exists & get the parent page id
		if($plugin_aem_page_id > 0) {
			
			$post_info = get_post($plugin_aem_page_id); 
			$plugin_aem_page_parent_id = $post_info->post_parent;
			$plugin_aem_page_menu_order = $post_info->menu_order;
			
		} else {
		
			if(AEM_THEME_ACTIVE == TRUE) {
				$plugin_aem_page_parent_id = plugin_aem_get_ID_by_slug(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT);
			} else {
				$plugin_aem_page_parent_id = 0;
			}	
			
			$plugin_aem_page_menu_order = 0;
			
		}
			
		// get current blog site user info
		$author_info = plugin_aem_wp_get_current_user();
		
		// Insert the PAGE into the WP database
		$plugin_aem_page = array();
		
		$plugin_aem_page['ID'] 				= $plugin_aem_page_id;
		$plugin_aem_page['post_title'] 		= $plugin_aem_page_title;
		$plugin_aem_page['post_type'] 		= 'page';
		$plugin_aem_page['post_author'] 	= $author_info->ID;
		$plugin_aem_page['post_content'] 	= $plugin_aem_page_content;
		$plugin_aem_page['post_status'] 	= 'publish';
		$plugin_aem_page['comment_status']	= 'closed';
		$plugin_aem_page['ping_status'] 	= 'closed';
		$plugin_aem_page['post_parent'] 	= $plugin_aem_page_parent_id;
		$plugin_aem_page['menu_order'] 		= $plugin_aem_page_menu_order;
		
		// Insert the post into the database
		$thePageIDx = 0;
		$thePageIDx = wp_insert_post( $plugin_aem_page );
	   
		if($thePageIDx > 0) {
			$output = true;
		} else {
			$output = false;
		} 
		
		return $output; 
	
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Register Plugin Settings & Set Option default values
* ----------------------------------------------------------------------------------------------------------------------
*/

	//call register settings function
	add_action( 'admin_init', 'register_plugin_aem_settings' );

	//register our settings
	function register_plugin_aem_settings() {
	
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_api_key' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_limit' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_xml_agent' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_xml_agent_enable' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_xml_neighborhoods_serialize' );
		
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_base_search' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_base_results' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_base_details' );
		
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_search' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_results' );
		register_setting( 'plugin-ps-settings-group', 'plugin_aem_option_template_page_details' );

	}

	if(get_option('plugin_aem_option_template_page_search') == '') {
		$pageslug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_SEARCH);
		add_option('plugin_aem_option_template_page_search', $pageslug);
		update_option('plugin_aem_option_template_page_search', $pageslug);
	}
	
	if(get_option('plugin_aem_option_template_page_results') == '') {
		$pageslug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS);
		add_option('plugin_aem_option_template_page_results', $pageslug);
		update_option('plugin_aem_option_template_page_results', $pageslug);
	}
	
	if(get_option('plugin_aem_option_template_page_details') == '') {
		$pageslug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS);
		add_option('plugin_aem_option_template_page_details', $pageslug);
		update_option('plugin_aem_option_template_page_details', $pageslug);
	}
	
	if(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT != "") {
		$page_parent_slug = '/'.AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT;
	} else {
		$page_parent_slug = '';
	}
	
	if(get_option('plugin_aem_option_template_page_base_search') == '') {
		add_option('plugin_aem_option_template_page_base_search', get_option( 'siteurl' ).$page_parent_slug);
		update_option('plugin_aem_option_template_page_base_search', get_option( 'siteurl' ).$page_parent_slug);
	}
	
	if(get_option('plugin_aem_option_template_page_base_results') == '') {
		add_option('plugin_aem_option_template_page_base_results', get_option( 'siteurl' ).$page_parent_slug);
		update_option('plugin_aem_option_template_page_base_results', get_option( 'siteurl' ).$page_parent_slug);
	}
	
	if(get_option('plugin_aem_option_template_page_base_details') == '') {
		add_option('plugin_aem_option_template_page_base_details', get_option( 'siteurl' ).$page_parent_slug);
		update_option('plugin_aem_option_template_page_base_details', get_option( 'siteurl' ).$page_parent_slug);
	}
	
	if(get_option('plugin_aem_option_api_key') == '') {
		add_option('plugin_aem_option_api_key', AEM_PLUGIN_OPTION_API_KEY);
	}

	if(get_option('plugin_aem_option_limit') == '') {
		add_option('plugin_aem_option_limit', AEM_PLUGIN_OPTION_XML_LIMIT);
	}

	if(get_option('plugin_aem_option_xml_agent') == '') {
		add_option('plugin_aem_option_xml_agent', AEM_PLUGIN_OPTION_XML_AGENT);
	}

	if(get_option('plugin_aem_option_xml_agent_enable') == '') {
		add_option('plugin_aem_option_xml_agent_enable', AEM_PLUGIN_OPTION_XML_AGENT_ENABLE);
	}

	if(get_option('plugin_aem_option_xml_neighborhoods_serialize') == '') {
		add_option('plugin_aem_option_xml_neighborhoods_serialize', AEM_PLUGIN_OPTION_XML_NEIGHBORHOODS);
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Set & Get Settings values
* ----------------------------------------------------------------------------------------------------------------------
*/

	function wp_plugin_aem_params(){
	
		$wp_ps = array();
		
		$wp_ps['plugin_aem_option_xml_parser']  		 	 	= AEM_PLUGIN_OPTION_XML_PARSER;
		$wp_ps['plugin_aem_option_api_key'] 	 		 		= get_option('plugin_aem_option_api_key');
		$wp_ps['plugin_aem_option_limit'] 	 		 			= get_option('plugin_aem_option_limit');
		$wp_ps['plugin_aem_option_xml_agent'] 	 		 		= get_option('plugin_aem_option_xml_agent');
		$wp_ps['plugin_aem_option_xml_agent_enable'] 	 		= get_option('plugin_aem_option_xml_agent_enable');
		$wp_ps['plugin_aem_option_xml_status'] 	 				= AEM_PLUGIN_OPTION_XML_STATUS;
		$wp_ps['plugin_aem_option_xml_neighborhoods_serialize']  = get_option('plugin_aem_option_xml_neighborhoods_serialize');

		$wp_ps['plugin_aem_option_template_page_base_search']  	= get_option('plugin_aem_option_template_page_base_search');
		$wp_ps['plugin_aem_option_template_page_base_results'] 	= get_option('plugin_aem_option_template_page_base_results');
		$wp_ps['plugin_aem_option_template_page_base_details'] 	= get_option('plugin_aem_option_template_page_base_details');
	
		$wp_ps['plugin_aem_option_template_page_search']  		= get_option('plugin_aem_option_template_page_search');
		$wp_ps['plugin_aem_option_template_page_results'] 		= get_option('plugin_aem_option_template_page_results');
		$wp_ps['plugin_aem_option_template_page_details'] 		= get_option('plugin_aem_option_template_page_details');
	
		$wp_ps['AEM_PLUGIN_URL'] 								= AEM_PLUGIN_URL;
	
		return $wp_ps;
		
	}
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Template Page
* ----------------------------------------------------------------------------------------------------------------------
*/

	function wp_plugin_aem_template($template_page, $wp_plugin_aem_params) {
	
		global $wpdb;
		
		if($template_page == "template-my-sold-properties.php") {
		
			$sold_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE Status = 'Sold' ORDER BY MLS ASC", ARRAY_A);
			
			if(count($sold_mlsid_listings) > 0) {
				$db_listings['sold_listings'] = $sold_mlsid_listings;
			} else {
				$db_listings['sold_listings'] = array();
			}
			
		}
	
		if($template_page == "template-my-active-properties.php") {
		
			$active_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." WHERE Status LIKE 'Active' OR Status LIKE 'Act. Con.' OR Status LIKE 'Active Contigent' ORDER BY MLS ASC", ARRAY_A);
			
			if(count($active_mlsid_listings) > 0) {
				$db_listings['active_listings'] = $active_mlsid_listings;
			} else {
				$db_listings['active_listings'] = array();
			}
			
		}
	
		if($template_page == "template-details.php") {
		
			
			// set default value as null
			$mlsid = 0;	
			$address = "";	
			
			// get the url params on the property details page
			$aem_property_details = get_query_var('aem_property_details');
			$property_vars = explode('/',$aem_property_details);
			
			// check if 
			if(is_array($property_vars)) {
				
				// get the mlsid on property url (permalink)
				if(count($property_vars) > 0) {
					if($property_vars[0] > 0) {
						$mlsid = $property_vars[0];	
					}
				}
				
				// get the address on property url (permalink)
				if(count($property_vars) > 1) {
					if($property_vars[1] != "") {
						$address = urldecode($property_vars[1]);	
					}
				}
				
			}
			
			$WHERE  = " WHERE MLS = '".$mlsid."' ";
			$WHERE .= " AND address = '".$address."' ";
			
			$details_mlsid_listings = $wpdb->get_results("SELECT * FROM ".AEM_PLUGIN_DB_Table." ".$WHERE." LIMIT 1", ARRAY_A);
			
			if(count($details_mlsid_listings) > 0) {
				$db_listings['details_listings'] = $details_mlsid_listings;
			} else {
				$db_listings['details_listings'] = array();
			}
			
		}
	
		// turn output buffering On
		ob_start();
		
		// get the template content
		require_once($template_page);
		
		$output = ob_get_contents();
		
		// silently discard the buffer contents. 
		ob_end_clean();
		
		return $output;
		
	}

	function wp_plugin_aem_template_page($template_page) {
	
		$wp_plugin_aem_params = wp_plugin_aem_params();
		$output = wp_plugin_aem_template($template_page, $wp_plugin_aem_params);
		
		return $output;
		
	}
	

/**
* ----------------------------------------------------------------------------------------------------------------------
* Template Page: Search
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_property_templates($content) {
	
		$content = preg_replace('/<p>\s*[(.*)]\s*<\/p>/i', "[$1]", $content);
		
		if (strpos($content, AEM_PLUGIN_SHORTCODE_SEARCH) !== FALSE) {
			$content = str_replace(AEM_PLUGIN_SHORTCODE_SEARCH, wp_plugin_aem_template_page('template-search.php'), $content);
		}
		
		if (strpos($content, AEM_PLUGIN_SHORTCODE_RESULTS) !== FALSE){
			$content = str_replace(AEM_PLUGIN_SHORTCODE_RESULTS, wp_plugin_aem_template_page('template-results.php'), $content);
		}
		
		if (strpos($content, AEM_PLUGIN_SHORTCODE_DETAILS) !== FALSE) {
			$content = str_replace(AEM_PLUGIN_SHORTCODE_DETAILS, wp_plugin_aem_template_page('template-details.php'), $content);
		}
		
		if (strpos($content, AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES) !== FALSE) {
			$content = str_replace(AEM_PLUGIN_SHORTCODE_MY_ACTIVE_PROPERTIES, wp_plugin_aem_template_page('template-my-active-properties.php'), $content);
		}
		
		if (strpos($content, AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES) !== FALSE) {
			$content = str_replace(AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES, wp_plugin_aem_template_page('template-my-sold-properties.php'), $content);
		}
		
		return $content;
	
	}
	
	add_filter('the_content', 'plugin_aem_property_templates');


/**
* ----------------------------------------------------------------------------------------------------------------------
* Custom Rewrite Rule for the "Property" Detail Page (Clean/SEO friendly URL)
* ----------------------------------------------------------------------------------------------------------------------
* Example: http://myblogsite.com/detail-page/376072/2450-bush-st-san-francisco-ca-94115/
*  
*  -> Template page    : detail-page
*  -> MLS Listing#     : 376072
*  -> Property Address : 2450-bush-st-san-francisco-ca-94115
* ----------------------------------------------------------------------------------------------------------------------
*/

	function property_search_flush_rewrite() {
	  	global $wp_rewrite;
	  	$wp_rewrite->flush_rules();
	}
	
	function property_search_vars($public_query_vars) {
		$public_query_vars[] = 'aem_property_details';
		return $public_query_vars;
	}
	
	function property_search_add_rewrite_rules($wp_rewrite) {
		
		$template_page = get_option('plugin_aem_option_template_page_base_details').'/'.get_option('plugin_aem_option_template_page_details');
		
		$template_base = get_option( 'siteurl' ).'/';

		$template_page_details = str_replace($template_base, "", $template_page);
		
		$new_rules = array($template_page_details.'/(.+)' => 'index.php?pagename='.$template_page_details.'&aem_property_details=' . $wp_rewrite->preg_index(1));
		
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	}

	add_action('init', 'property_search_flush_rewrite');
	add_filter('query_vars', 'property_search_vars');
	add_action('generate_rewrite_rules', 'property_search_add_rewrite_rules');
		

/**
* ----------------------------------------------------------------------------------------------------------------------
*  Pagination string
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function plugin_aem_getPaginationString($frontText = "", $page = 1, $totalitems, $limit = 10, $adjacents = 1, $targetpage = "/", $pagestring = "?pg=") {		
		
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
		
			$pagination .= "<div class=\"property_results-pagination\">";
			
			#$pagination .= "<span id=\"pagination-title\">$frontText</span>";
	
			//previous button
			if ($page > 1) {
				$pagination .= "&nbsp;&nbsp;<a href=\"$targetpage$pagestring$prev\">&laquo; PREV</a>&nbsp;&nbsp;";
			} else {
				//$pagination .= "<span class=\"disabled\">&laquo; prev</span>";	
			}
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2)) {	//not enough pages to bother breaking it up
				
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page) {
						$pagination .= "<span class=\"current\">$counter</span>";
					} else {
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
					}
				}
				
			} elseif($lastpage >= 7 + ($adjacents * 2)) { //enough pages to hide some	
				
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 3))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page) {
							$pagination .= "<span class=\"current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
						}
					}
					$pagination .= "<span class=\"elipses\">....</span>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
				
				//in middle; hide some front and some back
				} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
				
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
					$pagination .= "<span class=\"elipses\">...</span>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page) {
							$pagination .= "<span class=\"current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
						}
					}
					$pagination .= "...";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
				
				//close to end; only hide early pages
				} else {
				
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
					$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
					$pagination .= "<span class=\"elipses\">....</span>";
					for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page) {
							$pagination .= "<span class=\"current\">$counter</span>";
						} else {
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
						}
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) {
				$pagination .= "&nbsp;&nbsp;<a href=\"" . $targetpage . $pagestring . $next . "\">NEXT &raquo;</a>";
			} else {
				//$pagination .= "<span class=\"disabled\">next &raquo;</span>";
			}
			
			$pagination .= "</div>";
		}
		
		return $pagination;
	
	}

	
/**
* ----------------------------------------------------------------------------------------------------------------------
*  XML processing
* ----------------------------------------------------------------------------------------------------------------------
*/

	function xml2array($contents, $get_attributes=1) { 
	   
		if(!$contents) return array(); 
	
		if(!function_exists('xml_parser_create')) { 
			//print "'xml_parser_create()' function not found!"; 
			return array(); 
		} 
		
		//Get the XML parser of PHP - PHP must have this module for the parser to work 
		$parser = xml_parser_create(); 
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
		xml_parse_into_struct( $parser, $contents, $xml_values ); 
		xml_parser_free( $parser ); 
	
		if(!$xml_values) return; //Hmm... 
	
		//Initializations 
		$xml_array = array(); 
		$parents = array(); 
		$opened_tags = array(); 
		$arr = array(); 
	
		$current = &$xml_array; 
	
		//Go through the tags. 
		foreach($xml_values as $data) { 
			
			unset($attributes,$value); //Remove existing values, or there will be trouble 
	
			//This command will extract these variables into the foreach scope 
			// tag, type, level(int), attributes(array). 
			extract($data);//We could use the array by itself, but this cooler. 
	
			$result = ''; 
			
			if($get_attributes) { //The second argument of the function decides this. 
				
				$result = array(); 
				if(isset($value)) $result['value'] = $value; 
	
				//Set the attributes too. 
				if(isset($attributes)) { 
					foreach($attributes as $attr => $val) { 
						if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
						/**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */ 
					} 
				} 
				
			} elseif(isset($value)) { 
				$result = $value; 
			} 
	
			//See tag status and do the needed. 
			if($type == "open") { //The starting of the tag '<tag>' 
				
				$parent[$level-1] = &$current; 
	
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
					
					$current[$tag] = $result; 
					$current = &$current[$tag]; 
	
				} else { //There was another element with the same tag name 
					
					if(isset($current[$tag][0])) { 
						array_push($current[$tag], $result); 
					} else { 
						$current[$tag] = array($current[$tag],$result); 
					}
					
					$last = count($current[$tag]) - 1; 
					$current = &$current[$tag][$last]; 
					
				} 
	
			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
				
				//See if the key is already taken. 
				if(!isset($current[$tag])) { //New Key 
					
					$current[$tag] = $result; 
	
				} else { //If taken, put all things inside a list(array) 
					
					if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array... 
						or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) { 
						
						// ...push the new element into that array. 
						array_push($current[$tag],$result); 
					
					} else { //If it is not an array... 
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
					} 
					
				} 
	
			} elseif($type == 'close') { //End of tag '</tag>' 
				$current = &$parent[$level-1]; 
			} 
		} 
	
		return($xml_array); 
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
*  Exclude Pages from the Navigation Menu
* ----------------------------------------------------------------------------------------------------------------------
*/

	function exclude_plugin_aem_pages_from_navmenu($exclude_array) {
		
		$results_page_slug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS);
		$details_page_slug = sanitize_title(AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS);
		
		// check if the pages has parent page & get the parent slug/permalink
		if(AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT != "") { 
			$parent_slug = AEM_PLUGIN_TEMPLATE_PAGE_SLUG_PARENT.'/'; 
		} else {
			$parent_slug = '';
		}
		
		$results_page_id = plugin_aem_get_ID_by_slug($parent_slug.$results_page_slug);
		$details_page_id = plugin_aem_get_ID_by_slug($parent_slug.$details_page_slug);
		
		return array_merge( $exclude_array, array( $results_page_id, $details_page_id  ) );		
				
	}	
	
	add_filter('wp_list_pages_excludes', 'exclude_plugin_aem_pages_from_navmenu');


/**
* ----------------------------------------------------------------------------------------------------------------------
*  Integrate the Media Library into a Plugin
* ----------------------------------------------------------------------------------------------------------------------
*/

	function wp_gear_manager_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery');
	}
	
	function wp_gear_manager_admin_styles() {
		wp_enqueue_style('thickbox');
	}
	
	add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
	add_action('admin_print_styles', 'wp_gear_manager_admin_styles');


/**
* ----------------------------------------------------------------------------------------------------------------------
*  WP Admin Plugin Setting Page
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_settings_page() {
	?>
    
		<div class="wrap">
		
			<div class="icon32" id="icon-themes"><br></div>
			<h2 class="nav-tab-wrapper" style="border-bottom:none;"><?php echo AEM_PLUGIN_TITLE; ?></h2>
			
			<p>&nbsp;</p>
			
			<?php 
			// update the XML Settings
			if (isset($_POST["update_plugin_aem_settings"])){
			
				$errcount = 0;
				
				update_option('plugin_aem_option_api_key', $_POST["plugin_aem_option_api_key"]);
				
				if($_POST["plugin_aem_option_xml_agent_enable"]) {
					
					if($_POST["plugin_aem_option_xml_agent"] != "") {
						
						update_option('plugin_aem_option_xml_agent_enable', $_POST["plugin_aem_option_xml_agent_enable"]);
						update_option('plugin_aem_option_xml_agent', $_POST["plugin_aem_option_xml_agent"]);
					
					} else {
						echo '<div id="message" class="updated fade"><p><strong>';
						echo 'Error Saving Agent MLSID setting. MLSID is empty.';
						echo '</strong></p></div>';
						$errcount++;
					} 
					
				} else {
					update_option('plugin_aem_option_xml_agent_enable', 'No');
				}
				
				if($_POST["plugin_aem_option_limit"] >= 10 && $_POST["plugin_aem_option_limit"] <= 100) {
					update_option('plugin_aem_option_limit', $_POST["plugin_aem_option_limit"]);
				} else {
					echo '<div id="message" class="updated fade"><p><strong>';
					echo 'Error Saving LIMIT setting. Value should have a minimum value of 10 and maximum value of 100.';
					echo '</strong></p></div>';
					$errcount++;
				}
					
				if($errcount == 0) {	
					echo '<div id="message" class="updated fade"><p><strong>';
					echo 'Settings Saved.';
					echo '</strong></p></div>';
				}
				
			}
			
			// update the Neighborhoods array
			if (isset($_POST["update_xml_settings_neighborhoods"])){
			
				if(count($_POST["plugin_aem_option_xml_neighborhoods"]) > 0) {
					$plugin_aem_option_xml_neighborhoods_serialize = serialize($_POST["plugin_aem_option_xml_neighborhoods"]);
					$plugin_aem_option_xml_neighborhoods_serialize = htmlentities($plugin_aem_option_xml_neighborhoods_serialize,ENT_QUOTES);
					delete_option('plugin_aem_option_xml_neighborhoods_serialize');
					add_option('plugin_aem_option_xml_neighborhoods_serialize', $plugin_aem_option_xml_neighborhoods_serialize);
				}
						
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Neighborhoods Updated.';
				echo '</strong></p></div>';

			}

			// update the Template Pages
			if (isset($_POST["update_template_pages"])){
			
				update_option('plugin_aem_option_template_page_base_search', $_POST["plugin_aem_option_template_page_base_search"]);
				update_option('plugin_aem_option_template_page_base_results', $_POST["plugin_aem_option_template_page_base_results"]);
				update_option('plugin_aem_option_template_page_base_details', $_POST["plugin_aem_option_template_page_base_details"]);
				
				update_option('plugin_aem_option_template_page_search', $_POST["plugin_aem_option_template_page_search"]);
				update_option('plugin_aem_option_template_page_results', $_POST["plugin_aem_option_template_page_results"]);
				update_option('plugin_aem_option_template_page_details', $_POST["plugin_aem_option_template_page_details"]);
						
				echo '<div id="message" class="updated fade"><p><strong>';
				echo 'Settings Saved.';
				echo '</strong></p></div>';
				
			}
			?>
			
			<form method="post" action="" name="form_update_plugin_aem_settings" id="form_update_plugin_aem_settings">
				<table cellspacing="0" class="widefat">
					<tr class="alternate">
						<th scope="row" colspan="2" style="background:#D7D7D7; font-size:14px;">Plugin Settings</th>
					</tr>
					<tr class="alternate">
						<th scope="row">Agent API Key</th>
						<td>
                        	<input type="text" name="plugin_aem_option_api_key" value="<?php echo get_option('plugin_aem_option_api_key'); ?>" style="border:1px solid #CCCCCC; width:800px;" />
                        	<span class="description" style="font-size:11px; font-style:normal; float:right;">You'll need an API key to use this plugin.</span>
                        </td>
					</tr>
					<tr class="alternate">
						<th scope="row">Agent MLSID</th>
						<td>
                            Enable: 
                            <input type="checkbox" name="plugin_aem_option_xml_agent_enable" id="plugin_aem_option_xml_agent_enable" value="Yes" onclick="javascript: checkAgentEnable();" <?php if(get_option('plugin_aem_option_xml_agent_enable') == "Yes") { echo 'checked="checked"'; } ?> style="border:1px solid #CCCCCC;" />
                        	&nbsp;&nbsp;
                            MLSID:
                            <input type="text" name="plugin_aem_option_xml_agent" id="plugin_aem_option_xml_agent" value="<?php echo get_option('plugin_aem_option_xml_agent'); ?>" style="border:1px solid #CCCCCC; width:200px;" />
                        	<span class="description" style="font-size:11px; font-style:normal; float:right;">XML agent: Agent MLSID</span>
                            <script language="JavaScript">
							function checkAgentEnable() {
								if(document.form_update_plugin_aem_settings.plugin_aem_option_xml_agent_enable.checked == true) {
									document.form_update_plugin_aem_settings.plugin_aem_option_xml_agent.disabled = false;
								} else {
									document.form_update_plugin_aem_settings.plugin_aem_option_xml_agent.disabled = true;
								}
							}
							checkAgentEnable();
                            </script> 
                        </td>
					</tr>
					<tr class="alternate">
						<th scope="row">Limit</th>
						<td>
                        	<input type="text" name="plugin_aem_option_limit" value="<?php echo get_option('plugin_aem_option_limit'); ?>" style="border:1px solid #CCCCCC; width:40px;" />
                        	<span class="description" style="font-size:11px; font-style:normal; float:right;">Number of results/listings to return per page, max is 100</span>
                        </td>
					</tr>
					<tr class="alternate">
						<td colspan="2">
                        	<input type="submit" class="button-primary" name="update_plugin_aem_settings" value="<?php _e('Save Changes') ?>" style="margin:10px auto;" />
                        </td>
					</tr>
				</table>
			</form>
			
			<br/><br/>
			
			<form method="post" action="" name="form_update_xml_settings_neighborhoods">
                <table cellspacing="0" class="widefat">
                    <tr class="alternate">
                        <th scope="row" style="background:#D7D7D7; font-size:14px;">Neighborhoods</th>
                    </tr>
					<tr class="alternate">
						<td style="text-align:left; padding:10px 5px;">
							<?php 
							$option_xml_neighborhoods = array();
							if(get_option('plugin_aem_option_xml_neighborhoods_serialize') != "") {
								$option_xml_neighborhoods = html_entity_decode(get_option('plugin_aem_option_xml_neighborhoods_serialize'),ENT_QUOTES);
								$option_xml_neighborhoods = unserialize($option_xml_neighborhoods);
							} 
							?>
							<?php if(count($option_xml_neighborhoods) > 0) {?>
                                <?php foreach($option_xml_neighborhoods as $neighborhood) { ?> 
                                    <div style="float:left; width:230px; text-align:left; margin:2px; padding:2px; border:1px solid #CCCCCC; text-align:left;">
                                        <input type="checkbox" name="plugin_aem_option_xml_neighborhoods[<?php echo $neighborhood['id']; ?>][enable]" value="Yes" title="Enable Neighborhood" <?php if($neighborhood['enable'] == "Yes") { echo 'checked="checked"'; } ?> style="border:1px solid #CCCCCC;"> 
                                        <input type="hidden" name="plugin_aem_option_xml_neighborhoods[<?php echo $neighborhood['id']; ?>][id]" value="<?php echo $neighborhood['id']; ?>" title="Neighborhood ID" style="border:1px solid #CCCCCC; width:50px;"> 
                                        <input type="hidden" name="plugin_aem_option_xml_neighborhoods[<?php echo $neighborhood['id']; ?>][title]" value="<?php echo $neighborhood['title']; ?>" title="Neighborhood Name" style="border:1px solid #CCCCCC; width:180px;"> 
                                        <input type="hidden" name="plugin_aem_option_xml_neighborhoods[<?php echo $neighborhood['id']; ?>][district]" value="<?php echo $neighborhood['district']; ?>" title="Neighborhood District" style="border:1px solid #CCCCCC; width:150px;"> 
                                        <?php echo $neighborhood['title']; ?>
                                    </div>	
                            	<?php } ?>
                            <?php } else { ?>
                                <span class="description">No Neighborhood.</span>
                            <?php } ?>	
                        </td>
					</tr>
                    <tr class="alternate">
                        <td style="padding:10px; text-align:left;">
                            <input type="submit" class="button-primary" name="update_xml_settings_neighborhoods" value="<?php _e('Save Changes') ?>" />
                        </td>
                    </tr>
            	</table>
            </form>
			
            <br/><br/>
            
			<form method="post" action="" name="form_update_template_pages">
				<table cellspacing="0" class="widefat">
					<tr class="alternate">
						<th scope="row" style="background:#D7D7D7; font-size:14px;" width="20%">Template Page</th>
						<th scope="row" style="background:#D7D7D7; font-size:14px;" >Permalink : &nbsp;<span class="description" style="font-style:normal; font-weight:normal;">Value should be the same with the Page Permalink where the Shortcode is placed</span></th>
						<th scope="row" style="background:#D7D7D7; font-size:14px;" width="20%">Shortcode</th>
					</tr>
					<tr class="alternate">
						<th>
							<a href="<?php echo get_option('plugin_aem_option_template_page_base_search'); ?>/<?php echo get_option('plugin_aem_option_template_page_search'); ?>/" style="font-size:13px;" target="_blank">
								<?php echo AEM_PLUGIN_TEMPLATE_PAGE_TITLE_SEARCH; ?>
							</a>
						</th>
						<td>
							<input type="text" name="plugin_aem_option_template_page_base_search" value="<?php echo get_option('plugin_aem_option_template_page_base_search'); ?>" style="border:1px solid #CCCCCC; width:400px;" />/
							<input type="text" name="plugin_aem_option_template_page_search" value="<?php echo get_option('plugin_aem_option_template_page_search'); ?>" style="border:1px solid #CCCCCC; width:200px;" />/
						</td>
						<td>
                             <?php echo AEM_PLUGIN_SHORTCODE_SEARCH; ?>
						</td>
					</tr>
					<tr class="alternate">
						<th>
							<a href="<?php echo get_option('plugin_aem_option_template_page_base_results'); ?>/<?php echo get_option('plugin_aem_option_template_page_results'); ?>/" style="font-size:13px;" target="_blank">
								<?php echo AEM_PLUGIN_TEMPLATE_PAGE_TITLE_RESULTS; ?>
							</a>
						</th>
						<td>
							<input type="text" name="plugin_aem_option_template_page_base_results" value="<?php echo get_option('plugin_aem_option_template_page_base_results'); ?>" style="border:1px solid #CCCCCC; width:400px;" />/
							<input type="text" name="plugin_aem_option_template_page_results" value="<?php echo get_option('plugin_aem_option_template_page_results'); ?>" style="border:1px solid #CCCCCC; width:200px;" />/
						</td>
						<td>
                             <?php echo AEM_PLUGIN_SHORTCODE_RESULTS; ?>
						</td>
					</tr>
					<tr class="alternate">
						<th>
							<a href="<?php echo get_option('plugin_aem_option_template_page_base_details'); ?>/<?php echo get_option('plugin_aem_option_template_page_details'); ?>/" style="font-size:13px;" target="_blank">
								<?php echo AEM_PLUGIN_TEMPLATE_PAGE_TITLE_DETAILS; ?>
							</a>
						</th>
						<td>
							<input type="text" name="plugin_aem_option_template_page_base_details" value="<?php echo get_option('plugin_aem_option_template_page_base_details'); ?>" style="border:1px solid #CCCCCC; width:400px;" />/
							<input type="text" name="plugin_aem_option_template_page_details" value="<?php echo get_option('plugin_aem_option_template_page_details'); ?>" style="border:1px solid #CCCCCC; width:200px;" />/
						</td>
						<td>
                            <?php echo AEM_PLUGIN_SHORTCODE_DETAILS; ?>
						</td>
					</tr>
					<tr class="alternate">
						<td colspan="3">
                            <input type="submit" class="button-primary" name="update_template_pages" value="<?php _e('Save Changes') ?>" style="margin:10px auto;" />
                        </td>
					</tr>
				</table>
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
                         When the Agent MLSID is supplied in the Plugin Settings and the Shortcode is on a page, it will display the auto feed of the Agents Active Listings.
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <?php echo AEM_PLUGIN_SHORTCODE_MY_SOLD_PROPERTIES; ?>
                    </td>
                    <td>
                         When the Agent MLSID is supplied in the Plugin Settings and the Shortcode is on a page, it will display the auto feed of the Agents Sold Listings.
                    </td>
                </tr>
            </table>

			<p>&nbsp;</p>
            
			<h2 class="title" style="font-size:20px; padding-top:0px;">
				Usage: 
				<span style="font-size:12px; font-style:normal;">Add the trigger text (Shortcode) to the page content to display the template. By default, the pages with shortcode are created already on plugin activation.</span>
			</h2>
            
			<p>&nbsp;</p>
				
		</div>
	
	<?php 
	} 
	
	
/**
* ----------------------------------------------------------------------------------------------------------------------
*  WP Admin Plugin Properties Page
* ----------------------------------------------------------------------------------------------------------------------
*/

	function plugin_aem_properties_page() {
	?>
    
		<div class="wrap">
		
			<div class="icon32" id="icon-themes"><br></div>
			<h2 class="nav-tab-wrapper" style="border-bottom:none;">Properties</h2>
			
			<p>&nbsp;</p>
			
			<p>&nbsp;</p>
				
		</div>
	
	<?php 
	} 


/**
* ----------------------------------------------------------------------------------------------------------------------
*  Agent Information Widget
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	add_action("widgets_init", array('Widget_aem_agent_information', 'register'));
	
	register_activation_hook( __FILE__, array('Widget_aem_agent_information', 'activate'));
	register_deactivation_hook( __FILE__, array('Widget_aem_agent_information', 'deactivate'));
	
	class Widget_aem_agent_information {
	
	 	 function activate(){
		
			$data = array('widgetTitle' => 'Contact Info',  
						  'agentMLSID' => '',
						  'agentImage' => '',
						  'agentName' => '',
						  'agentFax' => '',
						  'agentDRE' => '',
						  'agentPhone' => '',
						  'agentMobile' => '',
						  'agentEmail' => '',
						  'agentWebsite' => '',
						  'agentOffice' => '',
						  'agent1name' => '',
						  'agent1phone' => '',
						  'agent2name' => '',
						  'agent2phone' => '',
						  'agentAddress' => '');
		
			if ( ! get_option('widget_aem_agent_information')){
		  		add_option('widget_aem_agent_information' , $data);
				#echo "add widget options";
			} else {
		  		#update_option('widget_aem_agent_information' , $data);
				#echo "update widget options";
			}
			
	  	}
	  
	  	function deactivate(){
			#delete_option('widget_aem_agent_information');
			#echo "delete widget options";
	  	}
	  
	  	function control(){
	  
			$data = get_option('widget_aem_agent_information');
		  
			?>
			<p><label>Title: <br /><input class="widefat" name="widget_aem_agent_information_widgetTitle" type="text" value="<?php echo $data['widgetTitle']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<?php /*?>
            <p><label>Agent MLSID: <br /><input class="widefat" name="widget_aem_agent_information_agentMLSID" type="text" value="<?php echo $data['agentMLSID']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<?php */?>
            <p><label>Agent Name: <br /><input class="widefat" name="widget_aem_agent_information_agentName" type="text" value="<?php echo $data['agentName']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			
            <p>
				<script language="JavaScript">
                jQuery(document).ready(function() {
                
                    var uploadID = ''; /*setup the var in a global scope*/
                
                    jQuery('.upload-button').click(function() {
                        uploadID = jQuery(this).prev('input'); /*set the uploadID variable to the value of the input before the upload button*/
                        formfield = jQuery('.upload').attr('name');
                        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                        return false;
                    });
                
                    window.send_to_editor = function(html) {
                        imgurl = jQuery('img',html).attr('src');
                        uploadID.val(imgurl); /*assign the value of the image src to the input*/
                        tb_remove();
                    };
                });
                </script>
                <label>
                    Image URL: 
                    <br />
                    <input class="widefat" name="widget_aem_agent_information_agentImage" type="text" value="<?php echo $data['agentImage']; ?>" style="border: 1px solid #CCCCCC;"  class="upload" />
                  	<input class="upload-button" name="wsl-image-add" type="button" value="Upload Image" />  
                </label>
            </p>
            <p><label>DRE#: <br /><input class="widefat" name="widget_aem_agent_information_agentDRE" type="text" value="<?php echo $data['agentDRE']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            <p><label>Mobile#: <br /><input class="widefat" name="widget_aem_agent_information_agentMobile" type="text" value="<?php echo $data['agentMobile']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<p><label>Phone#: <br /><input class="widefat" name="widget_aem_agent_information_agentPhone" type="text" value="<?php echo $data['agentPhone']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            			<p><label>Fax#: <br /><input class="widefat" name="widget_aem_agent_information_agentFax" type="text" value="<?php echo $data['agentFax']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            	
			<p><label>Email: <br /><input class="widefat" name="widget_aem_agent_information_agentEmail" type="text" value="<?php echo $data['agentEmail']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<p><label>Website: <br /><input class="widefat" name="widget_aem_agent_information_agentWebsite" type="text" value="<?php echo $data['agentWebsite']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<p><label>Office/Company Name: <br /><input class="widefat" name="widget_aem_agent_information_agentOffice" type="text" value="<?php echo $data['agentOffice']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
			<p><label>Address: <br /><textarea class="widefat" rows="4" cols="20" name="widget_aem_agent_information_agentAddress" style="border: 1px solid #CCCCCC;"><?php echo $data['agentAddress']; ?></textarea></label></p>
            
           <p> Teams</p>
<p>            Agent #1 (left)</p>
          <p><label>Name: <br /><input class="widefat" name="widget_aem_agent_information_agent1name" type="text" value="<?php echo $data['agent1name']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            <p><label>Phone: <br /><input class="widefat" name="widget_aem_agent_information_agent1phone" type="text" value="<?php echo $data['agent1phone']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
<p>            Agent #1 (right)</p>
           <p><label>Name: <br /><input class="widefat" name="widget_aem_agent_information_agent2name" type="text" value="<?php echo $data['agent2name']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            <p><label>Phone: <br /><input class="widefat" name="widget_aem_agent_information_agent2phone" type="text" value="<?php echo $data['agent2phone']; ?>" style="border: 1px solid #CCCCCC;" /></label></p>
            
           
			<?php
		  
			if (isset($_POST['widget_aem_agent_information_widgetTitle'])){
			
				$data['widgetTitle'] = attribute_escape($_POST['widget_aem_agent_information_widgetTitle']);
				$data['agentMLSID'] = attribute_escape($_POST['widget_aem_agent_information_agentMLSID']);
				$data['agentImage'] = attribute_escape($_POST['widget_aem_agent_information_agentImage']);
				$data['agentName'] = attribute_escape($_POST['widget_aem_agent_information_agentName']);
				$data['agentPhone'] = attribute_escape($_POST['widget_aem_agent_information_agentPhone']);
				$data['agentMobile'] = attribute_escape($_POST['widget_aem_agent_information_agentMobile']);
				$data['agentFax'] = attribute_escape($_POST['widget_aem_agent_information_agentFax']);
				$data['agentDRE'] = attribute_escape($_POST['widget_aem_agent_information_agentDRE']);
				$data['agentEmail'] = attribute_escape($_POST['widget_aem_agent_information_agentEmail']);
				$data['agentWebsite'] = attribute_escape($_POST['widget_aem_agent_information_agentWebsite']);
				$data['agentOffice'] = attribute_escape($_POST['widget_aem_agent_information_agentOffice']);
				$data['agent1name'] = attribute_escape($_POST['widget_aem_agent_information_agent1name']);
				$data['agent1phone'] = attribute_escape($_POST['widget_aem_agent_information_agent1phone']);
				$data['agent2name'] = attribute_escape($_POST['widget_aem_agent_information_agent2name']);
				$data['agent2phone'] = attribute_escape($_POST['widget_aem_agent_information_agent2phone']);
				$data['agentAddress'] = attribute_escape($_POST['widget_aem_agent_information_agentAddress']);
				
				update_option('widget_aem_agent_information', $data);
		  
			}
			
			#print_r($_POST);
			#print_r($data);
		
	  	}
	  
	  	function widget($args){
	  
	  		$wp_plugin_aem_params = wp_plugin_aem_params();
			$data = get_option('widget_aem_agent_information');
				
			#if($data['agentMLSID'] != '') {
				
				ob_start();
				require_once(AEM_PLUGIN_PATH.'/template-widget-agent-information.php');
				$agent_information = ob_get_contents();
				ob_end_clean();
				
				echo $args['before_widget'];
				
				if($data['widgetTitle'] != '') {
					echo $args['before_title'] . $data['widgetTitle'] . $args['after_title'];
				}
							
				echo $agent_information;
				echo $args['after_widget'];
		
			#}
			
	  	}
	  
	  	function register(){
			register_sidebar_widget('Agent Information', array('Widget_aem_agent_information', 'widget'));
			register_widget_control('Agent Information', array('Widget_aem_agent_information', 'control'));
	  	}
	  
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Cleans a string for input into a MySQL Database.
* Gets rid of unwanted characters/SQL injection etc.
* ----------------------------------------------------------------------------------------------------------------------
*/
	
	function aem_clean($str){
	
		// Only remove slashes if it's already been slashed by PHP
		#if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		#}
		
		// Let MySQL remove nasty characters.
		$str = mysql_real_escape_string($str);
		
		return $str;
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* This function will set & get date 
* ----------------------------------------------------------------------------------------------------------------------
*/

	 function aem_formatDate($dateFormat="g:ia \o\\n\ F j, Y", $dateValue) {
		
		$dated = str_replace(array(" ",":"),"-",$dateValue);
		list($year,$month,$day,$hour,$minute,$seconds) = explode("-",$dated);
		
		// you can edit this line to display date/time in your preferred notation
		$niceday = @date($dateFormat,mktime($hour,$minute,$seconds,$month,$day,$year));
		
		return $niceday;
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* This function will get the wp post attachment ID from an Image Src
* ----------------------------------------------------------------------------------------------------------------------
*/

	function aem_get_attachment_id_from_src($image_src) {
		
		global $wpdb;
		
		$attachment_id = $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid = '$image_src' LIMIT 1");
	 
		if($attachment_id == null){
			$image_src = basename ( $image_src );
			$id = $wpdb->get_var("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attachment_metadata' AND meta_value LIKE '%$image_src%' LIMIT 1");
		}
		
		if($attachment_id == null){
			$attachment_id = 0;
		}
		
		return $attachment_id;
		
	} 

/**
* ----------------------------------------------------------------------------------------------------------------------
* Custom Post Type >> Property Listings
* ----------------------------------------------------------------------------------------------------------------------
*/

	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Enable WYSIWYG & WP Media Library editor in plugin 
	* ------------------------------------------------------------------------------------------------------------------
	*/

		if($_GET['post'] > 0 && $_GET['action'] == "edit" || $_GET['post_type'] == "property_listings") {
			add_action( 'init', 'ae_property_listings_plugin_init' );
		}
	
		function ae_property_listings_plugin_init(){
				
			if(current_user_can('upload_files')) {
				add_action('admin_print_scripts', 'ae_property_listings_load_jquery');
				add_action('admin_print_styles', 'ae_property_listings_load_styles' );
				add_action( 'admin_head', 'wp_tiny_mce' );
			}

		}
		
		function ae_property_listings_load_jquery(){
			
			wp_enqueue_script('editor');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
			
			$ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__));
			wp_enqueue_script('jquery');
			wp_enqueue_script('ae-fileupload', $ae_fileupload_dir . 'jquery.fileupload.js');
			wp_enqueue_script('ae-fileupload-ui', $ae_fileupload_dir . 'jquery.fileupload-ui.js');
		
		}
		
		function ae_property_listings_load_styles(){
			
			$ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__));
			wp_enqueue_style('ae-fileupload-style', $ae_fileupload_dir . 'jquery.fileupload-ui.css');
		
		}


	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Register/Setup Post Type
	* ------------------------------------------------------------------------------------------------------------------
	*/

		add_action('init', 'ae_register_property_listings');
	 
		function ae_register_property_listings() {
		 
			$labels = array(
				'name' 				 => _x('AE Listings', 'post type general name'),
				'singular_name' 	 => _x('Property Listing Item', 'post type singular name'),
				'add_new' 			 => _x('Add New', 'property_listing item'),
				'add_new_item' 		 => __('Add New Property Listing Item'),
				'edit_item' 		 => __('Edit Property Listing Item'),
				'new_item' 			 => __('New Property Listing Item'),
				'view_item' 		 => __('View Property Listing Item'),
				'search_items' 		 => __('Search Property Listing'),
				'not_found' 		 => __('Nothing found'),
				'not_found_in_trash' => __('Nothing found in Trash'),
				'parent_item_colon'  => ''
			);
				 
			$args = array(
					'labels' 			 => $labels,
					'public' 			 => true,
					'publicly_queryable' => true,
					'show_ui' 			 => true, 
					'show_in_menu' 		 => true,
					'query_var' 		 => true,
					'rewrite' 			 => array('slug' => 'property-listings', 'with_front' => true),
					'capability_type' 	 => 'post',
					'hierarchical' 	 	 => false,
					'menu_position' 	 => 100,
					'supports' 			 => array('title'), #array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats'),
					'has_archive' 		 => false,
					'can_export' 		 => true
				  ); 
			
			register_post_type( 'property_listings' , $args );
			flush_rewrite_rules();
			
		}	

	/**
	* ------------------------------------------------------------------------------------------------------------------
	* tells WP to overwrite existing images without changing the filenames
	* ------------------------------------------------------------------------------------------------------------------
	*/

		add_filter('wp_handle_upload_overrides','noneUniqueFilename');
		
		function noneUniqueFilename($overrides){
		
			$overrides['test_form'] = false;
			$overrides['unique_filename_callback'] = 'nonUniqueFilenameCallback';
			return $overrides;
			
		}
		
		function nonUniqueFilenameCallback($directory, $name, $extension){
			
			$filename = $name . strtolower($extension);
			removeOldAttach($filename);
			return $filename;
			
		}
		
		function removeOldAttach($filename){
			
			$arguments = array(
				'numberposts'   => -1,
				'meta_key'      => '_wp_attached_file',
				'meta_value'    => $filename,
				'post_type'     => 'attachment'
			);
			
			$Attachments_to_remove = get_posts($arguments);
		
			foreach($Attachments_to_remove as $a) {
				wp_delete_attachment($a->ID, true);
			}
			
		}
		

	

	/**
	* ----------------------------------------------------------------------------------------------------------------------
	* Function To Process Image Uploading into WP Media Library
	* ----------------------------------------------------------------------------------------------------------------------
	*/
	
		function add_xml_img_to_wp_media_ibrary($image, $post_id, $imgURLname) { 
		
			require_once(ABSPATH . '/wp-admin/includes/file.php');
			require_once(ABSPATH . '/wp-admin/includes/media.php');
			require_once(ABSPATH . '/wp-admin/includes/image.php');
		
			$results = array();
			$name = basename($image); // name
			$binary_data = @file_get_contents($image);
			$getimagesize = @getimagesize($image_url); // type
			$type = $getimagesize['mime'];
			$tmp_name = tempnam(dirname(__FILE__)."/tmp", $img_name); // tmp_name
			$handle = @fopen($tmp_name, "w");
			@fwrite($handle, $binary_data);
			@fclose($handle);
			$size = @filesize($tmp_name); // size
	
			if(isset($name) && !empty($name)) {
				// nothing to do...
			} else {
				$name = $imgURLname; // name
			}
			if(isset($name) && !empty($name)) {
				// nothing to do...
			} else {
				$name = time(); //$imgURLname; // name
			}
	
			 //array to mimic $_FILES
			$file_array = array(
				'name' 		=> $name,		
				'type' 		=> $type, 		
				'tmp_name' 	=> $tmp_name, 	
				'error' 	=> 0, 			
				'size' 		=> $size 
			);
	
			//the actual image processing, that is, move to upload directory, generate thumbnails and image sizes and writing into the database happens here
			#$mhs = media_handle_sideload($file_array, $post_id, $desc = null, $post_data = array());
			$mhs = media_handle_sideload($file_array, $post_id, $name);
		
			// store thr processing result/details into an array
			$results = array('file' => $file_array, 'id' => $mhs);
		
			@unlink($tmp_name);
		
			return $results;
			
		}


	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Save Fields
	* ------------------------------------------------------------------------------------------------------------------
	*/

		add_action('save_post', 'save_details_property_listings');
	
		function save_details_property_listings() {

			global $post;
		 
			// Stop WP from clearing custom fields on autosave, and also during ajax requests (e.g. quick edit) and bulk edits.
			if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])) { return; }
		
			// Clean, Validate and Save Custom Fields
			$listing = array();

			// Property Details
			$listing['Represented'] 		= 'Seller';
			$listing['Address'] 			= '';
			$listing['MLS'] 				= '';
			$listing['Bedrooms'] 			= '';
			$listing['Bathrooms'] 			= '';
			$listing['PropertyType'] 		= '';
			$listing['Neighborhood'] 		= '';
			$listing['Description'] 		= '';
			$listing['ListingPrice'] 		= '';
			$listing['SellingPrice'] 		= '';
			$listing['SoldDate'] 			= '';
			$listing['ListingAgent'] 		= '';
			$listing['ListingOffice']		= '';
			$listing['Status'] 				= '';
			
			// PrimaryDetails
			$listing['CrossStreet'] 		= '';
			$listing['ApproximateSqFt'] 	= '';
			$listing['PricePerSqFt'] 		= '';
			$listing['YearBuilt'] 			= '';
			$listing['TotalRooms'] 			= '';
			$listing['HOADues'] 			= '';
			
			// AdditionalDetails
			$listing['Parking'] 			= '';
			$listing['Type']				= '';
			$listing['Style'] 				= '';
			$listing['Floors'] 				= '';
			$listing['BathTypeIncludes']	= '';
			$listing['Kitchen'] 			= '';
			$listing['DiningRoom'] 			= '';
			$listing['LivingRoom'] 			= '';
			$listing['HeatingCoolingSystem']= '';
			$listing['LaundryAppliances'] 	= '';
			$listing['SpecialFeatures'] 	= '';
			$listing['CommonAreas'] 		= '';
			$listing['Transportation'] 		= '';
			$listing['Shopping'] 			= '';
			//$listing['Comment'] 			= '';

			// photos/images
			$listing['FeaturedImage'] 		= '';
			$listing['Photos'] 				= '';

			foreach($listing as $listing_key => $listing_value) {

				if($listing_key == 'Photos') {
						
					if(!isset($_POST['Photos'])) {
						$_POST['Photos'] = array();
					}
					if(count($_POST['Photos']) > 0) {
						foreach($_POST['Photos'] as $photos_key => $photos_val) {
							if(isset($_POST['removedPhoto'][$photos_key])) {
								// don't add photo
							} else {
							
                            	$ignore_image = false;
								$image = $_POST['Photos'][$photos_key];
								
								/* // -------------------------------------------------------------------------------------------------------
								// ------------------------------------------------------------------------------------------------------- */
								if(isset($_POST['MID']) && !empty($_POST['MID'])) {
									if($image != AEM_PLUGIN_URL."/images/no_image_available.jpg") {
										#if(@fopen($image,'r') !== false) { 
											
											// upload into wp media library
											$imgURLname = basename($image); // name
											$add_to_wpml_result = add_xml_img_to_wp_media_ibrary($image, $post->ID, $imgURLname);
											
											#echo '<hr/>'; echo '<pre>'; print_r($add_to_wpml_result); echo '</pre>';
											
											// check if added into wp media library 
											if(is_int($add_to_wpml_result['id'])) {
												if($add_to_wpml_result['id'] > 0) { // Image added into WP Media Library.
												
													// get image
													@list( $img_src, $width, $height ) = image_downsize($add_to_wpml_result['id'], 'full');
												   
													// update the value of the image url
													$image = $img_src; 
													
													#if(@fopen($img_src,'r') !== false) { 
														#echo '<p>Image retrived from WP Media Library. ('.$image.')</p>';
													#} else {
														#echo '<p>Failed getting Image URL from WP Media Library.</p>';
													#}
													
												} else {
													$ignore_image = true; // Failed adding image into WP Media Library
												} 
											} else {
												$ignore_image = true; // Failed adding image into WP Media Library
											} 
										#} else {
											#$ignore_image = true; // Image doesn't exists
										#} 
									} else {
										$ignore_image = true; // Image is ignore
									} 
								}
								
								if($ignore_image == false) {
									$listing['Photos'][] = $image;
									update_post_meta($post->ID, 'Photos', $listing['Photos']);
								}
								
							}	
						}
					}
				
				} else {
					update_post_meta($post->ID, $listing_key, $_POST[$listing_key]);
				}
			
			}
			
			if(!isset($_POST['Represented']) || empty($_POST['Represented'])) {
				update_post_meta($post->ID, 'Represented', 'Seller');
			}
			
			update_post_meta($post->ID, "Description", $_POST["content"]);
	
		}
		
		
	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Main Post Type List Columns
	* ------------------------------------------------------------------------------------------------------------------
	*/
	
		// Add to admin_init function
		add_filter('manage_edit-property_listings_columns', 'add_new_property_listings_columns');
		
		function add_new_property_listings_columns($property_listings_columns) {
			
			$new_columns['cb'] 			 = '<input type="checkbox" />';
			$new_columns['title'] 		 = _x('Title', 'column name');
			$new_columns['Address'] 	 = __('Address');
			$new_columns['Status'] 		 = __('Status');
			$new_columns['Represented']	 = __('Represented');
			$new_columns['ListingPrice'] = __('ListingPrice');
			$new_columns['SellingPrice'] = __('SellingPrice');
			$new_columns['FeaturedImage']= __('Featured Image');
			$new_columns['ID'] 	 		 = __('ID');
	 
			return $new_columns;
			
		}		
		

	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Function to echo the selected field 
	* ------------------------------------------------------------------------------------------------------------------
	*/
		add_action("manage_posts_custom_column",  "property_listings_custom_columns");

		function property_listings_custom_columns($field){
			
			global $post;
			$wp_plugin_aem_params = wp_plugin_aem_params();
			
			switch ($field){
			
				case "ID": 
					
					$value = get_the_ID(); 
					echo $value;
					break;
					
				case "title": 
					
					$value = get_the_title(); 
					echo $value;
					break;
					
				case "description": 
					
					$value = get_the_content(); 
					echo $value;
					break;
					
				case "excerpt": 
					
					$value = get_the_excerpt(); 
					echo $value;
					break;
					
				case "FeaturedImage": 
					
					$value = '<img src="'.$wp_plugin_aem_params['AEM_PLUGIN_URL'].'/images/no_image_available.jpg" alt="" title="" border="0" width="70" height="70" style="border:1px solid #CCCCCC;" />';
					$fields = get_post_custom($post->ID);
					if(isset($fields[$field][0]) && !empty($fields[$field][0])) {
						$FeaturedImage = $fields['FeaturedImage'][0];
						if(!empty($FeaturedImage)) {
							$value = '<img src="'.$FeaturedImage.'" alt="" title="" border="0" width="70" height="70" style="border:1px solid #CCCCCC;" />';
						}
					}
					echo $value;
					break;
					
				default:
					
					$value = '';
					if(isset($field) && !empty($field)) {
						$fields = get_post_custom($post->ID);
						if(isset($fields[$field][0]) && !empty($fields[$field][0])) {
							$value = $fields[$field][0];
						}
					}
					echo $value;
					break;
					
			}
			
		}


	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Set Fields
	* ------------------------------------------------------------------------------------------------------------------
	*/

		add_action("admin_init", "ae_admin_init_property_listings");
		 
		function ae_admin_init_property_listings(){
			
			add_meta_box("property_listing_details-meta", "Property Details", "property_listing_fields", "property_listings", "normal", "high");

			$wp_plugin_aem_params = wp_plugin_aem_params();
			if(!empty($wp_plugin_aem_params['plugin_aem_option_api_key'])) {
				add_meta_box("property_listing_parser-meta", "Property Details XML Parser", "property_listing_parser", "property_listings", "side", "low");
			}
            
		}
		 
		function property_listing_parser() {
		
			if($_GET['post_type'] == 'property_listings' || $_GET['action'] == 'edit') { ?>
				<?php 
                if(!empty($_GET['post']) && $_GET['action'] == 'edit') {
                    $mid_xml_location = 'post.php?post='.$_GET['post'].'&action=edit'; 
                } else {
                    $mid_xml_location = 'post-new.php?post_type=property_listings'; 
                }
                ?>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
                    <tr class="alternate">
                        <td width="100" style="font-size:11px; text-align:center; vertical-align:middle; padding:5px 0px;">
                           Property MLSID:
                        </td>
                        <th style="text-align:center; vertical-align:middle; padding:5px 0px;">
                            <input type="text" name="MID" id="MID" value="<?php echo $_GET['MID']; ?>" style="border:1px solid #CCCCCC; padding:5px 0; width:70px; text-align:center;" />
                        </th>
                        <td style="font-size:11px; text-align:center; vertical-align:middle; padding:5px 1px;">
                            <input type="button" value="submit" name="submit_get_mlsid_details" class="button" style="padding:5px;" onclick="javascript: if(confirm('Get MLSID Property details from XML?')) { if(document.post.MID.value > 0) { location.href='<?php echo $mid_xml_location; ?>&MID=' + document.post.MID.value; } else { alert('Error: Property MLSID is Empty!'); } }" />
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td colspan="3" style="font-size:11px; text-align:left; vertical-align:middle; padding:10px; color:#666666;">
                           <div style="margin:3px;">Get MLSID Property details from XML and</div>
                           <div style="margin:3px;">fills out the 'Property Details' form fields.</div>
                        </td>
					</tr>
                </table>   
            <?php }
		
		}
		 
		function property_listing_fields() {
		 
		/**
		* ----------------------------------------------------------------------------------------------------------------------
		*  Variables
		* ----------------------------------------------------------------------------------------------------------------------
		*/
			
			global $post;
			$listing = array();
			$error = 0;
			$wp_plugin_aem_params = wp_plugin_aem_params();

			// Property Details
			$listing['Represented'] 		= 'Seller';
			$listing['Address'] 			= '';
			$listing['MLS'] 				= '';
			$listing['Bedrooms'] 			= '';
			$listing['Bathrooms'] 			= '';
			$listing['PropertyType'] 		= '';
			$listing['Neighborhood'] 		= '';
			$listing['Description'] 		= '';
			$listing['ListingPrice'] 		= '';
			$listing['SellingPrice'] 		= '';
			$listing['SoldDate'] 			= '';
			$listing['ListingAgent'] 		= '';
			$listing['ListingOffice']		= '';
			$listing['Status'] 				= 'Active';
			
			// PrimaryDetails
			$listing['CrossStreet'] 		= '';
			$listing['ApproximateSqFt'] 	= '';
			$listing['PricePerSqFt'] 		= '';
			$listing['YearBuilt'] 			= '';
			$listing['TotalRooms'] 			= '';
			$listing['HOADues'] 			= '';
			
			// AdditionalDetails
			$listing['Parking'] 			= '';
			$listing['Type']				= '';
			$listing['Style'] 				= '';
			$listing['Floors'] 				= '';
			$listing['BathTypeIncludes']	= '';
			$listing['Kitchen'] 			= '';
			$listing['DiningRoom'] 			= '';
			$listing['LivingRoom'] 			= '';
			$listing['HeatingCoolingSystem']= '';
			$listing['LaundryAppliances'] 	= '';
			$listing['SpecialFeatures'] 	= '';
			$listing['CommonAreas'] 		= '';
			$listing['Transportation'] 		= '';
			$listing['Shopping'] 			= '';
			//$listing['Comment'] 			= '';
			
			// photos/images
			$listing['FeaturedImage'] 		= '';
			$listing['Photos'] 				= '';

			$custom_fields = get_post_custom($post->ID);
			
			$custom_fields_exempt = array('_edit_lock', '_edit_last');

			if(is_array($custom_fields)) {
				foreach($listing as $field_key => $field_value) {
					if(!in_array($field_key,$custom_fields_exempt)) { 	
						$listing[$field_key] = $custom_fields[$field_key][0];
					}
				}
			}
			
			if(isset($listing['Photos']) && !empty($listing['Photos'])) {
				$listing['Photos'] = unserialize($listing['Photos']);
			}
			if(!is_array($listing['Photos'])) {
				$listing['Photos'] = array();
			}
	
			
		/**
		* ----------------------------------------------------------------------------------------------------------------------
		* Process >> Get Property
		* ----------------------------------------------------------------------------------------------------------------------
		*/
	
            // check if 
            if(isset($_GET['MID']) && !empty($_GET['MID'])) {
                
                // set & parse the xml 
                $xml_query_url = "property?mlsid=".$_GET['MID'];
                $xml_url = $wp_plugin_aem_params['plugin_aem_option_xml_parser'].urlencode($xml_query_url.'&apikey='.$wp_plugin_aem_params['plugin_aem_option_api_key']);
                
                // open the xml url
                if ($f = @fopen($xml_url, 'r')) {
                
                    $xml = '';
                    while (!feof($f)) { $xml .= fgets($f, 4096); }
                    fclose($f);
                    $arr = xml2array($xml);  //print_r($arr);
                
                    if (sizeof($arr) == 1 && $arr["Listing"]){
                        
                        if(array_key_exists("MLS", $arr["Listing"])) {
                           
							echo '<div id="message" class="updated fade"><p>';
							echo 'Property details successfully retrieved. Click the \'Publish\' or \'Save Draft\' button to save the property details.';
							echo '</p></div>';
                       
					   		if(is_array($listing)) {
								$listing_title = $arr["Listing"]['Title']['value'];
								foreach($listing as $field_key => $field_value) {
									if(!in_array($field_key,$custom_fields_exempt)) { 	
										if(isset($arr["Listing"]['PrimaryDetails'][$field_key])) {
											$listing[$field_key] = $arr["Listing"]['PrimaryDetails'][$field_key]['value'];
										} elseif(isset($arr["Listing"]['AdditionalDetails'][$field_key])) {
											$listing[$field_key] = $arr["Listing"]['AdditionalDetails'][$field_key]['value'];
										} else {
											$listing[$field_key] = $arr["Listing"][$field_key]['value'];
										}
									}
								}
							}
														
							if(is_array($arr["Listing"]['Photos']['Photo'])) {
								if(count($arr["Listing"]['Photos']['Photo']) > 0) {
									foreach($arr["Listing"]['Photos']['Photo'] as $Photos_photo) {
										if(!empty($Photos_photo['URL']['value'])) {
											$listing['Photos'][] = $Photos_photo['URL']['value'];
										}
									}
								}
							}
							
							if(!isset($listing['Represented']) || empty($listing['Represented'])) {
								$listing['Represented'] = 'Seller';
								update_post_meta($post->ID, 'Represented', 'Seller');
							}
							
							/*echo '<div id="message" class="updated fade"><pre>';
							print_r($arr["Listing"]);
							echo '</pre></div>';*/
							
					   
					    } else {
							echo '<div id="message" class="updated fade">Failed retrieving property details. MLSID doesn\'t exists</p></div>';
					    }
                        
                    } else {
                    
                        echo '<div id="message" class="updated fade"><p><strong>';
                        echo 'Failed retrieving property details. MLSID doesn\'t exists';
                        echo '</strong></p></div>';
                    }
                }
                
            }
			
			/*if(!isset($listing['Status']) || empty($listing['Status'])) {
				$listing['Status'] = 'Active';
			}*/
			?>
            
			<div style="padding:0px; font-size:12px; margin:0px;">
            
				<?php if(isset($listing_title) && !empty($listing_title)) { ?>
				<script type="text/javascript">
				document.getElementById('title').value = '<?php echo str_replace("'", " ", $listing_title); ?>';
                </script>
				<?php } ?>
            	
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
					
					<?php foreach($listing as $property_key => $property_val) { ?>
                            
                        <?php if($property_key == "Represented") { ?> 
                            
                            <tr class="alternate">
                                <td style="vertical-align:middle; padding:8px;" align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                <td style="vertical-align:middle; padding:8px;">
                                    <input type="radio" name="<?php echo $property_key; ?>" value="Seller" <?php $repval = "Seller"; if($property_val == $repval){ echo 'checked="checked"'; } ?> /> <?php echo $repval; ?>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="<?php echo $property_key; ?>" value="Buyer" <?php $repval = "Buyer"; if($property_val == $repval){ echo 'checked="checked"'; } ?> /> <?php echo $repval; ?>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="<?php echo $property_key; ?>" value="Both" <?php $repval = "Both"; if($property_val == $repval){ echo 'checked="checked"'; } ?>/> <?php echo $repval; ?>
                                </td>
                            </tr>
                            
                        <?php } elseif($property_key == "Description") { ?> 
                            
                            <tr class="alternate">
                                <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                <td>
                                    <div id="poststuff">
                                        <div id="postdivrich">
                                         <?php the_editor($property_val, $id = 'content', $prev_id = $property_key, $media_buttons = false, $tab_index = 9); ?>
                                        </div>
                                    </div>
                                    <?php /*?>
                                    <textarea name="<?php echo $property_key; ?>" cols="31" rows="10" style="border:1px solid #CCCCCC; width:70%;"><?php echo $property_val; ?></textarea>
                                    <?php */?>
                                 </td>
                            </tr>
                               
                        <?php } elseif($property_key == "Comment") { ?> 
                            
                            <?php /*?><tr class="alternate">
                                <td align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                 <td><textarea name="<?php echo $property_key; ?>" cols="31" rows="4" style="border:1px solid #CCCCCC; width:70%;"><?php echo $property_val; ?></textarea></td>
                            </tr><?php */?>
                        
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
                           
                            if($property_val != "" && !in_array($property_val, $property_types)) {
                                $property_types[] = $property_val;
                            }
                           ?>
                           
                            <tr class="alternate">
                                <td style="vertical-align:middle;" align="right" width="15%"><strong><?php echo $property_key; ?>:</strong></td>
                                <td style="vertical-align:middle;">
                                    <?php if(count($property_types) > 0) { ?>
                                        <select name="<?php echo $property_key; ?>">
                                            <?php foreach($property_types as $ptype) { ?>
                                                <?php 
                                                if($property_val == $ptype) { 
                                                    $selected = 'selected="selected"'; 
                                                } elseif($property_val == "" && $ptype == "Sold") { 
                                                    $selected = 'selected="selected"'; 
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                <option value="<?php echo $ptype; ?>" <?php echo $selected; ?>><?php echo $ptype; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>     
                                        <input type="text" name="<?php echo $property_key; ?>" value="<?php echo $property_val; ?>" style="width:70%; border:1px solid #CCCCCC;" />
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
                           

                           if($property_val != "" && !in_array($property_val, $property_status)) {
                                $property_status[] = $property_val;
                           }
                           ?>
                           
                            <tr class="alternate">
                                <td align="right" width="15%" style="vertical-align:middle;"><strong><?php echo $property_key; ?>:</strong></td>
                                <td style="vertical-align:middle;">
                                    <?php if(count($property_status) > 0) { ?>
                                        <select name="<?php echo $property_key; ?>">
                                            <?php foreach($property_status as $pstatus) { ?>
                                                <?php 
                                                if($property_val == $pstatus) { 
                                                    $selected = 'selected="selected"'; 
                                                } elseif($property_val == "" && $pstatus == "Sold") { 
                                                    $selected = 'selected="selected"'; 
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                <option value="<?php echo $pstatus; ?>" <?php echo $selected; ?>><?php echo $pstatus; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>     
                                        <input type="text" name="<?php echo $property_key; ?>" value="<?php echo $property_val; ?>" style="width:70%; border:1px solid #CCCCCC;" />
                                    <?php } ?>	
                                </td>
                            </tr>
                           
                        <?php } elseif($property_key == "Photos" || $property_key == "FeaturedImage") { ?> 
                            
                            <?php // -- nothing to display -- ?>
                        
                        <?php } else { ?>  
                        
                            <tr class="alternate">
                                <td align="right" width="15%" style="vertical-align:middle;"><strong><?php echo $property_key; ?>:</strong></td>
                                <td style="vertical-align:middle;">
                                    <input type="text" name="<?php echo $property_key; ?>" value="<?php echo $property_val; ?>" style="width:<?php if(in_array($property_key, array('MLS','Bedrooms','Bathrooms','ListingPrice','SellingPrice','SoldDate'))) { echo '20'; } else { echo '98'; }?>%; border:1px solid #CCCCCC;" />
                                    <?php if($property_key == "SoldDate") { ?>
                                        <span style="color:#999999;">YYYY-mm-dd</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            
                        <?php } // end of if elseif else condition ?>
                        
                    <?php } // end of foreach loop ?>
				</table>
                
                <br/>
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="widefat">
                    <tr class="alternate">
                        <th width="150" style="font-size:12px; text-align: right; vertical-align:middle;">
                            Property Photos:
						</th>
                        <th style="font-size:12px; border-right:1px solid #CCCCCC; text-align:right; vertical-align:middle;">
							<?php $ae_fileupload_dir = plugins_url('/agenteasy-properties/', dirname(__FILE__)); // set the plugin & upload dirs ?>
                            <script type="text/javascript">
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
                                        //alert('imgsrc: ' + imgsrc);
                                        // get the uploaded image data from wp media library
                                        jQuery.get("<?php echo $ae_fileupload_dir; ?>wp-medialink-property.php?image=" + imgsrc + "", {"id" : "<?php echo $_GET['post']; ?>"}, function(data){
                                            //alert('data: ' + data);
                                            if(data != '') { // check if data is returned
                                                // increment the photo counter hidden field
                                                var current_photo_counter = parseInt(jQuery('#photo_counter').val()) + 1; 									
                                                jQuery('#photo_counter').val(current_photo_counter); 
                                                // append the added image into the Property Photos
												jQuery('#property_photos').append('<div style="float:left; margin:4px; padding:2px 2px 0px; border:1px solid #999999; width:48%; text-align:center; vertical-align:middle; font-size:10px; color:#333333;">' + 
																						'<table border="0" cellpadding="0" cellspacing="0" width="100%">' +
																							'<tr>' +
																								'<td style="text-align:center; padding:1px;" width="50">' +
																									'<a href="' + data + '" target="_blank">' +
																										'<img src="' + data + '" alt="" title="" border="0" style="margin:0px; border:2px solid #999999; width:40px; height:40px;" />' +
																									'</a>' +
																								'</td>' +
																								'<td style="text-align:left; padding:1px; vertical-align:top;">' +
																									'<input type="text" style="width:99%; margin-bottom:2px; border:1px solid #999999;" name="Photos[' + current_photo_counter + ']" id="Listing_Photo_' + current_photo_counter + '" value="' + data + '" />' + 
																									'<br/>' +
																									'<input type="radio" name="FeaturedImage" id="FeaturedImage" value="' + data + '" /> Featured Image' +
																									'&nbsp;&nbsp;&nbsp;<input type="checkbox" name="removedPhoto[' + current_photo_counter + ']" value="' + current_photo_counter + '" /> Remove Image' +
																								'</td>' +
																							'</tr>' +
																						'</table>' +
																						'<div style="clear:both; height:0px;"></div>' +
																					'</div> '); 
                                            }
                                       });
                                    });
                                    document.getElementById('div_add_photos').innerHTML = '';
                                };
                            });
                            </script>
                            <input type="hidden" name="add_photos" id="add_photos" value=""  class="upload" />
                            <input class="upload-button" name="wsl-image-add" type="button" value="Upload/Browse Media Library" style="padding:3px; margin:0px; cursor:pointer; font-size:12px;" />
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:10px; font-size:10px;">
							<?php $pht = 0; ?>
                            <input name="photo_counter" id="photo_counter" type="hidden" style="width:20px; text-align:center;" value="<?php echo $pht; ?>" />
                            <div id="property_photos">
                                <?php if(count($listing['Photos']) > 0) { ?>
                                    <?php foreach($listing['Photos'] as $listing_photo) { ?>
                                        <?php #if(@fopen($listing_photo,'r') !== false) { ?>	
                                            <div style="float:left; margin:4px; padding:2px 2px 0px; border:1px solid #999999; width:48%; text-align:center; vertical-align:middle; font-size:10px; color:#333333;"> 
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                	<tr>
                                                    	<td style="text-align:center; padding:1px;" width="50">
                                                        	<a href="<?php echo $listing_photo; ?>" target="_blank">
                                                                <img src="<?php echo $listing_photo; ?>" alt="" title="" border="0" style="margin:0px; border:2px solid #999999; width:40px; height:40px;" />
                                                            </a>
                                                        </td>
                                                    	<td style="text-align:left; padding:1px; vertical-align:top;">
															<script type="text/javascript">
                                                            jQuery('#photo_counter').val('<?php echo $pht; ?>'); 
                                                            </script>
                                                            <input type="text" style="width:99%; margin-bottom:2px; border:1px solid #999999; color:#666666;" name="Photos[<?php echo $pht; ?>]" id="Listing_Photo_<?php echo $pht; ?>" value="<?php echo $listing_photo; ?>" /> 
                                                            <br/>
                                                            <input type="radio" name="FeaturedImage" id="FeaturedImage" value="<?php echo $listing_photo; ?>" <?php if($listing['FeaturedImage'] == $listing_photo){ echo 'checked="checked"'; } ?> /> Featured Image
                                                            &nbsp;&nbsp;&nbsp;<input type="checkbox" name="removedPhoto[<?php echo $pht; ?>]" value="<?php echo $pht; ?>" /> Remove Image
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php $pht++; ?>
                                                <div style="clear:both; height:0px;"></div>
                                            </div>  
                                        <?php #} ?>   
                                    <?php } ?>
                                <?php } ?>   
                            </div> 
                            <div style="clear:both; height:0px;">&nbsp;</div>
                            <div id="div_add_photos" style="display:none;"></div>   
                        </td>
                    </tr> 
                </table>                
                
            </div>
			<?php
			
		}
	 

	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Format the Content of the Property Listing Post Type (single page content)
	* ------------------------------------------------------------------------------------------------------------------
	*/
	
		function plugin_ae_customposttype_fields_property_listings($content) {
		
			global $post;
			
			if($post->post_type == "property_listings") { 
			
				$wp_plugin_aem_params = wp_plugin_aem_params();
				
				$listing = array();
				
				// Property Details
				$listing['Title'] 				= get_the_title();
				$listing['Address'] 			= '';
				$listing['MLS'] 				= '';
				$listing['Bedrooms'] 			= '';
				$listing['Bathrooms'] 			= '';
				$listing['PropertyType'] 		= '';
				$listing['Neighborhood'] 		= '';
				$listing['Description'] 		= '';
				$listing['ListingPrice'] 		= '';
				$listing['SellingPrice'] 		= '';
				$listing['SoldDate'] 			= '';
				$listing['ListingAgent'] 		= '';
				$listing['ListingOffice']		= '';
				$listing['Status'] 				= '';
				
				// PrimaryDetails
				$listing['CrossStreet'] 		= '';
				$listing['ApproximateSqFt'] 	= '';
				$listing['PricePerSqFt'] 		= '';
				$listing['YearBuilt'] 			= '';
				$listing['TotalRooms'] 			= '';
				$listing['HOADues'] 			= '';
				
				// AdditionalDetails
				$listing['Parking'] 			= '';
				$listing['Type']				= '';
				$listing['Style'] 				= '';
				$listing['Floors'] 				= '';
				$listing['BathTypeIncludes']	= '';
				$listing['Kitchen'] 			= '';
				$listing['DiningRoom'] 			= '';
				$listing['LivingRoom'] 			= '';
				$listing['HeatingCoolingSystem']= '';
				$listing['LaundryAppliances'] 	= '';
				$listing['SpecialFeatures'] 	= '';
				$listing['CommonAreas'] 		= '';
				$listing['Transportation'] 		= '';
				$listing['Shopping'] 			= '';
				//$listing['Comment'] 			= '';
				
				// photos/images
				$listing['FeaturedImage'] 		= '';
				$listing['Photos'] 				= '';

				$custom = get_post_custom($post->ID);
				
				if(is_array($custom)) {
					foreach($custom as $field_key => $field_value) {
						$listing[$field_key] = $field_value[0];
					}
				}
				
				if(isset($listing['Photos']) && !empty($listing['Photos'])) {
					$listing['Photos'] = unserialize($listing['Photos']);
				}
				if(!is_array($listing['Photos'])) {
					$listing['Photos'] = array();
				}

				$listing['Description'] = $content;
				
				$listings[0] = $listing;
				
				$content = "";
				
				ob_start();
				// ------------------------------------------------------------------------------------------------------
				?>
                <div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
                    <link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
                </div>
                
                <div class="property_details">
                
                    <?php if(count($listing) > 0) { // check listing exists/found ?>
                    
                        <?php if($listing["Address"] != "") { ?>
                            <h2 style="margin-bottom:10px; font-size:18px;"><?php echo $listing["Address"]; ?></h2>
                        <?php } ?>
                       
                        <?php if($listing["ListingPrice"]) { ?>
                            Listing Price: $<?php echo number_format(str_replace(array(',','$'), '', $listing["ListingPrice"])); ?><br>
                        <?php } ?>
                
                        <?php if ($listing["Bedrooms"]) { ?>
                            <?php echo number_format($listing["Bedrooms"], 0, '', ''); ?> Beds,
                        <?php } ?>
                        <?php if ($listing["Bathrooms"]) { ?>
                            <?php echo number_format($listing["Bathrooms"], 0, '', ''); ?> Baths
                        <?php } ?>
                        
                        <?php if ($listing["Status"]) { ?>
                            Status: <?php echo $listing["Status"]; ?><br>
                        <?php } ?>
                
                        <?php if ($listing["MLS"]) { ?>
                            MLS Listing#: <?php echo $listing["MLS"]; ?><br>
                        <?php } ?>
                                        
                        <?php if ($listing["Description"]) { ?>
                            <p><?php echo $listing["Description"];?></p>
                        <?php } ?>
                        
                        <?php 
						// additional details
						$additional_details = array();
						if(!empty($listing['PropertyType'])) 		{ $additional_details['Property Type']			= $listing['PropertyType']; }
						if(!empty($listing['Neighborhood'])) 		{ $additional_details['Neighborhood'] 			= $listing['Neighborhood']; }
						if(!empty($listing['SellingPrice'])) 		{ $additional_details['Selling Price'] 			= $listing['SellingPrice']; }
						if(!empty($listing['SoldDate'])) 			{ $additional_details['Sold Date'] 	 			= $listing['SoldDate']; }
						if(!empty($listing['CrossStreet'])) 		{ $additional_details['CrossStreet'] 		 	= $listing['CrossStreet']; }
						if(!empty($listing['ApproximateSqFt'])) 	{ $additional_details['Approximate Sq Ft'] 		= $listing['ApproximateSqFt']; }
						if(!empty($listing['PricePerSqFt'])) 		{ $additional_details['Price Per Sq Ft']   		= $listing['PricePerSqFt']; }
						if(!empty($listing['YearBuilt'])) 			{ $additional_details['Year Built'] 		 	= $listing['YearBuilt']; }
						if(!empty($listing['TotalRooms'])) 			{ $additional_details['Total Rooms'] 		 	= $listing['TotalRooms']; }
						if(!empty($listing['HOADues'])) 			{ $additional_details['HOA Dues'] 			 	= $listing['HOADues']; }
						if(!empty($listing['Parking'])) 			{ $additional_details['Parking'] 				= $listing['Parking']; }
						if(!empty($listing['Type'])) 				{ $additional_details['Type'] 					= $listing['Type']; }
						if(!empty($listing['Style'])) 				{ $additional_details['Style']	 				= $listing['Style']; }
						if(!empty($listing['Floors'])) 				{ $additional_details['Floors'] 				= $listing['Floors']; }
						if(!empty($listing['BathTypeIncludes']))	{ $additional_details['Bath Type Includes'] 	= $listing['BathTypeIncludes']; }
						if(!empty($listing['Kitchen'])) 			{ $additional_details['Kitchen']				= $listing['Kitchen']; }
						if(!empty($listing['DiningRoom'])) 			{ $additional_details['Dining Room'] 			= $listing['DiningRoom']; }
						if(!empty($listing['LivingRoom'])) 			{ $additional_details['Living Room'] 			= $listing['LivingRoom']; }
						if(!empty($listing['HeatingCoolingSystem'])){ $additional_details['Heating Cooling System'] = $listing['HeatingCoolingSystem']; }
						if(!empty($listing['LaundryAppliances'])) 	{ $additional_details['Laundry Appliances'] 	= $listing['LaundryAppliances']; }
						if(!empty($listing['SpecialFeatures'])) 	{ $additional_details['Special Features'] 		= $listing['SpecialFeatures']; }
						if(!empty($listing['CommonAreas'])) 		{ $additional_details['Common Areas'] 			= $listing['CommonAreas']; }
						if(!empty($listing['Transportation'])) 		{ $additional_details['Transportation'] 		= $listing['Transportation']; }
						if(!empty($listing['Shopping'])) 			{ $additional_details['Shopping'] 				= $listing['Shopping']; }
						?>
                       
                       	<?php if(count($additional_details) > 0) { ?>
                        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<?php $ad=0; foreach($additional_details as $ad_key => $ad_val) { ?>
                                    <tr>
                                        <td width="30%" style="padding:5px; border-bottom:1px dashed #CCCCCC; <?php if($ad == 0) { echo 'border-top:1px dashed #CCCCCC;'; } ?>">
                                        	<span><?php echo $ad_key; ?></span>
                                        </td>
                                        <td width="1" style="padding:0px; border-bottom:1px dashed #CCCCCC; <?php if($ad == 0) { echo 'border-top:1px dashed #CCCCCC;'; } ?>">: </td>
                                        <td width="70%" style="padding:5px; border-bottom:1px dashed #CCCCCC; <?php if($ad == 0) { echo 'border-top:1px dashed #CCCCCC;'; } ?>">
											<span><?php echo $ad_val; ?></span>
                                        </td>
                                    </tr>
                                <?php $ad++; } ?>
                            </table>
                        <?php } ?>
                       	
                        <?php if(count($listing['Photos']) > 0) { ?>	
                           
                            <div class="clear">&nbsp;</div>
                       
                            <?php if(count($listing['Photos']) > 1) { ?>	
                                <p><strong>Photos</strong></p>
                            <?php } ?>
                            
                            <?php if(count($listing['Photos']) > 1) { ?>	
                                <script type="text/javascript" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/js/jquery-1.5.2.min.js"></script>
                                <script type="text/javascript">
                                $.noConflict();
                                jQuery(document).ready(function($) {
                                    jQuery('#images_thumbnails a').click(function(){
                                      var newImageSrc = jQuery(this).attr('href');
                                      jQuery('#images_full img').attr({'src': newImageSrc });
                                      return false;
                                    });
                                });
                                </script>
                            <?php } ?>  
                              
                            <div id="images_full" align="center">
                                <?php $nphoto = 0; ?>
                                <?php foreach($listing['Photos'] as $photo){ ?>
                                    <?php $nphoto++; ?>
                                    <?php if($nphoto <= 1) { ?>
                                        <img src="<?php echo $photo; ?>" title="" alt="" />
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            
                            <?php if(count($listing['Photos']) > 1) { ?>	
                                <div id="images_thumbnails">
                                    <?php foreach($listing['Photos'] as $photo){ ?>
                                        <a href="<?php echo $photo; ?>" style="text-decoration:none;">
                                            <img width="70" height="70" src="<?php echo $photo; ?>" title="" alt="" />
                                        </a>
                                    <?php } ?>
                                    <div class="clear">&nbsp;</div>
                                </div>
                            <?php } ?>  
                              
                        <?php } ?>
                
                        <?php if($listing["Address"]) { //google map ?>            
                          
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
                            setMapAddress( "<?php echo $listing["Address"]; ?>" );
                            </script>
                          
                            <div id="location" class="singlecol right last">
                                <div id="map" class="singlecol right last">Loading Map...</div>
                            </div>
                            <a href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo urlencode($listing["Address"]); ?>" target="_blank" style="text-decoration:none; font-size:11px;">View Larger Map</a>
                            
                        <?php } ?>    
                         
                       <div class="clear" style="height:30px;">&nbsp;</div>
                               
               <?php /*?>         <!-- AddThis Button BEGIN (remove addthis_32x32_style to have small icons) -->
                        <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                            <a class="addthis_button_compact"></a> 
                            <a class="addthis_button_facebook"></a>
                            <a class="addthis_button_email"></a>
                            <a class="addthis_button_twitter"></a>
                            <a class="addthis_button_print"></a>
                            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d91919a33ca085b"></script>
                        </div>
                        <!-- AddThis Button END --><?php */?>
                    
                        <?php if ($listing["ListingAgent"] || $listing["ListingOffice"]) { ?>
                            <div class="clear" style="height:30px;">&nbsp;</div>
                            <div class="property_details-listing-courtesy">
                                This listing courtesy of <?php echo $listing["ListingAgent"]; ?><?php if($listing["ListingOffice"]) { echo ', '.$listing["ListingOffice"]; } ?>
                            </div>
                        <?php } ?>
                        
                        <div class="clear" style="height:30px;">&nbsp;</div>
                        
                     <?php } else { ?>
                    
                        <div class="property_details-noresult">
                            Property Not Found
                        </div>
                    
                    <?php } ?> 
                    
                </div>
				<?php 				
				// ------------------------------------------------------------------------------------------------------
				$content = ob_get_contents();
				
				ob_end_clean();		
			
			}
					
			return $content;
			
		}
	
		add_filter('the_content', 'plugin_ae_customposttype_fields_property_listings');


	/**
	* ------------------------------------------------------------------------------------------------------------------
	* Custom Post Type >> Property Listings >> Admin Sub Page for Shortcodes
	* ------------------------------------------------------------------------------------------------------------------
	*/

		add_action('admin_menu', 'register_property_listings_custom_submenu_page');
		
		function register_property_listings_custom_submenu_page() {
			add_submenu_page( 'edit.php?post_type=property_listings', 'Shortcodes', 'Shortcodes', 'manage_options', 'shortcode', 'property_listings_custom_submenu_page_callback' ); 
		}

		function property_listings_custom_submenu_page_callback() {
		
			?>
            <div class="wrap">
            
                <div class="icon32" id="icon-themes"><br></div>
                <h2 class="nav-tab-wrapper" style="border-bottom:none;">Property Listings Shortcodes</h2>
                
                <br/>
                
                <table cellspacing="0" class="widefat">
                    <tr class="alternate">
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;" width="15%">Shortcode</th>
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;">Usage</th>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            [AE_PROPERTY_LISTINGS]
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                             Add the trigger text (shortcode) to the page/post content to display the property listings.
                        </td>
                    </tr>
                </table>
    
                <br/>
                
                <table cellspacing="0" class="widefat">
                    <tr class="alternate">
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;" width="15%">Shortcode Options</th>
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;">Description</th>
                        <th scope="row" style="background:#D7D7D7; font-size:13px;" width="28%">Example</th>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            num  
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                            The number of records to display. By default it is set to display All records.
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS num="5"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            show_pagination  
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                            Set this to 'no' if you don't want to display the pagination. By default it is set to 'yes'. 
                            <br/>The pagination is automatically hidden if 'num' option is not set and if all records are already displayed. 
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS show_pagination="no"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            sort_order
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                             The sort order of records ('ASC' or 'DESC'). By default it is set it sort in 'ASC'.
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS order_sort="ASC"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            sort_by 
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                             Sort records by 'post_title', 'Status', 'Address', 'ListingPrice', 'SellingPrice', 'SoldDate'. By default it is sorted by 'post_title'.
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS order_by="title"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            id 
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                        	Filter records to display based on ID (comma separated IDs). Display only the records with specific IDs: 
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS id="1,2,3"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            exclude_id 
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                        	Filter records to display based on ID (comma separated IDs). Display all records but NOT the specified IDs. 
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS exclude_id="1,2,3"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                            status  
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                        	<?php 
							$pl_status = array();
							$pl_status[] = "Active";
							$pl_status[] = "Act. Cont."; //Active Contingent
							$pl_status[] = "Pending";
							$pl_status[] = "Sold";
							$pl_status[] = "Coming Soon";
							$pl_status[] = "Withdrawn";
							$pl_status[] = "Removed";
							?>
							<?php $plstatus = ''; foreach($pl_status as $plstat) { if($plstatus != '') { $plstatus .= ', '; } $plstatus .= $plstat; } ?>
                        	Filter records to display based on status: <?php echo $plstatus; ?>.
                        </td>
                        <td>
                            [AE_PROPERTY_LISTINGS status="Active"]
                        </td>
                    </tr>
                </table>
    
                <br/>
                
                <table cellspacing="0" class="widefat">
                    <tr class="alternate">
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;" width="15%">Example</th>
                        <th scope="row" style="background:#D7D7D7; font-size:13px; border-right:1px solid #CCCCCC;">Shortcode</th>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                             All Property Listings
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                            [AE_PROPERTY_LISTINGS num="10" show_pagination="yes" sort_order="ASC" sort_by="post_title" id="" exclude_id="" status=""]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                             Active Property Listings
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                            [AE_PROPERTY_LISTINGS num="10" show_pagination="yes" sort_order="ASC" sort_by="ListingPrice" id="" exclude_id="" status="Active"]
                        </td>
                    </tr>
                    <tr class="alternate">
                        <td style="border-right:1px solid #CCCCCC;">
                             Sold Property Listings
                        </td>
                        <td style="border-right:1px solid #CCCCCC;">
                            [AE_PROPERTY_LISTINGS num="10" show_pagination="yes" sort_order="DESC" sort_by="SoldDate" id="" exclude_id="" status="Sold"]
                        </td>
                    </tr>
                </table>
                
    
            </div>
			<?php 
		
		}


	/**
	* ----------------------------------------------------------------------------------------------------------------------
	*  Shortcodes: Property Listings
	* ----------------------------------------------------------------------------------------------------------------------
	*/
	
		function plugin_ae_settings_shortcode_property_listings($atts, $content = null) {
	
			global $query_string;
			
			$wp_plugin_aem_params = wp_plugin_aem_params();
						
			if(!isset($atts['num']) || empty($atts['num'])) {
				$atts['num'] = 0;
			}
			
			if(!isset($atts['show_pagination']) || empty($atts['show_pagination'])) {
				$atts['show_pagination'] = 'yes'; // yes, no 
			}
			
			if(!isset($atts['sort_order']) || empty($atts['sort_order'])) {
				$atts['sort_order'] = 'ASC'; // ASC, DESC 
			}
			
			if(!isset($atts['sort_by']) || empty($atts['sort_by'])) {
				$atts['sort_by'] = 'Status'; // date, title
			}
			
			if(!isset($atts['id']) || empty($atts['id'])) {
				$atts['id'] = 'N/A'; // comma separted IDs
			}
			
			if(!isset($atts['exclude_id']) || empty($atts['exclude_id'])) {
				$atts['exclude_id'] = 'N/A'; // comma separted IDs to exclude
			}

			if(!isset($atts['status']) || empty($atts['status'])) {
				$atts['status'] = 'N/A'; // Active, Act. Cont., Pending, Sold, Coming Soon, Withdrawn, Removed
			}
						
			$args = array(); 
			$args['post_type'] 	 = 'property_listings';
			$args['post_status'] = 'publish';
			
			if($atts['sort_by'] != 'post_title') {
				$args['meta_key'] = $atts['sort_by']; 
				//$args['orderby'] = 'meta_value_num'; // meta_value_num
				$args['orderby'] = $atts['sort_by'];
			} else {
				$args['orderby'] = $atts['sort_by']; 	
			}
			$args['order'] = $atts['sort_order'];		
			
			if($atts['id'] != 'N/A') {
				$args['post__in'] = explode(',', $atts['id']);
			}
			
			if($atts['exclude_id'] != 'N/A') {
				$args['post__not_in'] = explode(',', $atts['exclude_id']);
			}

			if($atts['num'] > 0) {
				$limit = $atts['num'];
			} else {
				$limit = 1000;
			}
			
			$loop = new WP_Query( $args );
			
			// cast the posts object into array
			$property_listings = (array)$loop->posts;
		
			if($atts['status'] != 'N/A') {
				if(count($property_listings) > 0) {
					foreach($property_listings as $pl_key => $pl_val) {
						$pl_listing = (array)$property_listings[$pl_key];
						$pl_customfields = get_post_custom($pl_listing['ID']);
						if($pl_customfields['Status'][0] == $atts['status']) {
							// ignore pl...
						} else {
							unset($property_listings[$pl_key]);
						}
					}
					$property_listings = array_values($property_listings);
				}
			}
		
			// Search Results: Pagination
			$frontText 		= "";
			
			$adjacents 		= 1;
			$targetpage 	= "index.php?";
			$pagestring 	= "pg=";
			$total_listings = count($property_listings);
			
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
							
			$data = '';
			
			ob_start();
			// -------------------------------------------------------------------------------------------------------
			?>
			<div class="<?php echo $args['post_type']; ?>'">
				
				<div style="margin:0px; padding:0px; visibility:hidden; display:none; background:none; border:none;">
					<link rel="stylesheet" href="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/css/style.css" type="text/css" media="screen" />
				</div>
				
				<div class="property_my_properties">
				
					<div id="div_loading_property_listings" style="padding:50px; text-align:center; vertical-align:middle;">
						<h1 style="text-align:center;">Loading...</h1>
						<img src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/loading.gif" alt="Loading Records..." title="Loading Records..." />
					</div>
						
					<div id="div_property_listings" style="display:none;">        
						
						<?php if(count($property_listings) > 0) { // count & check if has search results (listings) ?>
				
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
								
								<?php 
								$listing = array();
								
								// cast the object
								$listing = (array)$property_listings[$n];
								
								// Property Details
								$listing['Title'] 				= $listing['post_title']; 
								$listing['Address'] 			= '';
								$listing['MLS'] 				= '';
								$listing['Bedrooms'] 			= '';
								$listing['Bathrooms'] 			= '';
								$listing['PropertyType'] 		= '';
								$listing['Neighborhood'] 		= '';
								$listing['Description'] 		= $listing['post_content'];
								$listing['ListingPrice'] 		= '';
								$listing['SellingPrice'] 		= '';
								$listing['SoldDate'] 			= '';
								$listing['ListingAgent'] 		= '';
								$listing['ListingOffice']		= '';
								$listing['Status'] 				= '';
								
								// PrimaryDetails
								$listing['CrossStreet'] 		= '';
								$listing['ApproximateSqFt'] 	= '';
								$listing['PricePerSqFt'] 		= '';
								$listing['YearBuilt'] 			= '';
								$listing['TotalRooms'] 			= '';
								$listing['HOADues'] 			= '';
								
								// AdditionalDetails
								$listing['Parking'] 			= '';
								$listing['Type']				= '';
								$listing['Style'] 				= '';
								$listing['Floors'] 				= '';
								$listing['BathTypeIncludes']	= '';
								$listing['Kitchen'] 			= '';
								$listing['DiningRoom'] 			= '';
								$listing['LivingRoom'] 			= '';
								$listing['HeatingCoolingSystem']= '';
								$listing['LaundryAppliances'] 	= '';
								$listing['SpecialFeatures'] 	= '';
								$listing['CommonAreas'] 		= '';
								$listing['Transportation'] 		= '';
								$listing['Shopping'] 			= '';
								//$listing['Comment'] 			= '';
								
								// photos/images
								$listing['FeaturedImage'] 		= '';
								$listing['Photos'] 				= '';
				
								// get permalink
								$listing['permalink'] = get_permalink($listing['ID']);
	
								// get custom data fields
								$custom = get_post_custom($listing['ID']);
								if(is_array($custom)) {
									foreach($custom as $field_key => $field_value) {
										$listing[$field_key] = $field_value[0];
									}
								}
								
								if(isset($listing['Photos']) && !empty($listing['Photos'])) {
									$listing['Photos'] = unserialize($listing['Photos']);
								}
								if(!is_array($listing['Photos'])) {
									$listing['Photos'] = array();
								}
								?>
								
								<?php if($listing["MLS"] != "" || $listing["Address"] != "") { ?>
								
									<?php
									if($listing["MLS"] == "") { $listing["MLS"] = 0; }
									?>
								
									<div class="property_my_properties-item" id="#<?=$n+1;?>" style="padding:10px 0px 25px; <?php if($n == $rec_min) { echo 'padding-top:20px; border-top: 1px solid #CCCCCC;'; } ?>">
										<div class="property_my_properties-thumb-container">
										    <div class="sash status<? 
											switch ($listing["Status"]) {
    case "Active":
        echo "-active";
        break;
    case "Sold":
        echo "-sold";
        break;
  
}
				?>"><?php echo $listing["Status"]; ?></div>
											<a href="<?php echo $listing['permalink']; ?>">
												<?php if($listing["FeaturedImage"] != "") { ?>
													<img height="125" border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $listing["FeaturedImage"]; ?>" style="margin:0px; padding:0px; border:none;" />
												<?php } else { ?>
													<img height="125" border="0" width="125" class="property_my_properties-thumbnail" src="<?php echo $wp_plugin_aem_params['AEM_PLUGIN_URL']; ?>/images/no_image_available.jpg" style="margin:0px; padding:0px; border:none;" />
												<?php } ?>
											</a>
										</div>
										<div class="property_my_properties-detail"> 
											<a href="<?php echo $listing['permalink']; ?>">
												<?php echo $listing["Title"]; ?>
											</a>
                                            <br/><?php echo $listing["Address"]; ?>
											<br/><?php echo $listing["PropertyType"]; ?>
											<? if ($listing["ListingPrice"]) { ?>
                                            <br/>Offered at: $<?php echo number_format(str_replace(array(',','$'), '', $listing["ListingPrice"])); ?>
                                           <? } ?>
										</div>
									</div>
									
									<div class="clearfix" style="height:0px;">&nbsp;</div>
									
								<?php } ?>
								
								<?php $n++; // counter ?>
						
							<?php } // end while ?>
							
							<div class="clearfix" style="height:15px;">&nbsp;</div>
							
							<!-- Pagination -->
							<?php 
							if($atts['show_pagination'] == 'yes') {
								echo plugin_aem_getPaginationString($frontText, $pg, $total_listings, $limit, $adjacents, $targetpage, $pagestring); 
							}
							?>
					
							<div class="clearfix">&nbsp;</div>
						   
						<?php } else { ?>  
							
							<div class="property_property_listings-noresult">
								No Records Found.
							</div>
								  
						<?php } ?> 
						
					</div>
								
				</div>
						
				<script language="JavaScript">
				document.getElementById('div_loading_property_listings').style.display='none';
				document.getElementById('div_property_listings').style.display='block';
				</script> 
				
			</div>
			<?php 
			// -------------------------------------------------------------------------------------------------------
			$data = ob_get_contents();
			ob_end_clean();		
			
			wp_reset_query();
	
			return $data;
	
		}
		
		add_shortcode('AE_PROPERTY_LISTINGS', 'plugin_ae_settings_shortcode_property_listings');
		
		  
// End of file: agenteasy-properties.php ===============================================================================
?>