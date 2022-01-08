<?php

//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░███████╗░██████╗░░░░██████╗░██╗░░██╗██████╗░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░██╔════╝██╔═══██╗░░░██╔══██╗██║░░██║██╔══██╗░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░█████╗░░██║██╗██║░░░██████╔╝███████║██████╔╝░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░██╔══╝░░╚██████╔╝░░░██╔═══╝░██╔══██║██╔═══╝░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░███████╗░╚═██╔═╝░██╗██║░░░░░██║░░██║██║░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░╚══════╝░░░╚═╝░░░╚═╝╚═╝░░░░░╚═╝░░╚═╝╚═╝1.0.0░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   EQ.PHP is written by Brian LaClair   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ brianlaclair.com/eq for help & license ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
//░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

function eq_start() {
	$_numArgs = func_get_args();
	
	// Create the buffer for all future EQ commands
	global $__eq_buffer, $__eq_html_attr;
	$__eq_buffer = ['head' => [], 'body' => []];
	$__eq_html_attr;
	
	// Automatically generate header content based on function arguments
	foreach ($_numArgs as $args) {
		// Match and do function
		if (is_array($args)) {
			$_pass = "";
			if (isset($args[1])) {
				$_pass = $args[1];
			}
			eq_meta($args[0], $_pass);
		} else if (substr_count($args, "eq_title=")) {
			eq_title(explode("_title=", $args)[1]);
		} else if (substr_count($args, "css") || substr_count($args, "eq_style=")) {
			if (substr_count($args, "eq_style=")) {
				$args = explode("_style=", $args)[1];
			}
			eq_style($args, true);
		} else if (substr_count($args, "js") || substr_count($args, "eq_script=")) {
			if (substr_count($args, "eq_script=")) {
				$args = explode("_script=", $args)[1];
			}
			eq_script($args, true);
		}
	}
	
	
}

function eq_end($return = false) {
	
	// Finalize the EQ buffer and echo
	global $__eq_buffer, $__eq_html_attr;
	
	$_head 			= "";
	$_body 			= "";
	$_attributes 	= "";
	
	foreach ($__eq_buffer['head'] as $head) {
		$_head .= "\n" . $head;
	}
	
	foreach ($__eq_buffer['body'] as $body) {
		$_body .= "\n" . $body;
	}
	
	// Our document template
	$_final = 
"<!DOCTYPE html>
<html{$_attributes}>
<head>{$_head}
</head>
<body>{$_body}
</body>
</html>";

	if (!$return) {
		echo $_final;
	} else {
		return $_final;
	}
	
}

function eq_html($html = "", $head = null) {
	
	// Auto-decide the $head argument based on context, if it's not set
	if (!isset($head)) {
		$head = true;
		if (_body_is_active()) { $head = false; }
	}
	
	if ($head) {
		_eq_add_head($html);
	} else {
		_eq_add_body($html);
	}
	
}

#region Internal Functions

function _eq_add_head($ln) {
	
	global $__eq_buffer;
	array_push($__eq_buffer['head'], $ln);
	
}

function _eq_add_body($ln) {
	
	global $__eq_buffer;
	array_push($__eq_buffer['body'], $ln);
	
}

function _body_is_active() {
	global $__eq_buffer;
	
	if (!count($__eq_buffer['body'])) {
		return false;
	}
	
	return true;
}

function _eq_url_exists($url) {
	
	$headers = @get_headers($url);
  
	if($headers && strpos( $headers[0], '200')) {
		return true;
	}
	
	return false;
	
}

// returns an HTML formatted string for class and id declarations
function _eq_prefix($class, $id) {
	
	$_class = "";
	$_id 	= "";
	
	if (isset($class)) {
		$_class = " class=\"{$class}\""; 
	}
	
	if (isset($id)) {
		$_id = " id=\"{$id}\""; 
	}
	
	return "{$_class}{$_id}";
	
}

#endregion

#region Head Functions

