<?php
require "eq.php";

eq_start("style.css", "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js");

$title = "AnonChat";
if (isset($_GET['room'])) {
	$title .= " - {$_GET['room']}";
}
eq_title($title);

eq_div("chatmessages", "messages");
eq_div_end(2);

eq_div("chatbar");
	eq_html("<input class='chatinput' type='text' id='chatinput'>");
	eq_link("!onclick=submit_input()", "⏩", "chatlink", false);
eq_div_end();

eq_script("var room = \"{$_GET['room']}\";");
eq_script("script.js");

eq_end();

?>