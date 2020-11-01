<?php namespace App;

class Roulette50Achievement extends Achievement {

    public function id(): int {
        return 24;
    }

    public function name(): string {
        return 'Крупье';
    }

    public function description(): string {
        return 'Сыграйте в Roulette 50 раз';
    }

    public function category(): string {
        return 'roulette';
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