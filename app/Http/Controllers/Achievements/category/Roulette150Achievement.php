<?php namespace App;

class Roulette150Achievement extends Achievement {

    public function id(): int {
        return 25;
    }

    public function name(): string {
        return 'Крупье';
    }

    public function description(): string {
        return 'Выиграйте коэффициент x3 в Roulette 150 раз';
    }

    public function category(): string {
        return 'roulette';
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