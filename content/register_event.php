<?php
function availability_class($reservations, $date, $hour) {
    if (array_key_exists($date, $reservations)) {
        $hours = $reservations[$date];
        if (array_key_exists($hour, $hours)) {
            return $hours[$hour] ? "going" : "not-going";
        }
    }
    return "not-sure";
}

$all_users = json_encode(User::find_all_names());
$event = Event::find($_GET["id"]);
if (!$event) {
    die("404");
}



$event_dates = $event->dates();
$reservations = $event->getReservationsFor($user->id);
$c = count($event_dates);
?>


<div class="container">
    <h1><?php echo $event->name ?></h1>
	<div class="row">
        <form id="new_event_form" class="form-horizontal" action="">

	        <legend>Mes disponibilités</legend>

            <table border="1">
                <tr>
                    <th>&nbsp;</th>
                    <?php for ($h = 0; $h < 24; $h++): ?>
                    <th>
                        <?php printf("%02dh00", $h) ?>
                    </th>
                    <?php endfor ?>

                    <?php for ($i = 0; $i < $c; $i++): ?>
                    <tr>
                        <th><?php echo $event_dates[$i]["date"] ?></th>
                        <?php for ($h = 0; $h < 24; $h++): ?>
                            <?php if ($h >= $event_dates[$i]["start_hour"] && $h < $event_dates[$i]["end_hour"]): ?>
                            <td class="changeable <?php echo availability_class($reservations, $event_dates[$i]["date"], $h) ?>">&nbsp;</td>
                            <?php else: ?>
                            <td class="no-reservation">&nbsp;</td>
                            <?php endif ?>
                        <?php endfor ?>
                    </tr>
                    <?php endfor ?>
                </tr>
            </table>

        </form>
</div>

<script>
$(function () {
    var classes = ["going", "not-going", "not-sure"];
    $(".changeable").click(function () {
        var that = $(this);
        var currentClass = that.prop("class").split(" ")[1]; // Yuck.
        var nextClass = classes[(classes.indexOf(currentClass) + 1) % 3];
        that.removeClass(currentClass);
        that.addClass(nextClass);
    });
});
</script>