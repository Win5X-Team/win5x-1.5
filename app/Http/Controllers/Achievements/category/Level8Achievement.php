<?php namespace App;

class Level8Achievement extends Achievement {

    public function id(): int {
        return 16;
    }

    public function name(): string {
        return 'Завсегдатай';
    }

    public function description(): string {
        return 'Достигните 8 уровня';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'gold';
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