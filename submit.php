<?php

if (isset($_POST['rm']) && isset($_POST['msg'])) {
	
	$before 	= $_POST['msg'];
	
	$room 		= filter_var($_POST['rm'], FILTER_SANITIZE_STRING);
	$message 	= filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
	
	if (str_replace(" ", "", $before) == "" || str_replace(" ", "", $message) == "") {
		exit;
	}
	
	$path		= "chats/{$room}/";
	
	// Create our container if it doesn't exist
	if (!is_dir($path)) {
		mkdir($path);
	}
	
	// Gen ID
	$filecount 	= 0;
	$files 		= glob($path . "*.json");
	if ($files){
		$filecount = count($files);
	}
	
	// Decide on filename, write file
	$path 	   	= "{$path}{$filecount}.json";
	$timestamp 	= date("H:i:s - d/m/Y");
	$ip			= $_SERVER['REMOTE_ADDR'];
	$writeArray = ['timestamp' => $timestamp, 'ip' => $ip, 'text' => $message];
	
	$file 		= fopen($path, "w");
	fwrite($file, json_encode($writeArray));
	fclose($file);
	
}