function eq_title($title) {
	
	_eq_add_head("<title>{$title}</title>");
	
}

function eq_meta($name = "", $content = "") {
	
	$_first 			= "name=";
	$_second 			= "content=";
	
	$_ht_equ_array 		= ["content-security-policy", "content-type", "default-style", "refresh"];
	$_charset_array 	= ["ascii", "ansi", "8859-1", "utf-8"];
	$_social_array		= ["og:", "fb:", "article:"]; // Just a quick note that this is very dumb - Twitter gets it right though and deserves lots of clapping
	
	// If our type is in the header array
	if (in_array(strtolower($name), $_ht_equ_array)) {
		// We can assume we're setting http-equiv
		$_first = "http-equiv=";
	}
	
	// If our type is in the charset array
	if (in_array(strtolower($name), $_charset_array)) {
		// We can assume we're setting charset
		$_first = "charset=";
	}
	
	// Check for social tags that need modification - again, this is so dumb. 
	foreach($_social_array as $_social) {
		if (substr_count(strtolower($name), $_social)) {
			$_first = "property=";
		}
	}
	
	$_hold = "meta {$_first}\"{$name}\"";
	
	if ($content !== "") {
		$_hold .= " {$_second}\"{$content}\"";
	}
	
	_eq_add_head("<{$_hold}>");
	
}

#endregion

#region Styling and Scripts

function eq_style($stylesheet, $head = null) {
	
	// Auto-decide the $head argument based on context, if it's not set
	if (!isset($head)) {
		global $__eq_buffer;
		if (!count($__eq_buffer['body'])) {
			$head = true;
		} else {
			$head = false;
		}
	}
	
	// Dynamically check if the CSS is going to be included by the browser, or if it's written on the page
	if (_eq_url_exists($stylesheet) || file_exists($stylesheet)) {
		$_hold = "<link rel=\"stylesheet\" href=\"{$stylesheet}\">";
	} else {
		$_hold = "<style>{$stylesheet}</style>";
	}
	
	if ($head) {
		_eq_add_head($_hold);
	} else {
		_eq_add_body($_hold);
	}
	
}

function eq_script($script, $head = null) {
	
	// Auto-decide the $head argument based on context, if it's not set
	if (!isset($head)) {
		global $__eq_buffer;
		if (!count($__eq_buffer['body'])) {
			$head = true;
		} else {
			$head = false;
		}
	}
	
	// Dynamically check if the script is going to be included by the browser, or if it's written on the page
	if (_eq_url_exists($script) || file_exists($script)) {
		$_hold = "<script src=\"{$script}\"></script>";
	} else {
		$_hold = "<script>{$script}</script>";
	}
	
	if ($head) {
		_eq_add_head($_hold);
	} else {
		_eq_add_body($_hold);
	}
	
}

#endregion

#region Div Functions
function eq_div($class = NULL, $id = NULL, $attr = NULL) {
	
	$_attr  = "";
	if (isset($attr)) {
		$_attr = " " . $attr;
	}
	
	_eq_add_body("<div" . _eq_prefix($class, $id) . "{$_attr}>");
	
}

function eq_div_end($ittr = 1) {
	if (is_integer($ittr)) {
		for($i = 0; $i < $ittr; $i++) {
			_eq_add_body("</div>");
		}
	} else {
		eq_div($ittr);
		eq_div_end();
	}
}

#endregion

#region Content

