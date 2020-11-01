<?php namespace App;

class Battlegrounds15Achievement extends Achievement {

    public function id(): int {
        return 28;
    }

    public function name(): string {
        return 'Предсказатель';
    }

    public function description(): string {
        return 'Выиграйте 15 игр в Battlegrounds';
    }

    public function category(): string {
        return 'battlegrounds';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 15;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}