<?php
/**
 * Example: Schedule a specific groups state.
 *
 * Usage: HUE_HOST=127.0.0.1 HUE_USERNAME=1234567890 php schedule-group-state.php
 */
require_once 'common.php';

$client = new \Phue\Client($hueHost, $hueUsername);

echo 'Scheduling group 1 state.', "\n";

$x = new \Phue\Command\CreateSchedule();
$y = new \Phue\Command\SetGroupState(1);
$z = $x->name('Group 1 dimmer')
    ->description('Dims the lights for group 1')
    ->time('+10 seconds')
    ->command($y->brightness(255))
    ->status(\Phue\Schedule::STATUS_ENABLED)
    ->autodelete(true);
$client->sendCommand($z);

echo 'Done.', "\n";
