/**
 * Created by jono on 2/5/17.
 */
$("a.slide-anchor").click(function() {
	var id = $(this).attr('href')
	var anchor = id.substring(1);
	$("ul.right li").removeClass('active');
	$(this).parent().addClass('active');
	scrollToAnchor(anchor);
});

function scrollToAnchor(aid) {
	var aTag = $("a[name='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
}

function coffeeCounter(inc, outcome, id) {
	console.log([inc, outcome, id]);
	var outcome_str = outcome ? "won" : "lost";
	var count = Number( $("#" + id + "_" + outcome_str).html() ) + Number(inc);
	$("#" + id + "_" + outcome_str).html(count);
	var wins 	= Number($("#" + id + "_won").html());
	var losses	= Number($("#" + id + "_lost").html());
	var total = wins + losses;
	var ratStr;
	if (losses == 0 && wins > 0) {
		ratStr = all_wins_str;
	} else if (wins == 0 && losses > 0) {
		ratStr = all_losses_str;
	} else if (wins == 0 && losses == 0) {
		ratStr = "No has data";
	} else {
		var ratio = String( parseInt(wins) / (parseInt(wins) + parseInt(losses)) * 100 );
		ratStr	= ratio.substring(0,5) + "%";
	}
	$("#" + id + "_ratio").html(ratStr);
	$("#" + id + "_total").html(total);


	// updates 'cumulative despair' pane
	var total_wins = 0;
	var total_losses = 0;
	var total_rolled;
	var total_ratio;
	$(".current-wins").each(function() { total_wins += Number($(this).html()) });
	$(".current-losses").each(function() { total_losses += Number($(this).html()); });
	total_rolled = total_wins + total_losses;
	total_ratio = String(total_losses / total_rolled * 100);
	var fail_differential =  Number(total_ratio) - (5/6 * 100);
	var fail_performance = Math.abs(fail_differential) / (1/6 * 100) * 100;
	var fail_rate = String(total_losses * 6 / total_rolled).substr(0, 5);

	if (total_ratio.length > 5)  total_ratio = total_ratio.substr(0, 5);
	var add_class = fail_differential > 0 ? "red" : "green";
	var remove_class = fail_differential > 0 ? "green" : "red";
	var descriptor = fail_differential > 0 ? "Over-failing" : "Under-failing";


	$("#fail-performance-descriptor").addClass(add_class).removeClass(remove_class).html(descriptor)
	$("#fail-performance").html(String(fail_performance).substr(0, 4) + "%");

	// only for the first roll of the season
	if (total_rolled == 1) {
		$("#no-rolls-rate").replaceWith('<p id="roll-rate">5 in 6 rolls should fail; we are failing at a rate of <span id="failrate"></span> in 6</p>');
	}
	$("#failrate").html(fail_rate);

	$("#cumulative-results").html(total_ratio + "% Fail");
	$("#net-win").html(total_wins);
	$("#net-fail").html(total_losses);
	$("#net-rolled").html(total_rolled);
};

$("#add-new-roller").click( function() {
	var data = {roller: $("#new-roller").val(), year: current_year};
	console.log(data);
	$.ajax("register.php", {
		type:"GET",
		data: data,
		success: function() {
			$('#register-roller-modal').foundation('reveal', 'close');
			$("#new-roller").val(null);
		}
	});
});

$("#compute-metafail").click( function(){
	var fails = $("#consecutive-losses").val();
	if (fails == 0) {
		$("#metafail-error").show();
	} else {
		$("#metafail-form").hide();
		$("#metafail-rate").html(String(Math.pow(5/6, fails) * 100).substring(0,5) + "%");
		$("#metafail-result").show();
	}
});

$("#moar-metafail").click(function() {
	$("#consecutive-losses").val('');
	$("#metafail-form").show()
	$("#metafail-result").hide();

});

$(".roll-registry").click(function() {
	var data = {roller_id: $(this).data("roller-id")};
	$('#record-roll-modal').load("register_roll.php", data);
	$('#record-roll-modal').foundation('reveal', 'open');
});

$("body").on("click", ".register-roll", function() {
	var d = $(this).data();
	$('#record-roll-modal').load("increment.php", d);
	pr(d);
	switch (d.action) {
		case "remove":
			coffeeCounter(-1, d.outcome, d.rollerId);
			break;
		case "toggle":
			coffeeCounter(-1, d.outcome, d.rollerId);
			coffeeCounter(1, d.outcome ? 0 : 1, d.rollerId);
			break;
		case "add":
			coffeeCounter(1, d.outcome, d.rollerId);
			break;
	}
});
