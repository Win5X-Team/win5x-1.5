<?php namespace App;

class Blackjack50Achievement extends Achievement {

    public function id(): int {
        return 8;
    }

    public function name(): string {
        return 'Шулер';
    }

    public function description(): string {
        return 'Сыграйте в Blackjack 50 раз';
    }

    public function category(): string {
        return 'blackjack';
    }

    public function badge(): string {
        return 'bronze';
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