function eq_text($text = "", $type = NULL, $class = NULL, $return = NULL) {
	$_class = "";
	$_typeStart = "";
	$_typeEnd	= "";
	
	if (isset($class)) {
		$_class = " class=\"{$class}\"";
	}
	
	if (!is_array($text)) {
		$text = [$text];
	}
	
	if (isset($type) || isset($class)) {
		if (!is_array($type)) {
			$type = [$type];
		}
		
		// Set up the type vars - essentially this is useful if we want to say something like ["div class=X", "p"] in our type argument,
		// so that instead of closing with </div class=X">, it closes with </div>
		foreach ($type as $t) {
			$_typeStart .= "<{$t}{$_class}>";
			$_tMod 		= explode(" ", $t)[0];
			$_typeEnd	= "</{$_tMod}>" . $_typeEnd;
		}
	}
	
	if (!isset($return)) {
		$return = false;
	}
	
	if (!$return) {
		foreach ($text as $_text) {
			_eq_add_body("{$_typeStart}{$_text}{$_typeEnd}");
		}
	} else {
		$_fin = "";
		foreach ($text as $_text) {
			$_fin .= "{$_typeStart}{$_text}{$_typeEnd}" . "\n";
		}
		return $_fin;
	}
	
}

// Note for doc writing - note the default behavior of this function is different than most others, as it's intended to be used in conjunction with eq_text
function eq_link($url = "", $text = null, $class = null, $return = true) {
	$_class = "";
	$_text  = "";
	$_prefix = "href";
	
	if (mb_strlen($url) > 0 && $url[0] == "!") {
		$_url = explode("=", $url, 2);
		$url = $_url[1];
		$_prefix = ltrim($_url[0], "!");
	}
	
	if (isset($class)) {
		$_class = " class=\"{$class}\"";
	}
	
	if (isset($text)) {
		$_text = $text;
	} else {
		$_text = $url;
	}
	
	$_fin = "<a {$_prefix}=\"{$url}\"{$_class}>{$_text}</a>";
	
	if ($return) {
		return $_fin;
	} else {
		_eq_add_body($_fin);
	}
	
}

function eq_image($url = "", $class = null, $attr = null, $return = false) {
	
	$_attr = "";
	if (isset($attr)) {
		$_attr = " " . $attr;
	}
	
	if (!is_array($url)) {
		$url = [$url];
	}
	
	$_ret = [];
	
	foreach ($url as $value) {
		array_push($_ret, "<img" . _eq_prefix($class, null) . " src=\"{$value}\"{$_attr}>");
	}
	
	if (!$return) {
		foreach($_ret as $value) {
			_eq_add_body($value);
		}
	} else {
		$_fin = "";
		foreach($_ret as $value) {
			$_fin .= $value;
		}
		return $_fin;
	}
	
}

function eq_br($ittr = 1, $return = null) {
	if (!isset($return)) { $return = false; }
	
	$_fin = "";
	for($i = 0; $i < $ittr; $i++) {
		$_fin .= "</br>";
	}
	
	if ($return) {
		return $_fin;
	} else {
		_eq_add_body($_fin);
	}
	
}

function eq_caption($text = "", $class = null) {
	
	$_class = "";
	if (isset($class)) {
		$_class = " class=\"{$class}\"";
	}
	
	_eq_add_body("<caption{$_class}>{$text}</caption>");
}

function eq_button($text = "", $action = null, $class = null) {
	
	$_class = "";
	$_attr 	= "";
	
	if (isset($class)) {
		$_class = " class=\"{$class}\"";
	}
	
	if (isset($attr)) {
		$_attr = " {$action}";
	}
	
	_eq_add_body("<button{$_class}{$_attr}>{$text}</button>");
	
}

#region Tables

/**
 * Open a new table
 * @function
 * @param {string} [class] - The class to set the table to
 * @param {string} [id] - The ID of the table
 */
function eq_table($class = null, $id = null) {
	
	_eq_add_body("<table" . _eq_prefix($class, $id) . ">");
	
}

/**
 * Add a row to the open table
 * @function
 * @param {...string} [cell_value] - value of the cell
 */
function eq_table_row() {
	$_numArgs = func_get_args();
	
	_eq_add_body("<tr>");
	
	foreach ($_numArgs as $arg) {
		_eq_add_body("<td>{$arg}</td>");
	}
	
	_eq_add_body("</tr>");
	
}

/**
 * Closes the open table
 * @function
 */
function eq_table_end() {
	
	_eq_add_body("</table>");
	
}
#endregion

#endregion
?>