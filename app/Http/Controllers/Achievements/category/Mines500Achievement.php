<?php namespace App;

class Mines500Achievement extends Achievement {

    public function id(): int {
        return 6;
    }

    public function name(): string {
        return 'Сапер';
    }

    public function description(): string {
        return 'Откройте 500 алмазов';
    }

    public function category(): string {
        return 'mines';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 500;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}