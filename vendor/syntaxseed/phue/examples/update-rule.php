<?php
/**
 * Example: Update test rule.
 *
 * Usage: HUE_HOST=127.0.0.1 HUE_USERNAME=1234567890 php update-rule.php
 */
require_once 'common.php';

$client = new \Phue\Client($hueHost, $hueUsername);

echo 'Updating test rule', "\n";

$sensors = $client->getSensors();
$sensor = $sensors[2];

$rules = $client->getRules();
$rule = $rules[5];

$x = new \Phue\Command\UpdateRule($rule);
$y1 = new \Phue\Condition();
$y2 = new \Phue\Command\SetGroupState(0);
$z = $x->name('New name')
    ->addCondition(
    $y1->setSensorId($sensor)
        ->setAttribute('lastupdated')
        ->changed())
    ->addAction($y2->brightness(200));

$client->sendCommand($z);
