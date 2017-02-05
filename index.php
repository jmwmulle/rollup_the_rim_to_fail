<!--::DocumentDescription::Klein Lab roll up the rim to win contest (2013)::-->
<?php

include("common.php");

//$db 	= new mysqli("kleinlab.psychology.dal.ca", "jmwmulle", "fr0gstar", "rollup");

$years = [];
$registered =  $db->query("SELECT * FROM `registered` JOIN `rollers` ON `registered`.`roller_id` = `rollers`.`id`  AND `registered`.`year`= $current_year")->fetch_all();
for ($i=$current_year; $i>=2013; $i--) {
	$year = [];
	$year_data = $db->query("select * from `rollers` join `rolls` on `rollers`.`id` = `rolls`.`roller_id` where `year` = $i" )->fetch_all();
	$net_win = 0;
	$net_rolled = 0;
	foreach ($year_data as $yd) {

		if ( !array_key_exists($yd[ 1 ], $year ) ) {
			$year[ $yd[1] ] = ["id" => $yd[0], "won" => 0, "lost" => 0, "rolled" => 0, "winner" => false];
		}
		$year[ $yd[1] ]["rolled"]++;
		$net_rolled++;
		if ( $yd[ 4 ] ) {
			$year[ $yd[1] ]["won"]++;
			$net_win++;
		} else {
			$year[ $yd[ 1 ] ][ "lost" ]++;
		}

	}
	$year['net'] = ["won" => $net_win, "lost" => $net_rolled - $net_win, "rolled" => $net_rolled];
	$years[$i] = $year;
	$max_wins = [0, "no one"];
	foreach ($year as $p => $d) {
		if ( $d[ "won" ] > $max_wins[ 0 ] ) $max_wins = [ $d[ "won" ], $p ];
	}
	$years[$i][$max_wins[1]]["winner"] = true;
}
//pr($registered);
//exit();
foreach ($registered as $r) {
	if (!array_key_exists($r[4], $years[$current_year]) ) {
		$years[$current_year][$r[4]] = ["id" => $r[1], "won" => 0, "lost" => 0, "rolled" => 0, "winner" => false, "registered" => false];
	}
}

//array_reverse($years);
$euphemisms = ['Fool', 'Dolt', 'Chump', 'Sucker', 'Believer', "Optimist", "Mug", "Dupe", "Patsy", "Schlemiel"];
$all_losses_str = "Fail. So very fail.";
$all_wins_str = "Hacking. Obvs.";
$updating = false;
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>KleinLab vs Tim Hortons</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/foundation-icons.css" />
    <link rel="stylesheet" href="css/local.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="js/utils.js"></script>
  </head>

</head>
<body>
<?php if ($updating && $_SERVER['REMOTE_ADDR'] != "129.173.38.98"):?>
<h1>HANG ONTO YOUR RIMS; SITE HAS UPDATES; MOAR FAIL SOON</h1>
</body>
</html>
<?php else:?>
<div id="overhang" class="contain-to-grid sticky">
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name"><h1><i class="fi-graph-pie"></i>&nbsp;&nbsp; KleinLab: <span class="subheader_light">Roll Up The Rim To Fail</span></h1></li>
		</ul>
		<section class="top-bar-section">
			<ul class="right">
			<li> <a href="#" data-reveal-id="register-roller-modal">Sign Up For This Fail Season!</a></li>
			<?php for ($i = $current_year; $i>= 2013; $i--): ?>
				<li <?=$current_year == $i ? "class='active'": ""?>><a class="slide-anchor"  href="#anchor_<?=$i;?>"><?=$i;?></a></li>
			<?php endfor; ?>
			</ul>
		</section>
	</nav>
</div>
<div id="2016_wrapper" class="current-year">
	<a id="anchor_2016" name="anchor_2016">&nbsp;</a>
	<br /><br />
	<div class="row">
		<div class="small-12 columns text-center">
<!-- 		<h5 class="alert-box warning">This page is currently being worked on; use again in 10 minutes!<a href="#" class="close">&times;</a></h5>		 -->
			<h1 id="this-year" class="year-frame"><?=$current_year;?></h1>
		</div>
	</div>
<?php
	$net_res = null;
	$fail_performance = null;
	$class = null;
	$net_fail = $years[$current_year]['net']["lost"];
	$net_win = $years[$current_year]['net']["won"];
	$net_rolled = $years[$current_year]['net']["rolled"];
	if ($years[$current_year]['net']["rolled"] > 0 && $years[$current_year]['net']["lost"] > 0) {
		$fail_rate = substr($net_fail * 6 / ($net_fail + $net_win), 0, 5);
		$net_res = substr(($net_fail/($net_fail + $net_win)*100),0, 5)."% Fail";
		$fail_differential = ($net_fail / ($net_win + $net_fail) * 100) - (5/6 * 100);
		$fail_performance = substr($fail_differential / (1/6 * 100) * 100,0,5);
		$word = $fail_performance > 0 ? "Over-failing" : "Under-failing";
		$class = $fail_performance > 0 ? "red" : "green";
	} else {
		$net_res = "Failed to start failing. Wow. ";
		$word = "Failing has not yet commenced.";
		$class = "red";
	}
