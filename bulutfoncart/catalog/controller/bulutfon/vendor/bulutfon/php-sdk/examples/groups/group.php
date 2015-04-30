<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $group = $provider->getGroup($token, $id);

}else {
    echo "I don't know the ID";
    exit;
}
?>
<html>
<head>
    <title>Group - <?=$_GET['id']?></title>
</head>
<body>
    <h2><?= $group-> number; ?></h2>
    <ul>
        <li>ID: <?= $group->id; ?></li>
        <li>Number: <?= $group->number; ?></li>
        <li>Name: <?= $group->name; ?></li>
        <li>Timeout: <?= $group->timeout; ?></li>
        <li>Extensions:
            <ul>
                <?php $extensions =  $group->extensions;
                    foreach($extensions as $extension) { ?>
                        <li><?= $extension->id . " - " . $extension->number . " - " . $extension->caller_name . " - " . $extension->email; ?></li>
                    <?php } ?>
            </ul>
        </li>
</body>
</html>