<?php
function is_going($reservations, $date, $hour) {
    if (array_key_exists($date, $reservations)) {
        $hours = $reservations[$date];
        if (array_key_exists($hour, $hours)) {
            return $hours[$hour] ? "yes" : "no";
        }
    }
    return "maybe";
}

function display_square($reservations, $date, $hour, $input_field) {
    $input_name = "h_{$date}_{$hour}";
    $is_going = is_going($reservations, $date, $hour);
    if ($is_going === "yes") {
        $td_class = "going";
        $icon = "icon-ok-sign";
        $value = 1;
    }
    else if ($is_going === "no") {
        $td_class = "not-going";
        $icon = "icon-remove-sign";
        $value = 0;   
    }
    else {
        $td_class = "not-sure";
        $icon = "";
        $value = -1;      
    }
    return "<td class=\"changeable {$td_class}\">" .
        "<i class=\"$icon\"></i>" .
        ($input_field ? "<input type=\"hidden\" name=\"$input_name\" value=\"$value\" />" : "") .
        "</td>";
}

$event = Event::find($_GET["id"]);
if (!$event) {
    die("Cet événement n'existe pas.");
}

if (!$event->userIsInvited($user->id)) {
    die("Vous n'êtes pas invité à cet événement.");
}



$event_dates = $event->dates();
$invitees = array_diff($event->getInvitees(), array($user->id));
$my_reservations = $event->getReservationsFor($user->id);
$others_reservations = array();
foreach ($invitees as $invitee) {
    list($invitee_id, $invitee_name) = $invitee;
    if ($invitee_id == $user->id) {
        continue;
    }
    $others_reservations[$invitee_name] = $event->getReservationsFor($invitee_id);
}
$c = count($event_dates);
?>


<div class="container">
	<div class="row">
        <h1><?php echo $event->name ?></h1>
        <form id="new_event_form" class="form-horizontal" method="post" action="">
	        <legend>Mes disponibilités</legend>

            <table id="my-availabilities" border="1">
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
                            <?php echo display_square($my_reservations, $event_dates[$i]["date"], $h, TRUE) ?>
                            <?php else: ?>
                            <td class="no-reservation">&nbsp;</td>
                            <?php endif ?>
                        <?php endfor ?>
                    </tr>
                    <?php endfor ?>
                </tr>
            </table>
            <input type="submit" class="btn" value="Enregistrer" />
        </form>

        <h1>Autres invités</h1>
        <?php foreach ($others_reservations as $invitee_name => $reservations): ?>
        <?php if ($event->type === 'public'): ?>
        <legend><?php echo $invitee_name ?></legend>
        <?php endif ?>

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
                        <?php echo display_square($reservations, $event_dates[$i]["date"], $h, FALSE) ?>
                        <?php else: ?>
                        <td class="no-reservation">&nbsp;</td>
                        <?php endif ?>
                    <?php endfor ?>
                </tr>
                <?php endfor ?>
            </tr>
        </table>
        <?php endforeach ?>
    </div>
</div>

<script>
$(function () {
    var properties = {
        "0": ["not-sure", "", "-1"],
        "1": ["not-going", "icon-remove-sign", "0"],
        "-1": ["going", "icon-ok-sign", "1"],
    };
    $("#my-availabilities .changeable").click(function () {
        var td = $(this);
        var currentClass = td.prop("class").split(" ")[1]; // Yuck.
        var icon = td.find("i");
        var currentIcon = icon.prop("class");
        var hidden = td.find("input");
        var currentValue = hidden.prop("value");

        var nextProperties = properties[currentValue];
        td.removeClass(currentClass);
        icon.removeClass(currentIcon);
        td.addClass(nextProperties[0]);
        icon.addClass(nextProperties[1]);
        hidden.prop("value", nextProperties[2]);
    });
});
</script>
