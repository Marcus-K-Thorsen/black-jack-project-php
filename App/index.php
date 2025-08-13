<?php
declare(strict_types=1);

require_once __DIR__ . '/View/PrintService.php';
require_once __DIR__ . '/Model/DeckModel.php';
require_once __DIR__ . '/Model/CardModel.php';

use App\View\PrintService;
use App\Model\Deck;
use App\View\Color;

$printer = new PrintService();
$deck = new Deck();

$printer->printMessage("Starting PHP project.", Color::GREEN);

$playActions = ['play', 'P'];
$shuffleActions = ['shuffle', 'S'];
$exitActions = ['exit', 'E'];
$actionGroups = [$playActions, $shuffleActions, $exitActions];
$actions = array_merge(...$actionGroups);


$isRequestingAction = true;

do {
    $action = $printer->requestActionInput("Please choose an action 'play', 'shuffle' or 'exit'", $actionGroups);
    
    if (in_array($action, $playActions, true)) {
        $printer->printMessage("Starting a new game...", lineBreak: true);
        $drawnCard = $deck->drawCard();
        $printer->printMessage("You drew a card: " . $drawnCard->asString(), Color::BLUE, true);
    } elseif (in_array($action, $shuffleActions, true)) {
        $deck->shuffle();
        $printer->printMessage("Shuffling the deck...", Color::GREEN, true);
    } elseif (in_array($action, $exitActions, true)) {
        $printer->printMessage("Exiting the game...", Color::YELLOW, true);
        $isRequestingAction = false;
    } else {
        $printer->printMessage("Invalid action '$action' selected, allowed actions are: " . implode(", ", $actions), Color::RED, true);
    }
} while ($isRequestingAction);
