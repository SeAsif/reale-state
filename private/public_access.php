<?php 
global $_allowed_resources ;

	$_allowed_resources = array(
	
 		'admin'=>array(
			'index'=>array(
				"login",
				"logout"
			),
		),
		
		'default'=>array(
			"error",
			"social",
			"index",
			"homevaluation",
			"leadmagent",
			"sellermagent",
			"leadmagenttwo",
			"static",
			"user",
			"payment",
			"custombuyer",
			"customhomevaluation",
			"customtemp1",
			"customtemp2",
			"customtemp3",
			"customtemp4",
			"customtemp5",
			"job"=>array("viewjob","paypalnotification"),
			//"lead"
 		)
	);




