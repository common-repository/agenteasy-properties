<?php 
/**
* ----------------------------------------------------------------------------------------------------------------------
* Plugin >> Widget Template for Agent Information
* ----------------------------------------------------------------------------------------------------------------------
*/
?>
<?php 
$error = 0;
$agent = array();

#if($wp_plugin_aem_params['plugin_aem_option_api_key'] == "") { $error++; }
if(count($data) <= 0) { $error++; }

// check if no error
if($error == 0) {

	// get the agent informations
	$agent['agentMLSID']= $data['agentMLSID'];
	$agent['image'] 	= $data['agentImage'];
	$agent['name'] 		= $data['agentName'];
	$agent['phone'] 	= $data['agentPhone'];
	$agent['mobile'] 	= $data['agentMobile'];
	$agent['fax'] 		= $data['agentFax'];
	$agent['dre'] 		= $data['agentDRE'];
	$agent['email'] 	= $data['agentEmail'];
	$agent['website'] 	= $data['agentWebsite'];
	$agent['office'] 	= $data['agentOffice'];
	$agent['agent1name'] 	= $data['agent1name'];
	$agent['agent1phone'] 	= $data['agent1phone'];
	$agent['agent2name'] 	= $data['agent2name'];
	$agent['agent2phone'] 	= $data['agent2phone'];
	$agent['address'] 	= $data['agentAddress'];
	
}
?>
<?php if($error == 0) { // if no error, display the agent information ?>

<div style="text-align:center">
  <?php if($agent['image'] != "") {?>
  <img class="photo" src="<?php echo $agent['image']; ?>" alt="" title="" style="max-width:200px; max-height:200px; border:none;" /><br/>
  <?php } ?>
  <?php if($agent['agent1name'] != "") {?>
  <div style="float: left;width:100px;"><strong><?php echo $agent['agent1name']; ?></strong><br/>
    <?php echo $agent['agent1phone']; ?></div>
  <div style="float: right;width:100px;"> <strong><?php echo $agent['agent2name']; ?></strong><br/>
    <?php echo $agent['agent2phone']; ?></div>
  <div class="clearfix"></div>
  <?php } ?>

    <?php if($agent['name'] != "") {?>
      <p>
    <strong><?php echo $agent['name']; ?></strong> </p>
    <?php } ?>
    <?php if($agent['mobile'] != "") {?>
 
    <strong>Mobile:</strong> <?php echo $agent['mobile']; ?>   <br/>
    <?php } ?>
    <?php if($agent['phone'] != "") {?>
 
    <strong>Phone:</strong> <?php echo $agent['phone']; ?>  <br/>
    <?php } ?>
    <?php if($agent['fax'] != "") {?>

    <strong>Fax:</strong> <?php echo $agent['fax']; ?>  <br/>
    <?php } ?>
    <?php if($agent['dre'] != "") {?>
 
  <p><br/>
    <strong>DRE#:</strong> <?php echo $agent['dre']; ?></p>
  <?php } ?>
  <?php if($agent['email'] != "") {?>
  <p><a href="mailto:<?php echo $agent['email']; ?>" style="text-decoration:none;"><?php echo $agent['email']; ?></a></p>
  <?php } ?>
  <?php if($agent['website'] != "") {?>
  <br/>
  <a href="<?php echo $agent['website']; ?>" style="text-decoration:none;" target="_blank";><?php echo $agent['website']; ?></a>
  <?php } ?>
  </p>
  <p>
    <?php if($agent['office'] != "") {?>
    <b><?php echo $agent['office']; ?></b>
    <?php } ?>
    <?php if($agent['address'] != "") {?>
    <br/>
    <?php echo nl2br($agent['address']); ?><br>
    <a target="_blank" href="http://maps.google.com/maps?q=<?php echo $agent['address']; ?>" style="text-decoration:none;">Map Directions</a>
    <?php } ?>
  </p>
</div>
<?php } ?>
