<?php
if (isset($_POST['rm']) && isset($_POST['start'])) {
	$room = $_POST['rm'];
	$start = $_POST['start'];
	
	function stringToColorCode($str) {
	  $code = dechex(crc32($str));
	  $code = substr($code, 0, 6);
	  return $code;
	}
	
	while (file_exists("chats/{$room}/{$start}.json")) {
		$fpath = "chats/{$room}/{$start}.json";
		$file = fopen($fpath, "r");
		$dataArray = json_decode(fread($file, filesize($fpath)), true);
		fclose($file);
		
		$usercolor = stringToColorCode($dataArray['ip']);
		
		echo "<div class='messageholder' style='background-image: 	linear-gradient(to right, #adadad, #{$usercolor});'>[{$dataArray['timestamp']}] - {$dataArray['ip']} says...<br><div class='messagetext'>{$dataArray['text']}</div></div><br>";
		$start++;
		
	}
	
	
}