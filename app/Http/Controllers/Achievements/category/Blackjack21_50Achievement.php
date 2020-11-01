<?php namespace App;

class Blackjack21_50Achievement extends Achievement {

    public function id(): int {
        return 10;
    }

    public function name(): string {
        return 'Шулер';
    }

    public function description(): string {
        return 'Соберите 21 очко в Blackjack 50 раз';
    }

    public function category(): string {
        return 'blackjack';
    }

    public function badge(): string {
        return 'gold';
    }

    public function progress(): int {
        return 50;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}