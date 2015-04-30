<?php
session_start();
require '../../vendor/autoload.php';
require_once '../helpers/variables.php';
require_once '../helpers/functions.php';

$token = getAccessTokenFromSession();
$dids = $provider->getDids($token);

?>
<html>
<head>
    <title>Dids</title>
</head>
<body>
    <h2>Dids</h2>
    <table>
        <thead>
            <tr style="text-align: center">
                <th>#</td>
                <th>Number</td>
                <th>State</td>
                <th>Destination Type</td>
                <th>Destination Id</td>
                <th>Destination Number</td>
                <th>Working Hour</td>
                <th></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dids as $did) { ?>
                <tr style="text-align: center">
                    <td><?= $did->id; ?></td>
                    <td><?= $did->number; ?></td>
                    <td><?= $did->state; ?></td>
                    <td><?= $did->destination_type; ?></td>
                    <td><?= $did->destination_id; ?></td>
                    <td><?= $did->destination_number; ?></td>
                    <td><?= $did->working_hour ? 'true' : 'false'; ?></td>
                    <td><a href="did.php?id=<?= $did->id; ?>">Detail</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>