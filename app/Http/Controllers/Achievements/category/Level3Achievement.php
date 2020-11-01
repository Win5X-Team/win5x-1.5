<?php namespace App;

class Level3Achievement extends Achievement {

    public function id(): int {
        return 14;
    }

    public function name(): string {
        return 'Завсегдатай';
    }

    public function description(): string {
        return 'Достигните 3 уровня';
    }

    public function category(): string {
        return 'user';
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