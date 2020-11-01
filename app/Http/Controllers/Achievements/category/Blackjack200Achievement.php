<?php namespace App;

class Blackjack200Achievement extends Achievement {

    public function id(): int {
        return 9;
    }

    public function name(): string {
        return 'Шулер';
    }

    public function description(): string {
        return 'Выиграйте крупье в Blackjack 200 раз';
    }

    public function category(): string {
        return 'blackjack';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 200;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}