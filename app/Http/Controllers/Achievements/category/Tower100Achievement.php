<?php namespace App;

class Tower100Achievement extends Achievement {

    public function id(): int {
        return 21;
    }

    public function name(): string {
        return 'На высоте';
    }

    public function description(): string {
        return 'Сыграйте в Tower 100 раз';
    }

    public function category(): string {
        return 'tower';
    }

    public function badge(): string {
        return 'bronze';
    }

    public function progress(): int {
        return 100;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}