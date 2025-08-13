<?php

namespace App\Model;

require_once __DIR__ . '/CardModel.php';
require_once __DIR__ . '/SuitEnum.php';
require_once __DIR__ . '/RankEnum.php';

use App\Model\Card;
use App\Model\Suit;
use App\Model\Rank;

class Deck {
    /**
     * @var Card[]
     */
    private array $cards;

    /**
     * @var Card[]
     */
    private array $discardedCards;

    public function __construct() {
        $this->cards = [];
        $this->discardedCards = [];
        $this->initializeDeck();
    }

    private function initializeDeck(): void {
        $suits = Suit::getAllSuits();
        $ranks = Rank::getAllRanks();
        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                // Create a card for each suit and rank combination
                $card = new Card($suit, $rank);
                $this->cards[] = $card;
            }
        }
        // Shuffle the deck initially
        shuffle($this->cards);
    }

    public function shuffle(): void {
        if (count($this->discardedCards) > 0) {
            array_push($this->cards, ...$this->discardedCards);
        }
        $this->discardedCards = [];
        shuffle($this->cards);
    }

    public function drawCard(): Card {
        $drawnCard = array_pop($this->cards);
        if ($drawnCard === null) {
            $this->shuffle();
            return $this->drawCard();
        }
        $this->discardedCards[] = $drawnCard;
        return $drawnCard;
    }
}
