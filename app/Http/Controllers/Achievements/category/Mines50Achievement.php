<?php namespace App;

class Mines50Achievement extends Achievement {

    public function id(): int {
        return 5;
    }

    public function name(): string {
        return 'Сапер';
    }

    public function description(): string {
        return 'Откройте 50 алмазов';
    }

    public function category(): string {
        return 'mines';
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