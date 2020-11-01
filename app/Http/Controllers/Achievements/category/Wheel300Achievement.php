<?php namespace App;

class Wheel300Achievement extends Achievement {

    public function id(): int {
        return 40;
    }

    public function name(): string {
        return 'Wheel';
    }

    public function description(): string {
        return 'Выиграйте в Wheel 300 раз';
    }

    public function category(): string {
        return 'wheel';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 300;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}