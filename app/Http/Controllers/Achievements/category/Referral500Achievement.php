<?php namespace App;

class Referral500Achievement extends Achievement {

    public function id(): int {
        return 13;
    }

    public function name(): string {
        return 'Душа компании';
    }

    public function description(): string {
        return 'Пригласите 500 активных рефералов';
    }

    public function category(): string {
        return 'user';
    }

    public function badge(): string {
        return 'platinum';
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