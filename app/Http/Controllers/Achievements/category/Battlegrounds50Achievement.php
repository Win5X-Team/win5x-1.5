<?php namespace App;

class Battlegrounds50Achievement extends Achievement {

    public function id(): int {
        return 29;
    }

    public function name(): string {
        return 'Предсказатель';
    }

    public function description(): string {
        return 'Выиграйте 50 игр в Battlegrounds';
    }

    public function category(): string {
        return 'battlegrounds';
    }

    public function badge(): string {
        return 'gold';
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