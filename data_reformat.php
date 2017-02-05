<?php
/**
 * J. Mulle, for rollup, 2/3/17 12:51 PM
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
}

$rolls = [2013 => [
	[1, 'Matt', 5, 22],
	[2, 'Greg', 2, 10],
	[3, 'Jason', 1, 7],
	[4, 'Jono', 1, 14],
	[5, 'RJ', 3, 23],
	[6, 'Alex', 1, 12],
	[7, 'Shriel', 0, 4]],
	2015 => [
	[1, 'Jono', 3, 15],
	[2, 'RJ', 3, 25],
	[3, 'Alex', 3, 13],
	[4, 'Matt', 1, 11],
	[5, 'Greg', 0, 0],
	[11, 'Mike', 0, 0],
	[13, 'Shriel', 1, 5],
	[14, 'Dan', 0, 0],
	[15, 'Tariq', 0, 0]],
	
	2016 => [
	[1, 'Ray', 1, 4],
	[2, 'RJ', 3, 13],
	[3, 'Jono', 5, 16],
	[4, 'Ghislain', 0, 0],
	[5, 'Emily', 8, 20],
	[6, 'Brett', 0, 21],
	[7, 'Swasti', 1, 17],
	[8, 'Adam', 0, 0],
	[9, 'Austin', 0, 0],
	[19, 'Sabina', 0, 0],
	[20, 'Colin', 0, 10],
	[21, 'Tariq', 0, 0],
	[22, 'John', 0, 0],
	[23, 'Matt', 0, 0]]
];
$rolling = true;
$db = new mysqli("localhost", "root", "fr0gstar", "rollup");
$db->query("TRUNCATE TABLE rollers");
$db->query("TRUNCATE TABLE rolls");


foreach($rolls as $r_year => $r_data) {
	foreach ( $r_data as $r ) {
		$roller = $r[ 1 ];
		$id     = $db->query( "SELECT `id` from `rollers` WHERE `roller` = '$roller'" );

		if ( !$id->num_rows) {
			$db->query( "INSERT INTO `rollers` (`roller`) VALUES ('$roller')" );
			$id = $db->insert_id;
		}
		else {
			$id = $id->fetch_all()[ 0 ][0];
		};

		if ($rolling) {
			for ( $i = 0; $i < $r[ 2 ]; $i++ ) {
				$roll = 1;
				$db->query( "INSERT INTO `rolls` (`roller_id`, `won`, `year`) VALUES ($id, $roll, $r_year)" );
			};

			for ( $i = 0; $i < $r[ 3 ]; $i++ ) {
				$roll = 0;
				$db->query( "INSERT INTO `rolls` (`roller_id`, `won`, `year`) VALUES ($id, $roll, $r_year)" );
			};
		}
	}
}
