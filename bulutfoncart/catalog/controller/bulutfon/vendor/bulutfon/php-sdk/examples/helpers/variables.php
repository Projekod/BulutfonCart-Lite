<?php
$provider = new \Bulutfon\OAuth2\Client\Provider\Bulutfon([
    'clientId'      => '8fb6916bb8b9309b0c83827945d37aeba737ab5c740e320e60a483c73539d0d2',
    'clientSecret'  => '2f5860dd78f87fd773d9a8f95c3018530cd56497a2aaf8ac69f248e68fc6387f',
    'redirectUri'   => 'http://example.com/php-sdk/examples/callback.php',
    'scopes'        => ['cdr'],
]);
