<?php namespace App;

class Plinko25Achievement extends Achievement {

    public function id(): int {
        return 36;
    }

    public function name(): string {
        return 'Plinko';
    }

    public function description(): string {
        return 'Выиграйте в Plinko 25 раз';
    }

    public function category(): string {
        return 'plinko';
    }

    public function badge(): string {
        return 'bronze';
    }

    public function progress(): int {
        return 25;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}