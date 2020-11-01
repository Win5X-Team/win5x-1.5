<?php namespace App;

class Game5000Achievement extends Achievement {

    public function id(): int {
        return 3;
    }

    public function name(): string {
        return '5000 игр';
    }

    public function description(): string {
        return 'Сыграйте 5000 игр';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'gold';
    }

    public function progress(): int {
        return 5000;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}