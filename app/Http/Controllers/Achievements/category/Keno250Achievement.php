<?php namespace App;

class Keno250Achievement extends Achievement {

    public function id(): int {
        return 40;
    }

    public function name(): string {
        return 'Keno';
    }

    public function description(): string {
        return 'Выиграйте в Keno 250 раз';
    }

    public function category(): string {
        return 'keno';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 250;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}