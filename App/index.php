<?php
declare(strict_types=1);

require_once __DIR__ . '/View/PrintService.php';

use App\View\PrintService;

$printer = new PrintService();


$printer->printRedMessage("Starting PHP project.");

$name = $printer->requestTextInput("Please enter your name", true);

$printer->printBlueMessage("Hello $name!", true);

$playActions = ['play', 'P'];
$exitActions = ['exit', 'E'];
$actionGroups = [$playActions, $exitActions];
$actions = array_merge(...$actionGroups);


$isRequestingAction = true;

do {
    $action = $printer->requestActionInput("Please choose an action 'play' or 'exit'", $actionGroups);
    $isRequestingAction = false;

    if (in_array($action, $playActions, true)) {
        $printer->printGreenMessage("Starting a new game...", true);
    } elseif (in_array($action, $exitActions, true)) {
        $printer->printYellowMessage("Exiting the game...", true);
    } else {
        $printer->printRedMessage("Invalid action '$action' selected, allowed actions are: " . implode(", ", $actions), true);
        $isRequestingAction = true;
    }
} while ($isRequestingAction);

$number = $printer->requestNumberInput("Please enter a number between 1 and 10", 1, 10);

$printer->printCyanMessage("You entered the number: $number", true);

$printer->printMagentaMessage("Ending the PHP project.", true);
