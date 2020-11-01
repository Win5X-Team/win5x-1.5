<?php namespace App;

class Stairs200Achievement extends Achievement {

    public function id(): int {
        return 32;
    }

    public function name(): string {
        return 'Без помех';
    }

    public function description(): string {
        return 'Доберитесь до конца Stairs 200 раз';
    }

    public function category(): string {
        return 'stairs';
    }

    public function badge(): string {
        return 'gold';
    }

    public function progress(): int {
        return 200;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}