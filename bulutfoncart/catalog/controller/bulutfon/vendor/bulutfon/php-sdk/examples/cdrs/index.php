<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$filters = []; // ['caller' => 'xxx', 'callee'=> 'yyy', 'time_limit' => 'day'] etc.
$cdrObj = $provider->getCdrs($token, $filters, $page);
$cdrs = $cdrObj->cdrs;
?>
<html>
<head>
    <title>Cdrs</title>
</head>
<body>
    <h2>Cdrs</h2>
    <table>
        <thead>
            <tr style="text-align: center">
                <th>#</td>
                <th>Call Type</td>
                <th>Direction</td>
                <th>Caller</td>
                <th>Callee</td>
                <th>Call Time</td>
                <th>Answer Time</td>
                <th>Hangup Time</td>
                <th>Call Record</td>
                <th>Hangup Cause</td>
                <th>Hangup State</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($cdrs as $cdr) { ?>
                <tr style="text-align: center">
                    <td><?= $cdr->bf_calltype; ?></td>
                    <td><?= $cdr->direction; ?></td>
                    <td><?= $cdr->caller; ?></td>
                    <td><?= $cdr->callee; ?></td>
                    <td><?= $cdr->call_time; ?></td>
                    <td><?= $cdr->answer_time; ?></td>
                    <td><?= $cdr->hangup_time; ?></td>
                    <td><?= $cdr->call_record; ?></td>
                    <td><?= $cdr->hangup_cause; ?></td>
                    <td><?= $cdr->hangup_state; ?></td>
                    <td><a href="cdr.php?id=<?= $cdr->uuid; ?>">Detail</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php if($cdrObj->previous_page) {?>
        <a href="?page=<?= --$page ?>">Previous Page</a>
    <?php } ?>
    <?php if($cdrObj->next_page) {?>
        <a href="?page=<?= ++$page ?>">Next Page</a>
    <?php } ?>
</body>
</html>