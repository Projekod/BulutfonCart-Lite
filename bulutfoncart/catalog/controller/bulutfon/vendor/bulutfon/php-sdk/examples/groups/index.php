<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();
$groups = $provider->getGroups($token);

?>
<html>
<head>
    <title>Groups</title>
</head>
<body>
    <h2>Groups</h2>
    <table>
        <thead>
            <tr style="text-align: center">
                <th>#</td>
                <th>Number</td>
                <th>Name</td>
                <th>Timeout</td>
                <th></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($groups as $group) { ?>
                <tr style="text-align: center">
                    <td><?= $group->id; ?></td>
                    <td><?= $group->number; ?></td>
                    <td><?= $group->name; ?></td>
                    <td><?= $group->timeout; ?></td>
                    <td><a href="group.php?id=<?= $group->id; ?>">Detail</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>