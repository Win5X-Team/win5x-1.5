<?php namespace App;

class HiLo500Achievement extends Achievement {

    public function id(): int {
        return 44;
    }

    public function name(): string {
        return 'HiLo';
    }

    public function description(): string {
        return 'Выиграйте в HiLo 500 раз';
    }

    public function category(): string {
        return 'hilo';
    }

    public function badge(): string {
        return 'gold';
    }

    public function progress(): int {
        return 500;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}