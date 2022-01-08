<?php
require "eq.php";

eq_start("style.css", "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js");

if (isset($_SERVER['QUERY_STRING'])) {
	$room = explode("/", $_SERVER['QUERY_STRING'])[0];
}

$home  = true;
$title = "AnonChat";
if ($room != "") {
	$title .= " - {$room}";
	$home	= false;
}
eq_title($title);

if (!$home) {
	eq_div("topbar");
		eq_text($title . " <sub>(<cnt id='counter'></cnt>)</sub>", "h2");
	eq_div_end(2);
	// Actually include a chatroom
	eq_div("chatmessages", "messages");
	eq_div_end(2);

	eq_div("chatbar");
		eq_html("<input class='chatinput' type='text' id='chatinput'>");
		eq_link("!onclick=submit_input()", "⏩", "chatlink", false);
	eq_div_end();

	eq_script("var room = \"{$room}\";");
	eq_script("script.js");
} else { 
	eq_div("messageholder");
		eq_text("Hey there!", "h1");
		eq_text(["Welcome to anon-chat.", "You've landed on the homepage, so there's not much here at the moment.","That's okay though! There are an infinite number of chatrooms around this site.", "Why don't you head over to the " . eq_link("Welcome") . " chatroom first?"], "p");
	eq_div_end();
}
eq_end();

?>