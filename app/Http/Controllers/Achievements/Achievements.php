<?php namespace App;

class Achievements {

    public static $achievements = [
        // user
        Game1Achievement::class,
        Game1000Achievement::class,
        Game5000Achievement::class,
        Level3Achievement::class,
        Level5Achievement::class,
        Level8Achievement::class,
        Level10Achievement::class,
        Freebie15Achievement::class,
        Freebie50Achievement::class,
        Freebie100Achievement::class,
        Referral10Achievement::class,
        Referral100Achievement::class,
        Referral500Achievement::class,

        // battlegrounds
        Battlegrounds1Achievement::class,
        Battlegrounds15Achievement::class,
        Battlegrounds50Achievement::class,

        // mines
        Mines50Achievement::class,
        Mines500Achievement::class,
        Mines5000Achievement::class,

        // tower
        Tower100Achievement::class,
        Tower50Achievement::class,
        Tower200Achievement::class,

        // stairs
        Stairs100Achievement::class,
        Stairs50Achievement::class,
        Stairs200Achievement::class,

        // dice
        Dice50Achievement::class,
        Dice200Achievement::class,
        Dice1000Achievement::class,

        // coinflip
        Coinflip25Achievement::class,
        Coinflip150Achievement::class,
        Coinflip500Achievement::class,

        // wheel
        Wheel50Achievement::class,
        Wheel300Achievement::class,
        Wheel1000Achievement::class,

        // hilo
        HiLo25Achievement::class,
        HiLo150Achievement::class,
        HiLo500Achievement::class,

        // crash
        Crash50Achievement::class,
        Crash150Achievement::class,
        Crash500Achievement::class,

        // roulette
        Roulette50Achievement::class,
        Roulette150Achievement::class,
        Roulette25Achievement::class,

        // blackjack
        Blackjack50Achievement::class,
        Blackjack200Achievement::class,
        Blackjack21_50Achievement::class,

        // plinko
        Plinko25Achievement::class,
        Plinko250Achievement::class,
        Plinko1000Achievement::class,

        // keno
        Keno25Achievement::class,
        Keno250Achievement::class,
        Keno1000Achievement::class

        // event
    ];

    public static function categories() : array {
        $result = array();
        foreach(self::instances() as $instance)
            if(!in_array($instance->category(), $result)) array_push($result, $instance->category());
        return $result;
    }

    public static function get($category) : array {
        $result = array();
        foreach(self::instances() as $instance)
            if($instance->category() === $category) array_push($result, $instance);
        return $result;
    }

    public static function instances() : array {
        $result = array();
        foreach(self::$achievements as $achievement)
            array_push($result, new $achievement);
        return $result;
    }

}