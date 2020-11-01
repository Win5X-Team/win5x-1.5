<?php namespace App;

class Crash50Achievement extends Achievement {

    public function id(): int {
        return 45;
    }

    public function name(): string {
        return 'Crash';
    }

    public function description(): string {
        return 'Выиграйте в Crash 50 раз';
    }

    public function category(): string {
        return 'crash';
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