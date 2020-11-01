<?php namespace App;

class Freebie15Achievement extends Achievement {

    public function id(): int {
        return 18;
    }

    public function name(): string {
        return 'Халявщик';
    }

    public function description(): string {
        return 'Прокрутите колесо с бонусом 15 раз';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'bronze';
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