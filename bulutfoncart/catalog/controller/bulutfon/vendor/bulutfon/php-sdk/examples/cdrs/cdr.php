<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $cdr = $provider->getCdr($token, $id);

}else {
    echo "I don't know the ID";
    exit;
}
?>
<html>
<head>
    <title>Cdr - <?=$_GET['id']?></title>
</head>
<body>
    <h2><?= $cdr->uuid; ?></h2>
    <ul>
        <li>ID: <?= $cdr->uuid; ?></li>
        <li>Call Type: <?= $cdr->bf_calltype; ?></li>
        <li>Direction: <?= $cdr->direction; ?></li>
        <li>Caller: <?= $cdr->caller; ?></li>
        <li>Callee: <?= $cdr->callee; ?></li>
        <li>Extension: <?= $cdr->extension; ?></li>
        <li>Call Time: <?= $cdr->call_time; ?></li>
        <li>Answer Time: <?= $cdr->answer_time; ?></li>
        <li>Hangup Time: <?= $cdr->hangup_time; ?></li>
        <li>Call Price: <?= $cdr->call_price; ?></li>
        <li>Call Record: <?= $cdr->call_record; ?></li>
        <li>Hangup Cause: <?= $cdr->hangup_cause; ?></li>
        <li>Hangup State: <?= $cdr->hangup_state; ?></li>
        <?php if($cdr->call_flow) { ?>
            <li>Call Flow:
                <ul>
                    <?php foreach($cdr->call_flow as $callflow) {?>
                        <li><?= $callflow->callee; ?>
                            <ul>
                                <li><?= $callflow->start_time; ?></li>
                                <li><?= $callflow->answer_time; ?></li>
                                <li><?= $callflow->hangup_time; ?></li>
                                <li><?= $callflow->redirection; ?></li>
                                <li><?= $callflow->redirection_target; ?></li>
                                <?php if($callflow->origination) { ?>
                                    <li>Origination:
                                        <ul>
                                            <?php foreach($callflow->origination as $origination) {?>
                                                <li><?= $origination->destination ?>
                                                    <ul>
                                                        <li><?= $origination->start_time ?></li>
                                                        <li><?= $origination->answer_time ?></li>
                                                        <li><?= $origination->hangup_time ?></li>
                                                        <li><?= $origination->result ?></li>
                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

    </ul>
</body>
</html>