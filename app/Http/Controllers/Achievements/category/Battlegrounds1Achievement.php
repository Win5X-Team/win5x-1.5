<?php namespace App;

class Battlegrounds1Achievement extends Achievement {

    public function id(): int {
        return 27;
    }

    public function name(): string {
        return 'Предсказатель';
    }

    public function description(): string {
        return 'Выиграйте игру в Battlegrounds';
    }

    public function category(): string {
        return 'battlegrounds';
    }

    public function badge(): string {
        return 'bronze';
    }

    public function progress(): int {
        return 1;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}