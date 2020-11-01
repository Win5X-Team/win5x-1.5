<?php namespace App;

class Level5Achievement extends Achievement {

    public function id(): int {
        return 15;
    }

    public function name(): string {
        return 'Завсегдатай';
    }

    public function description(): string {
        return 'Достигните 5 уровня';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'silver';
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