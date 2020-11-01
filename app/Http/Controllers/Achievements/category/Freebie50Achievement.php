<?php namespace App;

class Freebie50Achievement extends Achievement {

    public function id(): int {
        return 19;
    }

    public function name(): string {
        return 'Халявщик';
    }

    public function description(): string {
        return 'Активируйте промокод 50 раз';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'silver';
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