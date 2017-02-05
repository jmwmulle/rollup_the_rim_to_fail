<?php
/**
 * J. Mulle, for rollup, 2/5/17 12:06 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

function pr($var) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
	return true;
};

$db 	= new mysqli("localhost", "root", "fr0gstar", "rollup");
$current_year = 2017;