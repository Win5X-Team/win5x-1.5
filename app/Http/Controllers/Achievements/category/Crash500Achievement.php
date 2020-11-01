<?php namespace App;

class Crash500Achievement extends Achievement {

    public function id(): int {
        return 47;
    }

    public function name(): string {
        return 'Crash';
    }

    public function description(): string {
        return 'Выиграйте в Crash 500 раз';
    }

    public function category(): string {
        return 'crash';
    }

    public function badge(): string {
        return 'gold';
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