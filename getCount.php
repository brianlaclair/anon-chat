<?php
if (isset($_POST['rm'])) {
	
	$room 		= $_POST['rm'];
	
	$path		= "chats/{$room}/";
	
	// Create our container if it doesn't exist
	if (!is_dir($path)) {
		
		echo '0';
		
	} else {
		
		$filecount 	= 0;
		$files 		= glob($path . "*.json");
		if ($files){
			$filecount = count($files);
		}
		echo $filecount;
		
	}
		
	
}