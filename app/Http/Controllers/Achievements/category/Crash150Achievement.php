<?php namespace App;

class Crash150Achievement extends Achievement {

    public function id(): int {
        return 46;
    }

    public function name(): string {
        return 'Crash';
    }

    public function description(): string {
        return 'Выиграйте в Crash 150 раз';
    }

    public function category(): string {
        return 'crash';
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