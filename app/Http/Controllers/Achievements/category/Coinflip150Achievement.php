<?php namespace App;

class Coinflip150Achievement extends Achievement {

    public function id(): int {
        return 37;
    }

    public function name(): string {
        return 'Coinflip';
    }

    public function description(): string {
        return 'Выиграйте в Coinflip 150 раз';
    }

    public function category(): string {
        return 'coinflip';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 150;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}