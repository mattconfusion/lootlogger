#!/usr/bin/php
<?php

/**
*  ╦  ┌─┐┌─┐┌┬┐  ╦  ┌─┐┌─┐┌─┐┌─┐┬─┐
*  ║  │ ││ │ │   ║  │ ││ ┬│ ┬├┤ ├┬┘
*  ╩═╝└─┘└─┘ ┴   ╩═╝└─┘└─┘└─┘└─┘┴└─
*/

require __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$llCli = new \LootLogger\LootLoggerCli();
$llCli->run();
