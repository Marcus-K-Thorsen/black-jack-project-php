<?php

namespace App\Model;

enum Rank {
    case ACE;
    case TWO;
    case THREE;
    case FOUR;
    case FIVE;
    case SIX;
    case SEVEN;
    case EIGHT;
    case NINE;
    case TEN;
    case JACK;
    case QUEEN;
    case KING;

    public function value(): int {
        return match($this) {
            self::ACE => 11,
            self::TWO => 2,
            self::THREE => 3,
            self::FOUR => 4,
            self::FIVE => 5,
            self::SIX => 6,
            self::SEVEN => 7,
            self::EIGHT => 8,
            self::NINE => 9,
            self::TEN, self::JACK, self::QUEEN, self::KING => 10,
        };
    }

    public function abbreviation(): string {
        return match($this) {
            self::ACE => 'A',
            self::TWO => '2',
            self::THREE => '3',
            self::FOUR => '4',
            self::FIVE => '5',
            self::SIX => '6',
            self::SEVEN => '7',
            self::EIGHT => '8',
            self::NINE => '9',
            self::TEN => '10',
            self::JACK => 'J',
            self::QUEEN => 'Q',
            self::KING => 'K',
        };
    }

    public static function getAllRanks(): array {
        return self::cases();
    }
}
