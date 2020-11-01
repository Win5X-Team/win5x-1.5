<?php namespace App;

class Stairs50Achievement extends Achievement {

    public function id(): int {
        return 31;
    }

    public function name(): string {
        return 'Без помех';
    }

    public function description(): string {
        return 'Доберитесь до конца Stairs 50 раз';
    }

    public function category(): string {
        return 'stairs';
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