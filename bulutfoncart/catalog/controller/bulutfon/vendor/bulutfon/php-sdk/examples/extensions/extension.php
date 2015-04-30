<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $extension = $provider->getExtension($token, $id);

}else {
    echo "I don't know the ID";
    exit;
}
?>
<html>
<head>
    <title>Extension - <?=$_GET['id']?></title>
</head>
<body>
    <h2><?= $extension-> number; ?></h2>
    <ul>
        <li>ID: <?= $extension->id; ?></li>
        <li>Number: <?= $extension->number; ?></li>
        <li>Registered: <?= $extension->registered; ?></li>
        <li>Caller Name: <?= $extension->caller_name; ?></li>
        <li>Email: <?= $extension->email; ?></li>
        <li>Did: <?= $extension->did; ?></li>
        <li>ACL: <?= join(', ', $extension->acl); ?></li>
</body>
</html>