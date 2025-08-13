<?php

namespace App\Model;

enum Suit: string {
    case HEARTS = 'hearts';
    case DIAMONDS = 'diamonds';
    case CLUBS = 'clubs';
    case SPADES = 'spades';

    /**
     * @return Suit[]
     */
    public static function getAllSuits(): array {
        return self::cases();
    }
}
