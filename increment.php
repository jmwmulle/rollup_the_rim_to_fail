<?php

include_once("common.php");
extract($_POST);
extract($_GET);
switch ($action) {
	case "remove":
		$q = "DELETE FROM `rolls` WHERE `id` = $rollId";
		$db->query($q);
		break;
	case "toggle":
		$q = "UPDATE `rolls` SET `won` = $outcome WHERE `id` = $rollId";
		$db->query($q);
		break;
	case "add":
		$q = "INSERT INTO `rolls` (`roller_id`, `won`, `year`) VALUES ($rollerId, $outcome, $current_year)";
		$db->query($q);
		break;
}
ob_start();
	$rollerId;
	include("register_roll.php");
//echo ob_get_contents()
?>
