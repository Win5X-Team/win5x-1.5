<?php namespace App;

class Game1Achievement extends Achievement {

    public function id(): int {
        return 1;
    }

    public function name(): string {
        return 'Первая игра';
    }

    public function description(): string {
        return 'Сыграйте свою первую игру';
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