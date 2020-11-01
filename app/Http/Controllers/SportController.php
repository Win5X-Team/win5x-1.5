<?php

namespace App\Http\Controllers;

use App\SportBet;
use App\User;
use Auth;
use DateTime;
use DateTimeZone;

class SportController extends Controller {

    public static function parseLive($game) {
        $ids = [
            "soccer" => 1
        ];
        return self::sortClubs(json_decode(file_get_contents("https://1xstavka.ru/LiveFeed/BestGamesExtVZip?sports=".$ids[$game]."&count=100&antisports=188&partner=51&getEmpty=true&mode=4"), true)['Value']);
    }

    public static function parseSoccer() {
        return self::sortClubs(json_decode(file_get_contents("https://1xstavka.ru/LineFeed/Get1x2_VZip?sports=1&count=50&tf=2200000&tz=5&antisports=188&mode=4&country=1&partner=51&getEmpty=true"), true)['Value']);
    }

    public static function parseSoccerGame($id, $isLive = null) {
        if($isLive == null) {
            $json = json_decode(file_get_contents("https://1xstavka.ru/LineFeed/GetGameZip?id=" . $id . "&lng=ru&cfview=0&isSubGames=true&GroupEvents=true&allEventsGroupSubGames=true&countevents=250&partner=51"), true);
            $isLive = ($json['Value']['CI'] ?? '-') === '-';
        }
        if($isLive) $json = json_decode(file_get_contents("https://1xstavka.ru/LiveFeed/GetGameZip?id=".$id."&lng=ru&cfview=0&isSubGames=true&GroupEvents=true&allEventsGroupSubGames=true&countevents=250&partner=51"), true);

        if($json['Success'] === false) return null;
        $json = $json['Value'];
        return self::formatSoccerGame($id, $json, false, $isLive);
    }

    public static function formatSoccerGame($id, $json, $isQuickJson, $isLive = null) {
        $isFinished = str_contains($json['SC']['CPS'] ?? '-', "Игра завершена");
        return array_merge([
            'id' => $id,
            'isLive' => $isLive,
            'isFinished' => $isFinished,
            'type' => $json['CN'] ?? '-',
            'location' => self::findKey($json['MIS'] ?? [], 2),
            'first' => $json['O1'] ?? '-',
            'second' => $json['O2'] ?? '-',
            'date' => self::formatDate($json['S']),
            'info' => $json['MIO']['TSt'] ?? '-',
            'header' => $json['L'] ?? '-',
            'weather' => [
                'weather' => [
                    'title' => self::findKey($json['MIS'] ?? [], 21),
                    'value' => self::findKey($json['MIS'] ?? [], 9)
                ],
                'wind' => [
                    'title' => self::findKey($json['MIS'] ?? [], 24),
                    'value' => self::findKey($json['MIS'] ?? [], 23)
                ],
                't' => [
                    'title' => self::findKey($json['MIS'] ?? [], 26),
                    'value' => self::findKey($json['MIS'] ?? [], 25)
                ],
                'rain' => [
                    'title' => self::findKey($json['MIS'] ?? [], 28),
                    'value' => self::findKey($json['MIS'] ?? [], 27)
                ]
            ],

            'score' => [
                'title' => $json['SC']['CPS'] ?? '-',
                'first' => $json['SC']['FS']['S1'] ?? ($isLive ? 0 : '-'),
                'second' => $json['SC']['FS']['S2'] ?? ($isLive ? 0 : '-')
            ],

            'stats' => [
                'first' => [
                    'corner' => self::findKey($json['SC']['S'] ?? [], 'ICorner1', 'Key', 'Value'),
                    'yellowCard' => self::findKey($json['SC']['S'] ?? [], 'IYellowCard1', 'Key', 'Value'),
                    'redCard' => self::findKey($json['SC']['S'] ?? [], 'IRedCard1', 'Key', 'Value'),
                    'penalty' => self::findKey($json['SC']['S'] ?? [], 'IPenalty1', 'Key', 'Value'),
                    'sub' => self::findKey($json['SC']['S'] ?? [], 'ISub1', 'Key', 'Value'),
                    'shotsOn' => self::findKey($json['SC']['S'] ?? [], 'ShotsOn1', 'Key', 'Value'),
                    'shotsOff' => self::findKey($json['SC']['S'] ?? [], 'ShotsOff1', 'Key', 'Value'),
                    'attacks' => self::findKey($json['SC']['S'] ?? [], 'Attacks1', 'Key', 'Value'),
                    'dangerAttacks' => self::findKey($json['SC']['S'] ?? [], 'DanAttacks1', 'Key', 'Value')
                ],
                'second' => [
                    'corner' => self::findKey($json['SC']['S'] ?? [], 'ICorner2', 'Key', 'Value'),
                    'yellowCard' => self::findKey($json['SC']['S'] ?? [], 'IYellowCard2', 'Key', 'Value'),
                    'redCard' => self::findKey($json['SC']['S'] ?? [], 'IRedCard2', 'Key', 'Value'),
                    'penalty' => self::findKey($json['SC']['S'] ?? [], 'IPenalty2', 'Key', 'Value'),
                    'sub' => self::findKey($json['SC']['S'] ?? [], 'ISub2', 'Key', 'Value'),
                    'shotsOn' => self::findKey($json['SC']['S'] ?? [], 'ShotsOn2', 'Key', 'Value'),
                    'shotsOff' => self::findKey($json['SC']['S'] ?? [], 'ShotsOff2', 'Key', 'Value'),
                    'attacks' => self::findKey($json['SC']['S'] ?? [], 'Attacks2', 'Key', 'Value'),
                    'dangerAttacks' => self::findKey($json['SC']['S'] ?? [], 'DanAttacks2', 'Key', 'Value')
                ],
                'stat' => self::findKey($json['SC']['S'] ?? [], 'Stat', 'Key', 'Value')
            ]
        ], self::validateBets($isQuickJson ? self::quickSoccerBets($json) : self::soccerBets($json)));
    }

