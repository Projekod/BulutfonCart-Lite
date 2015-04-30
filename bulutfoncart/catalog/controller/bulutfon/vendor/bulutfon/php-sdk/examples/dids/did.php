<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $did = $provider->getDid($token, $id);

}else {
    echo "I don't know the ID";
    exit;
}
?>
<html>
<head>
    <title>Did - <?=$_GET['id']?></title>
</head>
<body>
    <h2><?= $did-> number; ?></h2>
    <ul>
        <li>ID: <?= $did->id; ?></li>
        <li>Number: <?= $did->number; ?></li>
        <li>State: <?= $did->state; ?></li>
        <li>Destination Type: <?= $did->destination_type; ?></li>
        <li>Destination Id: <?= $did->destination_id; ?></li>
        <li>Destination Number: <?= $did->destination_number; ?></li>
        <li>Working Hour: <?= $did->working_hour ? 'true' : 'false'; ?></li>
        <?php if($did->working_hour) { ?>
            <?php $working_hours = $did->working_hours; ?>
            <li>
                Working Hours:
                <ul>
                    <li>Monday: <?= ($working_hours->monday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->monday, "lunch_break_start") ? $working_hours->monday->shift_start." - ". $working_hours->monday->lunch_break_start." / ".$working_hours->monday->lunch_break_finish." - ". $working_hours->monday->shift_finish : $working_hours->monday->shift_start." - ". $working_hours->monday->shift_finish)?></li>
                    <li>Tuesday: <?= ($working_hours->tuesday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->tuesday, "lunch_break_start") ? $working_hours->tuesday->shift_start." - ". $working_hours->tuesday->lunch_break_start." / ".$working_hours->tuesday->lunch_break_finish." - ". $working_hours->tuesday->shift_finish : $working_hours->tuesday->shift_start." - ". $working_hours->tuesday->shift_finish)?></li>
                    <li>Wednesday: <?= ($working_hours->wednesday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->wednesday, "lunch_break_start") ? $working_hours->wednesday->shift_start." - ". $working_hours->wednesday->lunch_break_start." / ".$working_hours->wednesday->lunch_break_finish." - ". $working_hours->wednesday->shift_finish : $working_hours->wednesday->shift_start." - ". $working_hours->wednesday->shift_finish)?></li>
                    <li>Thursday: <?= ($working_hours->thursday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->thursday, "lunch_break_start") ? $working_hours->thursday->shift_start." - ". $working_hours->thursday->lunch_break_start." / ".$working_hours->thursday->lunch_break_finish." - ". $working_hours->thursday->shift_finish : $working_hours->thursday->shift_start." - ". $working_hours->thursday->shift_finish)?></li>
                    <li>Friday: <?= ($working_hours->friday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->friday, "lunch_break_start") ? $working_hours->friday->shift_start." - ". $working_hours->friday->lunch_break_start." / ".$working_hours->friday->lunch_break_finish." - ". $working_hours->friday->shift_finish : $working_hours->friday->shift_start." - ". $working_hours->friday->shift_finish)?></li>
                    <li>Saturday: <?= ($working_hours->saturday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->saturday, "lunch_break_start") ? $working_hours->saturday->shift_start." - ". $working_hours->saturday->lunch_break_start." / ".$working_hours->saturday->lunch_break_finish." - ". $working_hours->saturday->shift_finish : $working_hours->saturday->shift_start." - ". $working_hours->saturday->shift_finish)?></li>
                    <li>Sunday: <?= ($working_hours->sunday->open ? 'Open' : 'Closed') . " / " . (property_exists($working_hours->sunday, "lunch_break_start") ? $working_hours->sunday->shift_start." - ". $working_hours->sunday->lunch_break_start." / ".$working_hours->sunday->lunch_break_finish." - ". $working_hours->sunday->shift_finish : $working_hours->sunday->shift_start." - ". $working_hours->sunday->shift_finish)?></li>
                </ul>
            </li>
        <?php } ?>
    </ul>
</body>
</html>