?>
	<div class="row">
		<div class="small-6 columns">
			<div class="row">
				<div class="small-12 columns">
					<div id="cumulative-despair" class="panel top-panel">
						<h3 class="expand">Cumulative Despair:</h3>
						<h1><span id='cumulative-results' class="subheader"><?=$net_res;?></span></h1>
						<p id="despair-stats">Won: <span id="net-win"><?="".$net_win;?></span>  | Lost: <span id='net-fail'><?=$net_fail;?></span> | Rolled: <span id='net-rolled'><?=$net_win + $net_fail;?></span></p>
					</div>
				</div>
			</div>
		</div>
		<div class="small-6 columns">
			<div class="row">
				<div class="small-12 columns">
					<div id="fail-performance-panel" class="panel top-panel">
						<h3 class="expand">Fail Performance:</h3>
							<h1><span id="fail-performance-descriptor" class="subheader <?=$class;?>"><?=$word;?></span>
							<span id="fail-performance" class="subheader"><?=substr($fail_performance, 0, 4).($fail_performance != null ? "%":"");?></span></h1>
							<?php
								if ($fail_performance == null): ?>
							<p id="no-rolls-rate">5 in 6 rolls are going to fail. Hoping makes you look silly.</p>
							<?php else:?>
							<p id="roll-rate">5 in 6 rolls should fail; we are failing at a rate of <span id='failrate'><?=$fail_rate;?></span> in 6</p>
							<?php endif;?>
					</div>
				</div>
			</div>
		</div>
		<div class="small-12 columns">
			<div class="row">
				<div class="small-12 columns">
					<div class="row">
						<div id="metafail-container" class="small-12 small-centered columns">
							<div id="metafail-form">
								<form class="custom">
									<fieldset id="metafail-fieldset">
										<legend>How Awful Is <u>Your</u> Metafail?</legend>
										<div class="row collapse">
											<div class="small-6 columns">
												<input id="consecutive-losses" name="consecutive-losses" type="text" placeholder="A number goes here." />
											</div>
											<div class="small-6 columns">
												<span class="postfix">Consecutive Losses</span>
											</div>
										</div>
										<h4 id="compute-metafail" class="small button expand radius">Ask The Ancients</h4>
										<div id="metafail-error" data-alert class="alert-box warning">
											<p>Sorry, we were looking for a number there. Kitteh emoticons just won't do.</p>
											<p class="text-center"><strong>The Ancients will not be duped!<strong></p>
											<a href="#" class="close">&times;</a>
										</div>
									</fieldset>
								</form>
							</div>
							<div id="metafail-result" class="hidden">
								<div id="metafail-solution" class="panel">
									<p>Yikes. The odds of failing <strong>that</strong> hard are about <span id="metafail-rate" class="failrate"></span>. All the sighs.</p>
									<h4 id="moar-metafail" class="small button expand radius">Moar Metafail</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!--
****************************************************************************************************************
                                            REGISTER NEW ROLLER MODAL
****************************************************************************************************************
-->

	<div id="register-roller-modal" class="reveal-modal" data-reveal aria-labelledby="register-roller-modal" aria-hidden="true" role="dialog">
		<div class="row">
			<div class="small-12 columns">
			<form class="custom">
				<fieldset>
					<legend>Planning on failing your way through the winter? Sign up!</legend>
					<div class="row collapse">
						<div class="small-6 columns">
							<input id="new-roller" name="new-roller" type="text" placeholder="Lab Member Destined to Fail" />
						</div>
						<div class="small-6 columns">
							<h4 id="add-new-roller" class="small button expand radius postfix">Register Now To Fail!</h4>
						</div>
					</div>
				</fieldset>
			</form>
			</div>
		</div>
		<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>

<!--
****************************************************************************************************************
                                                RECORD ROLL MODAL
