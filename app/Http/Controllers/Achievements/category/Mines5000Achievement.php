<?php namespace App;

class Mines5000Achievement extends Achievement {

    public function id(): int {
        return 7;
    }

    public function name(): string {
        return 'Сапер';
    }

    public function description(): string {
        return 'Откройте 5000 алмазов';
    }

    public function category(): string {
        return 'mines';
    }

    public function badge(): string {
        return 'gold';
    }

    public function progress(): int {
        return 5000;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}