    public static function soccerBets($json) {
        $main = [];

        $main += [
            '1x2' => [
                1 => ['value' => $json['GE'][0]['E'][0][0]['C'] ?? '-'],
                2 => ['value' => $json['GE'][0]['E'][1][0]['C'] ?? '-'],
                3 => ['value' => $json['GE'][0]['E'][2][0]['C'] ?? '-']
            ]
        ];

        return ['bets' => [
            'main' => $main
        ]];
    }

    public static function quickSoccerBets($json) {
        return self::validateBets(['bets' => [
            'main' => [
                '1x2' => [
                    1 => ['value' => $json['E'][0]['C'] ?? '-'],
                    2 => ['value' => $json['E'][1]['C'] ?? '-'],
                    3 => ['value' => $json['E'][2]['C'] ?? '-'],
                ]
            ]
        ]]);
    }

    public static function sport_bet($game, $id, $betCategory, $category, $index, $wager, $descJson) {
        if(Auth::guest()) return json_encode(['error' => -1]);
        $user = User::where('id', Auth::user()->id)->first();

        $json = null;
        switch ($game) {
            case 'soccer': $json = self::parseSoccerGame($id); break;
            default: return json_encode(['error' => 0]);
        }

        if($json['isFinished'] == true) return json_encode(['error' => 1]);

        $bet = $json['bets'][$betCategory][$category][$index];
        if($bet['validated'] === false) return json_encode(['error' => 3]);
        $multiplier = $bet['value'];

        if(!is_numeric($wager) || $user->money < $wager) return json_encode(['error' => 2]);
        $user->money = $user->money - $wager;
        $user->save();

        $description = json_decode($descJson, true);
        return json_encode(['id' => SportBet::insertGetId([
            'wager' => $wager,
            'game_id' => $id,
            'user_id' => $user->id,
            'game' => $game,
            'category' => $category,
            'category_index' => $index,
            'multiplier' => $multiplier,
            'description_header' => $description['header'],
            'description_title' => $description['title'],
            'description_subtitle' => $description['subtitle'],
            'status' => 0
        ])]);
    }

    public static function betCron() {
        
    }

    public static function validateBets($arr) {
        foreach ($arr['bets'] as &$betCategory) {
            foreach ($betCategory as &$betSubcategory) {
                foreach ($betSubcategory as &$betOption)
                    $betOption = $betOption + ['validated' => $betOption['value'] !== '-'];
            }
        }
        return $arr;
    }

    public static function sortClubs($parsed, $skipFinished = true, $by = 'L') {
        $clubs = [];
        foreach($parsed as $parsedItem) {
            if($skipFinished && str_contains($parsedItem['SC']['CPS'] ?? '-', "Игра завершена")) continue;

            if(isset($clubs[$parsedItem[$by]])) array_push($clubs[$parsedItem['L']], $parsedItem);
            else $clubs = array_merge($clubs, [$parsedItem['L'] => [$parsedItem]]);
        }
        return $clubs;
    }

    public static function findKey($arr, $key, $find = 'K', $return = 'V') {
        foreach($arr as $value) if($value[$find] == $key) return $value[$return];
        return '-';
    }

    public static function formatDate($unixTimestamp) {
        $monthsList = array(".01." => "января", ".02." => "февраля",
            ".03." => "марта", ".04." => "апреля", ".05." => "мая", ".06." => "июня",
            ".07." => "июля", ".08." => "августа", ".09." => "сентября",
            ".10." => "октября", ".11." => "ноября", ".12." => "декабря");
        $days = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        $datew = new DateTime(null, new DateTimeZone('Etc/GMT-5'));
        $datew->setTimestamp($unixTimestamp);
        $date = $datew->format('d.m. H:i');

        $mD = $datew->format(".m.");
        return $days[$datew->format('w')].", ".str_replace($mD, " ".$monthsList[$mD].". ", $date);
    }

    public static function countTotalLiveGames(...$clubArrays) {
        $total = 0;
        foreach($clubArrays as $array)
            foreach($array as $match) $total += sizeof($match);
        return $total;
    }

}