****************************************************************************************************************
-->
	<div id="record-roll-modal" class="reveal-modal" data-reveal aria-labelledby="record-roll-modal" aria-hidden="true" role="dialog">
	</div>

	<div class="row">
		<div class="small-12 columns">
			<table style="width:100%" class="text-center" >
				<thead>
					<th><?=$euphemisms[rand(0, count($euphemisms) - 1)];?></th><th>Wins</th><th>Opportunities to <br />Try Again</th><th>Rims Rolled</th><th>Victoriousness</th><th>Win & Lose Buttons</th>
				</thead>
				<?php
					foreach($years[$current_year] as $p => $d):
						if ($p == "net" or $p == "no one") continue;
						$winning = $d['winning'];
						$w = $d['won'];
						$l = $d['lost'];
						$ratio = "No has data.";
						if ($w > 0 && $l == 0) {
							$ratio = "0.00%";
						}
						if ($w == 0 && $l > 0) {
							$ratio = $all_losses_str;
						}
						if ($w > 0 && $l == 0) {
							$ratio = $all_wins_str;
						}
						if ($w > 0 && $l > 0) {
							$rate = 100*($w / ($w + $l));
							$ratio = substr($rate, 0, 5)."%";
						}
					?>
					<tr id="<?= $d['id'];?>" <?=$winning ? "class='winning'" : "";?>>
						<td><?= $p;?></td>
						<td id="<?=$d['id'];?>_won" class="current-wins"><?= $d['won'];?></td>
						<td id="<?=$d['id'];?>_lost" class="current-losses"><?= $d['lost'];?></td>
						<td id="<?=$d['id'];?>_total" class="current-total"><?= $d['lost'] + $d['won'];?></td>
						<td id="<?=$d['id'];?>_ratio"> <?=$ratio;?></td>
						<td class="text-center">
<!--
							<ul class="win-loss-buttons radius button-group" style="width: 400px; margin-left:auto; margin-right:auto;">
								<li> <a class="incrementor  button" id="<?= $d['id'];?>_up" onclick="coffeeCounter(1, true, '<?= $d['id'];?>')"><i class="fi-plus"></i>&nbsp;<strong>W</strong></a></li>
								<li> <a class="incrementor  button" id="<?= $d['id'];?>_down" onclick="coffeeCounter(-1, true, '<?= $d['id'];?>')"><i class="fi-minus"></i>&nbsp;<strong>W</strong></a></li>
								<li> <a class="incrementor  button" id="<?= $d['id'];?>_up" onclick="coffeeCounter(1, false, '<?= $d['id'];?>')"><i class="fi-plus"></i>&nbsp;<strong>L</strong></a></li>
								<li> <a class="incrementor  button" id="<?= $d['id'];?>_down" onclick="coffeeCounter(-1, false, '<?= $d['id'];?>')"><i class="fi-minus"></i>&nbsp;<strong>L</strong></a></li>
							</ul>
-->
							<a href="#" id="register-roll-<?=$d['id'];?>" class="roll-registry" data-roller-id="<?=$d['id'];?>">Update The Fail Log</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
<?php foreach ($years as $year => $data) {
		if ($year == $current_year) continue;?>
<div id="wrapper_<?=$year;?>" class="previous-year previous-year-wrapper">
	<a name="anchor_<?=$year;?>"></a>
	<br/>
	<br/>
	<div id="content_<?=$year;?>" class="row previous-year-content">
		<div class="small-12 columns">
			<div class="row">
				<div class="small-12 columns">
					<h1 class="year-frame expand text-center"><?=$year;?></h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 columns">
					<table style="width:100%" class="text-center">
						<thead>
							<th><?=$euphemisms[rand(0, count($euphemisms) - 1)];?></th><th>Won</th><th>Lost</th><th>Rolled</th><th>Victoriousness</th>
						</thead>
					<?php
						foreach($data as $p => $d):
							if ($p == "net" or $p == "no one") continue;
							$l = $d['lost'];
							$w = $d['won'];
							$total = $d['rolled'];
							$ratio = "N/A";
							if ($w > 0 && $l == 0) {
								$ratio = "0.00%";
							}
							elseif ($w == 0 && $l > 0) {
								$ratio = "Utter. Fail.";
							}
							elseif ($w > 0 && $l == 0) {
								$ratio = "100.00%";
							}
							elseif ($w > 0 && $l > 0) {
								$rate = 100*($w / ($w + $l));
								$ratio = substr($rate, 0, 5)."%";
							}

							?>
							<tr id="<?= $d['id'];?>">
								<td><?= $p;?></td>
								<td id="<?= $d['id'];?>_won" ><?= $w;?></td>
								<td id="<?= $d['id'];?>_lost" ><?= $l;?></td>
								<td id="<?= $d['id'];?>_total" ><?= $l + $w;?></td>
								<td id="<?= $d['id'];?>_ratio"> <?= $ratio;?></td>
							</tr>
						 <?php endforeach; ?>

					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
<script>
var current_year = "<?=$current_year;?>";
var all_wins_str = "<?=$all_wins_str;?>";
var all_losses_str = "<?=$all_losses_str;?>";
</script>
<script src="js/local.js"></script>
</body>
</html>
<?php endif; ?>