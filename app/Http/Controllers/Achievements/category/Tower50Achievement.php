<?php namespace App;

class Tower50Achievement extends Achievement {

    public function id(): int {
        return 22;
    }

    public function name(): string {
        return 'На высоте';
    }

    public function description(): string {
        return 'Доберитесь до конца Tower 50 раз';
    }

    public function category(): string {
        return 'tower';
    }

    public function badge(): string {
        return 'silver';
    }

    public function progress(): int {
        return 50;
    }

    public function reward() {
        return null;
    }

    public function whenAwarded() {
    }

}