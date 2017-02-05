<?php
/**
 * J. Mulle, for rollup, 2/4/17 7:05 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

include_once("common.php");
extract($_GET);
$roller_res = $db->query("SELECT `id` FROM `rollers` WHERE `roller` = '$roller'");
$roller_id = null;
if ($roller_res->num_rows != 0) {
	$roller_id = $roller_res->fetch_all()[0][0];
} else {
	$db->query("INSERT INTO `rollers` (`roller`) VALUES ('$roller')");
	$roller_id = $db->insert_id;
}

$registered_res = $db->query("SELECT `id` FROM `registered` WHERE `roller_id` = $roller_id");
if (!$registered_res->num_rows) $db->query("INSERT INTO `registered` (`roller_id`, `year`) VALUES ($roller_id, $year)");
?>

