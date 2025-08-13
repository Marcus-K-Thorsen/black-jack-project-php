<?php

namespace App\Model;

require_once __DIR__ . '/SuitEnum.php';
require_once __DIR__ . '/RankEnum.php';

use App\Model\Suit;
use App\Model\Rank;


class Card {
    public string $suit;
    public string $name;
    public string $abreviation;
    public int $value;

    public function __construct(Suit $suit, Rank $rank) {
        $this->suit = $suit->value;
        $this->name = $rank->name;
        $this->abreviation = $rank->abbreviation();
        $this->value = $rank->value();
    }

    public function asString(): string {
        return "$this->abreviation of $this->suit";
    }

}
