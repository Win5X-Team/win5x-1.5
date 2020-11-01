<?php namespace App;

class HiLo25Achievement extends Achievement {

    public function id(): int {
        return 42;
    }

    public function name(): string {
        return 'HiLo';
    }

    public function description(): string {
        return 'Выиграйте в HiLo 25 раз';
    }

    public function category(): string {
        return 'hilo';
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