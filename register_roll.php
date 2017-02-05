<?php
/**
 * J. Mulle, for rollup, 2/5/17 12:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
include_once("common.php");
$roller_id = isset($rollerId) ? $rollerId : $_POST['roller_id'];
$history = $db->query("SELECT * FROM `rolls` WHERE `roller_id` = $roller_id AND `year` = $current_year")->fetch_all();
?>
<div class="row">
	<h4>Let's Keep That Fail Score Up To Date!</h4>
	<div class="small-6 columns">
		<a href="#" class="small button expand register-roll" data-action="add" data-outcome=1 data-roller-id=<?=$roller_id;?>>Record a Win</a>
	</div>
	<div class="small-6 columns">
		<a href="#" class="small button expand register-roll" data-action="add" data-outcome=0 data-roller-id=<?=$roller_id;?>>Record a Fail</a>
	</div>
</div>
<div class="row">
	<div class="small-12 columns">
		<table style="width:100%" class="text-center" >
			<thead>
				<th>Result</th>
				<th>Date</th>
				<th></th>
			</thead>
			<tbody>
			<?php foreach($history as $r):?>
				<tr class="no-pad">
					<td><?=$r[2] ? "Won" : "Lost"?></td>
					<td><?=$r[4]?></td>
					<td class="no-pad">
						<a href="#" id="roll_<?=$r[0];?>" class="tiny button register-roll" data-action="remove" data-roller-id=<?=$roller_id;?> data-roll-id="<?=$r[0];?>" data-outcome=<?=$r[2] ? 0 : 1;?>>Remove</a>
						<a href="#" id="roll_<?=$r[0];?>" class="tiny button register-roll" data-action="toggle" data-roller-id=<?=$roller_id;?> data-roll-id="<?=$r[0];?>" data-outcome=<?=$r[2] ? 0 : 1;?>>Toggle Result</a>
					</td>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<a class="close-reveal-modal" aria-label="Close">&#215;</a>
