<?php

namespace App\Http\Controllers;

use App\Achievement;
use App\Achievements;
use App\Battlegrounds15Achievement;
use App\Battlegrounds1Achievement;
use App\Battlegrounds50Achievement;
use App\Blackjack200Achievement;
use App\Blackjack21_50Achievement;
use App\Blackjack50Achievement;
use App\Box;
use App\Coinflip150Achievement;
use App\Coinflip25Achievement;
use App\Coinflip500Achievement;
use App\Crash150Achievement;
use App\Crash500Achievement;
use App\Crash50Achievement;
use App\Dice1000Achievement;
use App\Dice200Achievement;
use App\Dice50Achievement;
use App\FilteredWord;
use App\Freebie100Achievement;
use App\Freebie15Achievement;
use App\Freebie50Achievement;
use App\Game1000Achievement;
use App\HiLo150Achievement;
use App\HiLo25Achievement;
use App\HiLo500Achievement;
use App\Keno1000Achievement;
use App\Keno250Achievement;
use App\Keno25Achievement;
use App\Level10Achievement;
use App\Level3Achievement;
use App\Level5Achievement;
use App\Level8Achievement;
use App\Mines5000Achievement;
use App\Mines500Achievement;
use App\Mines50Achievement;
use App\Notification;
use App\Plinko1000Achievement;
use App\Plinko250Achievement;
use App\Plinko25Achievement;
use App\Referral100Achievement;
use App\Referral10Achievement;
use App\Referral500Achievement;
use App\RetardAchievement;
use App\Roulette150Achievement;
use App\Roulette25Achievement;
use App\Roulette50Achievement;
use App\Stairs100Achievement;
use App\Stairs200Achievement;
use App\Stairs50Achievement;
use App\Tower100Achievement;
use App\Tower200Achievement;
use App\Tower50Achievement;
use App\Transaction;
use App\VideoStream;
use App\Wheel1000Achievement;
use App\Wheel300Achievement;
use App\Wheel50Achievement;
use App\Withdraw;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Request;
use App\Http\Requests;
use App\User;
use App\Settings;
use App\Game;
use App\Promocode;
use App\Task;
use Auth;
use DB;
use Bulletproof\Image;
use randomAvatarsGenerator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Pelago\Emogrifier\CssInliner;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

require_once "provablyfair/Traits/HasValue.php";
require_once "provablyfair/Contracts/AlgorithmInterface.php";
require_once "provablyfair/Contracts/SeedInterface.php";
require_once "provablyfair/Contracts/SystemInterface.php";
require_once "provablyfair/Exceptions/InvalidAlgorithmException.php";
require_once "provablyfair/Exceptions/InvalidSeedException.php";
require_once "provablyfair/Algorithm.php";
require_once "provablyfair/Seed.php";
require_once "provablyfair/System.php";
require_once "bulletproof/bulletproof.php";
require_once "bulletproof/utils/func.image-resize.php";
require_once "bulletproof/randomAvatarsGenerator.php";
require_once "smtp/Exception.php";
require_once "smtp/PHPMailer.php";
require_once "smtp/SMTP.php";
require_once "cssinliner/HtmlProcessor/AbstractHtmlProcessor.php";
require_once "cssinliner/HtmlProcessor/CssToAttributeConverter.php";
require_once "cssinliner/HtmlProcessor/HtmlNormalizer.php";
require_once "cssinliner/HtmlProcessor/HtmlPruner.php";
require_once "cssinliner/Utilities/ArrayIntersector.php";
require_once "cssinliner/Utilities/CssConcatenator.php";
require_once "cssinliner/CssInliner.php";

function require_all($folder){
    foreach (glob("{$folder}/*.php") as $filename) require_once $filename;
}

require_all(__DIR__."/achievements");
require_all(__DIR__."/achievements/category");

class GeneralController extends Controller {

    public static function logTransaction($sum, $type, $data, $id = null) {
        if($id == null && Auth::guest()) return;

        if($type == 2) {
            Achievement::award(new \App\Game1Achievement(), $id);
            Achievement::awardProgress(new \App\Game1000Achievement(), 1);
            Achievement::awardProgress(new \App\Game5000Achievement(), 1);
        }

        Transaction::insert([
            'user_id' => $id == null ? Auth::user()->id : $id,
            'sum' => $sum,
            'type' => $type,
            'data' => $data,
            'time' => time(),
            'current' => $id == null ? Auth::user()->money : User::where('id', $id)->first()->money
        ]);
    }

    public static function shouldFake($gameId, $dbGameId = null) {
        if(self::isDemo($dbGameId) || Auth::guest()) return false;
        if(!Auth::guest() && Auth::user()->chat_role == 1) return false;
        $divisor = pow(10, 3);
        $random = mt_rand(0, 100 * $divisor) / $divisor;

        $v = AdminController::getAdjustmentValues($gameId);
        $baseChance = $v['base'];
        $maxChance = $v['max'];
        $speed = $v['speed'];

        $alg = ((((( $baseChance * 6) -1.45)/100)+( Auth::user()->money * 2.5))*($speed * 1000)+4.4)/1.5;
        if($alg > $maxChance) $alg = $maxChance;
        return $alg > $random;
    }

    public static function isDemo($game_id = null) {
        return $game_id == null ? isset($_GET['demo']) : Game::where('id', $game_id)->first()->demo == 1;
    }

    public static function getGameIcon($id) {
        switch($id) {
            case 0: return 'fas fa-layer-group';
            case 1: return 'fa fa-dice';
            case 2: return 'fad fa-circle';
            case 3: return 'la la-line-chart';
            case 4: return 'fas fa-coin';
            case 5: return 'fas fa-bomb';
            case 6: return 'fad fa-swords';
            case 7: return 'fad fa-clone';
            case 8: return 'fas fa-spade';
            case 9: return 'fad fa-chess-rook';
            case 10: return 'fad fa-badge';
            case 11: return 'la la-stream';
            case 12: return 'fad fa-box-open';
            case 13: return 'fas fa-ball-pile';
            case 14: return 'fad fa-octagon';
        }
        return 'fa fa-question';
    }

    public static function getGameName($id) {
        switch($id) {
            case 1: return 'Dice';
            case 2: return 'Wheel';
            case 3: return 'Crash';
            case 4: return 'Coinflip';
            case 5: return 'Mines';
            case 6: return 'Battlegrounds';
            case 7: return 'HiLo';
            case 8: return 'Blackjack';
            case 9: return 'Tower';
            case 10: return 'Roulette';
            case 11: return 'Stairs';
            case 12: return 'Кейсы';
            case 13: return 'Plinko';
            case 14: return 'Keno';
        }
        return 'Unknown';
    }

    public static function game_info_from_id($id) {
        return json_encode(array(
            'name' => self::getGameName($id),
            'icon' => self::getGameIcon($id)
        ));
    }

    public static function game_time() {
        return time();
    }

    public static function get_time($time) {
        try {
            $date = new DateTime(null, new DateTimeZone('Etc/GMT-3'));
            $date->setTimestamp($time);
            return $date->format('H:i');
        } catch (\Exception $e) {
            return $time;
        }
    }

    public function task_info_from_id($id) {
        $task = Task::where('id', $id)->first();
        if($task == null) return "null";
        return json_encode(array(
            'start_time' => $task->start_time,
            'end_time' => $task->end_time,
            'price' => $task->price,
            'reward' => $task->reward,
            'game_id' => $task->game_id,
            'value' => $task->value,
            'id' => $task->id
        ));
    }

    public static function declOfNum($num, $titles) {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $num . " " . $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
    }

    public function has_tries($u, $id) {
        $user = User::where('id', $u)->first();

        $arr = json_decode($user->task_tries, true);
        foreach ($arr as $key => $v) {
            if (isset($v[$id]) && $v[$id] != null) {
                if ($v[$id] > 0) return '1';
                return '0';
            }
        }
        return '0';
    }

    public function get_task_tries($task_id) {
        $user = User::where('id', Auth::user()->id)->first();
        $arr = json_decode($user->task_tries, true);
        foreach ($arr as $key => $v) {
            if (isset($v[$task_id]) && $v[$task_id] != null) {
                if ($v[$task_id] > 0) return $v[$task_id];
                return '0';
            }
        }
        return '0';
    }

    public function task_purchase($id, $value) {
        if(Auth::guest()) return json_encode(array('error' => -1));
        $user = User::where('id', Auth::user()->id)->first();
        $task = Task::where('id', $id)->first();
        if($task == null) return json_encode(array('error' => 0));

        $price = $task->price * (int) $value;
        if($user->money < $price) return json_encode(array('error' => 2));

        $arr = json_decode($user->task_tries, true);
        foreach ($arr as $key => $v) {
            if(isset($v[$id]) && $v[$id] != null) {
                if($v[$id] > 0) return json_encode(array('error' => 1));
                $arr[$key][$id] = $value;
                $user->task_tries = json_encode($arr);
                $user->save();
                return json_encode(array('success' => 2));
            }
        }

        array_push($arr, array($id => $value));
        $user->task_tries = json_encode($arr);
        $user->money = $user->money - $price;
        $user->save();
        self::logTransaction(-$price, 10, $this->task_description($id));
        return json_encode(array('success' => 1));
    }

    public function ref($code) {
        Cookie::queue('ref', $code, 100000);
        return redirect('/');
    }

    public function cancelWithdraw($id) {
        $withdraw = Withdraw::where('id', $id)->first();
        if($withdraw->user_id != Auth::user()->id || $withdraw->status != 0) return '-1';

        $withdraw->update(['status' => 3]);

        $user = User::where('id', $withdraw->user_id)->first();
        if (!is_null($user)) {
            $user->update(['money' => $user->money + $withdraw->amount]);
            self::logTransaction($withdraw->amount, 13, null, $withdraw->user_id);
        }
        return '1';
    }

    public function task_description($id) {
        $task = Task::where('id', $id)->first();
        if($task == null) return 'Задания не существует';
        switch ($task->game_id) {
            case 3: return 'Достигнуть значения коэффициента x'.$task->value; break;
            case 4: return 'Угадать сторону '.$this->declOfNum($task->value, array('раз', 'раза', 'раз')).' подряд'; break;
            case 5:
            case 9: return 'Открыть ячейки '.$this->declOfNum($task->value, array('раз', 'раза', 'раз')).' подряд'; break;
            case 1: return 'Получить число '.$task->value; break;
            case 7: return 'Угадать карты '.$this->declOfNum($task->value, array('раз', 'раза', 'раз')).' подряд'; break;
            default: return 'N/A';
        }
    }

    public function validate_task_completion($task_id, $game_id) {
        $task = Task::where('id', $task_id)->first();
        $game = Game::where('id', $game_id)->first();
        if(Auth::guest() || $task == null || $game == null) return json_encode(array('error' => -1));
        $user = User::where('id', $game->user_id)->first();

        $value = -1;
        $operator = 'higher_than';

        switch ($game->game_id) {
            case 9:
            case 4:
            case 5:
            case 7:
            case 3: $value = $game->cell_2; $operator = 'higher_than'; break;
            case 1: $value = $game->cell_2; $operator = 'equals'; break;
        }

        $win = $operator == 'higher_than' ? $value >= $task->value
            : ($operator == 'equals' ? $value == $task->value : false);

        if($win) {
            $user->money = $user->money + $task->reward;
            $user->save();

            $arr = json_decode($user->tasks_completed, false);
            array_push($arr, (int) $task_id);
            $user->tasks_completed = json_encode($arr);
            $user->save();
            self::logTransaction($task->reward, 11, $this->task_description($task_id), $user->id);
        } else {
            $arr = json_decode($user->task_tries, true);
            foreach ($arr as $key => $v) {
                if(isset($v[$task_id]) && $v[$task_id] != null) {
                    $arr[$key][$task_id] = ((int) $arr[$key][$task_id]) - 1;
                    $user->task_tries = json_encode($arr);
                    $user->save();
                }
            }
        }

        return json_encode(array('completed' => $win ? true : false, 'reward' => $task->reward));
    }

    public function sendEmail($to, $title, $html) {
        try {
            $mail = new PHPMailer();

            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';

            $mail->Host = "smtp.mail.ru";
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->Username = "support@win5x.com";
            $mail->Password = "password";

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = CssInliner::fromHtml($html)->inlineCss()->render();

            $mail->setFrom('support@win5x.com', 'Win5X');
            $mail->addReplyTo('support@win5x.com', 'Win5X');

            $mail->addAddress($to);

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

	public function auth($login, $password) {
        $preferred_login = null;

        if(User::where('email', '=', $login)->exists())
            $preferred_login = 'email';
        else if(User::where('username', '=', $login)->exists())
            $preferred_login = 'username';

        if($preferred_login == null)
            return json_encode(array('error' => 0));

        $user = User::where($preferred_login, '=', $login)->first();
        if($user->password == null || hash('sha256', $password) != $user->password)
            return json_encode(array('error' => 1));

        Auth::login($user, true);
        return json_encode(array('response' => 'success'));
    }

	public function register($username, $email, $password) {
        try {
            $avatar_hash = bin2hex(random_bytes(15));
            $ref = substr(str_shuffle(MD5(microtime())), 0, 8);
            $nick = bin2hex(random_bytes(8));
        } catch (\Exception $e) {
            return json_encode(array('error' => 0));
        }

        if(preg_match('/^[a-zA-Z0-9]{5,}$/', $username) == 0 || strlen($username) > 24)
            return json_encode(array('error' => 2));

        if(User::where('username', '=', $username)->exists())
            return json_encode(array('error' => 3));

        if(User::where('email', '=', $email)->exists())
            return json_encode(array('error' => 4));

        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false)
            return json_encode(array('error' => 1));

        if($this->isDomainAvailable("https://disify.com/")) {
            $response = json_decode(file_get_contents("https://disify.com/api/email/".$email), true);
            if(isset($response['disposable']) && $response['disposable'] == 1) return json_encode(array('error' => 5));
        }

        $avatar = new randomAvatarsGenerator();
        $avatar->generate();
        $avatar->draw();
        $avatar->saveImage('storage/img/generated', $avatar_hash.'.png');

        $email_confirm_hash = $this->generate_seed();
        $user = User::create([
            'username' => $username,
            'avatar' => '/storage/img/generated/' . $avatar_hash . '.png',
            'login' => $username,
            'login2' => $username,
            'ref_code' => $ref,
            'nick' => $nick,
            'email' => $email,
            'email_confirm_hash' => $email_confirm_hash,
            'password' => hash('sha256', $password),
            'time' => time()
        ]);

        Auth::login($user, true);
        $this->sendEmail($email, 'Подтверждение аккаунта win5x.com', view('pages.email.confirm')->with('hash', $email_confirm_hash));

        return json_encode(array('response' => 'success'));
    }

    public function email_resend() {
        if(Auth::guest() || Auth::user()->isActivated()) return 'reload';
        if(Auth::user()->email_confirm_hash == null) {
            $user = User::where('id', Auth::user()->id);
            $user->email_confirm_hash = $this->generate_seed();
            $user->save();
        }
        $this->sendEmail(Auth::user()->email, 'Подтверждение аккаунта win5x.com', view('pages.email.confirm')->with('hash', Auth::user()->email_confirm_hash));
        return 'ok';
    }

    public function email_confirm($hash) {
        if(Auth::guest() || Auth::user()->isActivated() || $hash != Auth::user()->email_confirm_hash) return "<script>window.location.href='/'</script>";
        $user = User::where('id', Auth::user()->id)->first();
        $user->email_confirmed = 1;
        $user->save();

        if(Cookie::get('ref') !== null) {
            $referer = User::where('ref_code', Cookie::get('ref'))->first();
            if ($referer != false) {
                $summ = Settings::where('id', 1)->first();
                $user = User::where('id', Auth::user()->id)->first();

                $user->ref_use = Cookie::get('ref');
                $user->money = $user->money + $summ->promo_sum;
                $user->save();
                self::logTransaction($summ->promo_sum, 12, null);

                DB::table('promocodes')->insertGetId(["code" => Cookie::get('ref'), "user" => Auth::user()->id]);
            }
        }

        return "<script>window.location.href='/'</script>";
    }

    public function obf_tree() {
        $result = array();
        $include = function($with = '/') use(&$result) {
            if(!isset($result[$with])) $result[$with] = array();
            foreach(Storage::disk('js')->files($with) as $file) {
                array_push($result[$with], substr_count($file, '/') > 0 ? substr($file, strrpos($file, '/')+1) : $file);
            }
        };

        $include('/');
        $include('/game/');
        return json_encode($result);
    }


    public function provably_fair($server_seed, $client_seed) {
        $algo = new \ProvablyFair\Algorithm('sha512');
        $system = new \ProvablyFair\System($algo);
        $serverSeed = new \ProvablyFair\Seed($server_seed);
        $clientSeed = new \ProvablyFair\Seed($client_seed);

        $serverSeed = $system->generateServerSeed($serverSeed);
        return array(
            'hash' => $serverSeed->getValue(),
            'result' => $system->calculate($serverSeed, $clientSeed)
        );
    }

    public function generate_seed_range($mod, $min, $max, $default = null) {
        return $this->generate_seed_range_nonce($mod, $min, $max, null, $default);
    }

    public function generate_seed_range_nonce($mod, $min, $max, $nonce, $default = null) {
        $seed = null;
        try {
            while($seed == null) {
                $server_seed = $this->generate_seed($default);
                $v = ((int) $this->provably_fair($server_seed, $this->get_client_seed().($nonce == null ? '' : $nonce))['result']);
                if($mod != null) $v = $v % $mod;

                if($v >= $min && $v <= $max) {
                    $seed = $server_seed;
                    break;
                }
            }
        } catch (\Exception $e) {
            $seed = 'Error';
        }
        return $seed;
    }

    public function generate_seed_specific($mod, $wanted, $default = null) {
        return $this->generate_seed_specific_nonce($mod, $wanted, null, $default);
    }

    public function generate_seed_specific_nonce($mod, $wanted, $nonce, $default = null) {
        return $this->generate_seed_range_nonce($mod, $wanted, $wanted, $nonce, $default);
    }

    public function generate_seed($default = null) {
        if($default == null) $default = mt_rand() . 'F';
        try {
            return bin2hex(random_bytes(15));
        } catch (\Exception $e) {
            return $default;
        }
    }

    public function provably_fair_web($server_seed, $client_seed) {
        return json_encode($this->provably_fair($server_seed, $client_seed));
    }

    public function get_client_seed() {
        if(Auth::guest()) return "guest";
        $user = User::where('id', Auth::user()->id)->first();
        if($user->client_seed == null) {
            $user->client_seed = $this->generate_seed($user->username);
            $user->save();
        }
        return $user->client_seed;
    }

    public function change_client_seed($seed) {
        if(Auth::guest()) return '-1';
        $user = User::where('id', Auth::user()->id)->first();
        $user->client_seed = $seed;
        $user->save();
        return '1';
    }

    public static function get_active_refs($take = false) {
        if(Auth::guest()) return 0;

        $total = 0;
        $took = 0;

        $vvod = DB::table('promocodes')->where('code', Auth::user()->ref_code)->orderBy('id', 'desc')->get();
        if(count($vvod) > 0) foreach($vvod as $v) {
            $v->user = User::where('id', $v->user)->first();
            $won = \App\Game::where('user_id', $v->user->id)->where('status', 1)->sum('win');

            if($won >= 5) {
                if(count(\App\User::whereRaw('JSON_CONTAINS(`ref_wheel_used`, \''.$v->user->id.'\', \'$\')')->get()) > 0) continue;

                if($take && $took < 10) {
                    $user = User::where('id', Auth::user()->id)->first();
                    $arr = json_decode($user->ref_wheel_used);
                    array_push($arr, $v->user->id);
                    $user->ref_wheel_used = json_encode($arr);
                    $user->save();
                    $took += 1;
                }

                $total += 1;
            }
        }

        return $total;
    }

    public function ref_bonus() {
        if(Auth::guest()) return response('-1');
        if(self::get_active_refs() < 10) return response('0');
        self::get_active_refs(true);

        $user = User::where('id', Auth::user()->id)->first();

        $segments = array(
            1 => 5.00,
            2 => 10.00,
            3 => 15.50,
            4 => 30.00,
            5 => 5.00,
            6 => 10.00,
            7 => 15.50,
            8 => 30.00,
            9 => 5.00,
            10 => 10.00,
            11 => 15.50,
            12 => 30.00,
            13 => 5.00,
            14 => 10.00,
            15 => 15.50
        );

        $segment = mt_rand(0, 14);
        if(!isset($segments[$segment])) $segment = mt_rand(1, 14);

        $user->money = $user->money + $segments[$segment];
        $user->save();
        self::logTransaction($segments[$segment], 5, null);

        return json_encode(array(
            'segment' => $segment,
            'money' => $segments[$segment]
        ));
    }

	public function bonus() {
        if(Auth::guest()) return response('-1');
        if(Auth::user()->money > 0) return response('0');
        if(strpos(Auth::user()->login, 'id') !== false && !\App\User::isSubscribed(Auth::user()->login2)) return response('-1');

        $user = User::where('id', Auth::user()->id)->first();
        if(time() < $user->latest_bonus_claim + 180) {
            $left = $user->latest_bonus_claim + 180 - time();
            return json_encode(array('time' => $left));
        }

        $segments = array(
            1 => 0.01,
            2 => 0.05,
            3 => 0.10,
            4 => 0.15,
            5 => 0.01,
            6 => 0.05,
            7 => 0.10,
            8 => 0.15,
            9 => 0.01,
            10 => 0.05,
            11 => 0.10,
            12 => 0.15,
            13 => 0.01,
            14 => 0.05,
            15 => 0.10
        );

        $segment = mt_rand(0, 14);
        if(!isset($segments[$segment])) $segment = mt_rand(1, 14);

        $additional = $user->level < 2 ? 0 : User::getBonusModifier($user->level);
        if($user->money < 0) $user->money = 0;
        $user->money = $user->money + $segments[$segment] + $additional;
        $user->latest_bonus_claim = time();
        $user->save();

        self::logTransaction($segments[$segment] + $additional, 4, null);
        Achievement::awardProgress(new Freebie15Achievement(), 1);
        User::exp(35);

        return json_encode(array(
            'segment' => $segment,
            'money' => $segments[$segment]
        ));
    }

    public function level_bonus() {
        if(Auth::guest()) return '0';
        $user = User::where('id', Auth::user()->id)->first();
        if($user->level < 2) return '0';

        return User::getBonusModifier($user->level);
    }

    public function text_to_image($text) {
        $font = (str_contains(url('/'), "win5x") ? '/var/www/html/' : 'D:/xampp/htdocs/') . '/public/fonts/OpenSans-Regular.ttf';

        $size = 12;
        $bbox = imageftbbox($size, 0, $font, $text);

        $width  = $bbox[2] - $bbox[6];
        $height = $bbox[3] - $bbox[7];

        $im = imagecreatetruecolor($width, $height);
        imagesavealpha($im, true);
        imagealphablending($im, false);

        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocatealpha($im, 0, 0, 0, 127);

        imagefill($im, 0, 0, $black);

        imagettftext($im, $size, 0, -$bbox[6], -$bbox[7], $white, $font, $text);

        ob_start();
        imagepng($im);
        $bin = ob_get_clean();
        $b64 = base64_encode($bin);
        imagedestroy($im);
        return response($b64);
    }

    public static function getMinesMultiplierTable() {
        return array(
            2 => array(1 => 1.03, 2 => 1.12, 3 => 1.23, 4 => 1.35, 5 => 1.5, 6 => 1.66, 7 => 1.86, 8 => 2.09, 9 => 2.37, 10 => 2.71, 11 => 3.13, 12 => 3.65, 13 => 4.31, 14 => 5.18, 15 => 6.33, 16 => 7.91, 17 => 10.17, 18 => 13.57, 19 => 19, 20 => 28.5, 21 => 47.5, 22 => 95, 23 => 285),
            3 => array(1 => 1.07, 2 => 1.23, 3 => 1.41, 4 => 1.64, 5 => 1.91, 6 => 2.25, 7 => 2.67, 8 => 3.21, 9 => 3.9, 10 => 4.8, 11 => 6, 12 => 7.63, 13 => 9.93, 14 => 13.24, 15 => 18.2, 16 => 26.01, 17 => 39.01, 18 => 62.42, 19 => 109.25, 20 => 218.2, 21 => 546.25, 22 => 2190),
            4 => array(1 => 1.13, 2 => 1.35, 3 => 1.64, 4 => 2, 5 => 2.48, 6 => 3.1, 7 => 3.92, 8 => 5.04, 9 => 6.6, 10 => 8.8, 11 => 12, 12 => 16.8, 13 => 24.77, 14 => 36.41, 15 => 57.22, 16 => 95.37, 17 => 171.67, 18 => 343.55, 19 => 801.16, 20 => 2400, 21 => 12020),
            5 => array(1 => 1.18, 2 => 1.5, 3 => 1.91, 4 => 2.48, 5 => 3.25, 6 => 4.34, 7 => 5.89, 8 => 8.15, 9 => 11.55, 10 => 16.8, 11 => 25.21, 12 => 39.21, 13 => 63.72, 14 => 109.25, 15 => 200.29, 16 => 400.58, 17 => 901.31, 18 => 2400, 19 => 8410, 20 => 50470),
            6 => array(1 => 1.25, 2 => 1.66, 3 => 2.25, 4 => 3.1, 5 => 4.34, 6 => 6.2, 7 => 9.06, 8 => 13.59, 9 => 21, 10 => 33.61, 11 => 56.02, 12 => 98.04, 13 => 182.08, 14 => 364.16, 15 => 801.16, 16 => 2000, 17 => 6010, 18 => 24040, 19 => 168000),
            7 => array(1 => 1.31, 2 => 1.86, 3 => 2.67, 4 => 3.92, 5 => 5.89, 6 => 9.06, 7 => 14.34, 8 => 23.48, 9 => 39.91, 10 => 70.96, 11 => 133.06, 12 => 266.12, 13 => 576.59, 14 => 1380, 15 => 3810, 16 => 12690, 17 => 57080, 18 => 457000),
            8 => array(1 => 1.39, 2 => 2.09, 3 => 3.21, 4 => 5.04, 5 => 8,15, 6 => 13.59, 7 => 23.48, 8 => 42.26, 9 => 79.83, 10 => 159.67, 11 => 342.15, 12 => 798.36, 13 => 2080, 14 => 6230, 15 => 22830, 16 => 114000, 17 => 1030000),
            9 => array(1 => 1.48, 2 => 2.37, 3 => 3.9, 4 => 6.6, 5 => 11.55, 6 => 21, 7 => 39.91, 8 => 79.83, 9 => 169.65, 10 => 387.77, 11 => 396.44, 12 => 2710, 13 => 8820, 14 => 35290, 15 => 194000, 16 => 1940000),
            10 => array(1 => 1.58, 2 => 2.71, 3 => 4.8, 4 => 8.8, 5 => 16.8, 6 => 33.61, 7 => 70.96, 8 => 159.67, 9 => 387.77, 10 => 1030, 11 => 3100, 12 => 10860, 13 => 47050, 14 => 282000, 15 => 3100000),
            11 => array(1 => 1.69, 2 => 3.13, 3 => 6, 4 => 12, 5 => 25.21, 6 => 56.02, 7 => 133.06, 8 => 342.15, 9 => 969.44, 10 => 3100, 11 => 11630, 12 => 54290, 13 => 353000, 14 => 4230000),
            12 => array(1 => 1.82, 2 => 3.65, 3 => 7.63, 4 => 16.8, 5 => 39.21, 6 => 98.04, 7 => 266.12, 8 => 768.36, 9 => 2710, 10 => 10860, 11 => 54290, 12 => 380000, 13 => 4940000),
            13 => array(1 => 1.97, 2 => 4.31, 3 => 9.93, 4 => 24.27, 5 => 63.72, 6 => 182.08, 7 => 576.59, 8 => 20800, 9 => 8820, 10 => 47050, 11 => 353000, 12 => 4940000),
            14 => array(1 => 2.15, 2 => 5.18, 3 => 13.24, 4 => 36.41, 5 => 109.25, 6 => 364.16, 7 => 1380, 8 => 6230, 9 => 35290, 10 => 282000, 11 => 4230000),
            15 => array(1 => 2.37, 2 => 6.33, 3 => 18.2, 4 => 57.22, 5 => 200.29, 6 => 801.16, 7 => 3810, 8 => 22830, 9 => 194000, 10 => 3110000),
            16 => array(1 => 2.63, 2 => 7.91, 3 => 26.01, 4 => 95.37, 5 => 400.58, 6 => 2000, 7 => 12690, 8 => 114000, 9 => 1940000),
            17 => array(1 => 2.96, 2 => 10.17, 3 => 39.01, 4 => 171.67, 5 => 901.31, 6 => 6010, 7 => 57080, 8 => 1030000),
            18 => array(1 => 3.39, 2 => 13.57, 3 => 62.42, 4 => 343.35, 5 => 2400, 6 => 24030, 7 => 457000),
            19 => array(1 => 3.95, 2 => 19, 3 => 109.25, 4 => 801.16, 5 => 8410, 6 => 168000),
            20 => array(1 => 4.75, 2 => 28.5, 3 => 218.5, 4 => 2400, 5 => 50470),
            21 => array(1 => 5.93, 2 => 47.5, 3 => 546.25, 4 => 12020),
            22 => array(1 => 7.91, 2 => 95, 3 => 2190),
            23 => array(1 => 11.87, 2 => 285),
            24 => array(1 => 23.75)
        );
    }

    public static function getTowerMultiplierTable() {
        return array(
            1 => array(
                1 => 1.19,
                2 => 1.48,
                3 => 1.86,
                4 => 2.32,
                5 => 2.90,
                6 => 3.62,
                7 => 4.53,
                8 => 5.66,
                9 => 7.08,
                10 => 8.85
            ),
            2 => array(
                1 => 1.58,
                2 => 2.64,
                3 => 4.40,
                4 => 7.33,
                5 => 12.22,
                6 => 20.36,
                7 => 33.94,
                8 => 56.56,
                9 => 94.27,
                10 => 157.11
            ),
            3 => array(
                1 => 2.38,
                2 => 5.94,
                3 => 14.84,
                4 => 37.11,
                5 => 92.77,
                6 => 231.93,
                7 => 579.83,
                8 => 1449.58,
                9 => 3623.96,
                10 => 9059.91
            ),
            4 => array(
                1 => 4.75,
                2 => 23,
                3 => 118,
                4 => 593,
                5 => 2968,
                6 => 14843,
                7 => 74218,
                8 => 371093,
                9 => 1855468,
                10 => 9277343
            )
        );
    }

    public static function getStairsMultiplierTable() {
        return array(
            1 => array(
                13 => 2.71,
                12 => 2.38,
                11 => 2.11,
                10 => 1.90,
                9 => 1.73,
                8 => 1.58,
                7 => 1.46,
                6 => 1.36,
                5 => 1.27,
                4 => 1.19,
                3 => 1.12,
                2 => 1.06,
                1 => 1.00
            ),
            2 => array(
                13 => 8.60,
                12 => 6.45,
                11 => 5.01,
                10 => 4.01,
                9 => 3.28,
                8 => 2.73,
                7 => 2.31,
                6 => 1.98,
                5 => 1.72,
                4 => 1.50,
                3 => 1.33,
                2 => 1.18,
                1 => 1.06
            ),
            3 => array(
                13 => 30.94,
                12 => 19.34,
                11 => 12.89,
                10 => 9.03,
                9 => 6.56,
                8 => 4.92,
                7 => 3.79,
                6 => 2.98,
                5 => 2.38,
                4 => 1.93,
                3 => 1.59,
                2 => 1.33,
                1 => 1.12
            ),
            4 => array(
                13 => 131.51,
                12 => 65.75,
                11 => 36.53,
                10 => 21.92,
                9 => 13.95,
                8 => 9.30,
                7 => 6.44,
                6 => 4.60,
                5 => 3.37,
                4 => 2.53,
                3 => 1.93,
                2 => 1.50,
                1 => 1.19
            ),
            5 => array(
                13 => 701.37,
                12 => 263.01,
                11 => 116.90,
                10 => 58.45,
                9 => 31.88,
                8 => 18.60,
                7 => 11.44,
                6 => 7.36,
                5 => 4.90,
                4 => 3.37,
                3 => 2.38,
                2 => 1.72,
                1 => 1.27
            ),
            6 => array(
                13 => 5260.29,
                12 => 1315.07,
                11 => 438.36,
                10 => 175.34,
                9 => 79.70,
                8 => 39.85,
                7 => 21.46,
                6 => 12.26,
                5 => 7.36,
                4 => 4.60,
                3 => 2.98,
                2 => 1.98,
                1 => 1.36
            ),
            7 => array(
                13 => 73644.00,
                12 => 9205.00,
                11 => 2045.67,
                10 => 613.70,
                9 => 223.16,
                8 => 92.98,
                7 => 42.92,
                6 => 21.46,
                5 => 11.44,
                4 => 6.44,
                3 => 3.79,
                2 => 2.31,
                1 => 1.46
            )
        );
    }

    public function tower_multiplier($bombs) {
        if($bombs < 1 || $bombs > 4) return response('-1');
        return json_encode($this->getTowerMultiplierTable()[$bombs]);
    }

    public function mines_multiplier($bombs) {
        if($bombs < 2 || $bombs > 24) return response('-1');
        return json_encode($this->getMinesMultiplierTable()[$bombs]);
    }

    public function stairs_multiplier($bombs) {
        if($bombs < 1 || $bombs > 7) return response('-1');
        return json_encode($this->getStairsMultiplierTable()[$bombs]);
    }

    public function stairs($wager, $mines) {
        if(Game::isDisabled('stairs')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');
        if($mines < 1 || $mines > 7) return response('{"error":3}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();
            Achievement::awardProgress(new Stairs100Achievement(), 1);
            self::logTransaction(-$wager, 2, 11);
        }

        $rows = array(
            1 => 20,
            2 => 19,
            3 => 19,
            4 => 17,
            5 => 19,
            6 => 15,
            7 => 17,
            8 => 12,
            9 => 11,
            10 => 19,
            11 => 10,
            12 => 9,
            13 => 8
        );

        $grid = array();
        // 0 - safe; 1 - bomb
        for($r = 1; $r <= 13; $r++) {
            $row = array();
            for ($i = 0; $i <= $rows[$r]; $i++)
                $row[$i] = 0;

            $left = $mines;
            do {
                $rr = mt_rand(0, $rows[$r]);
                if ($row[$rr] == 0) {
                    $row[$rr] = 1;
                    $left--;
                }
            } while ($left > 0);
            array_push($grid, $row);
        }

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => $mines,
            'cell_2' => 0,
            'cell_3' => -1,
            'cell_4' => json_encode($grid),
            'win' => -1,
            'status' => -1,
            'game_id' => 11,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id, 'bet' => $wager));
    }

    public function stairs_open($id, $row_cell_id) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');
        if($game->status != -1) return response('{"error":0}');

        $win = true;
        $grid = json_decode($game->cell_4, true);

        $mul = $this->getStairsMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2 + 1];

        $game->cell_2 = $game->cell_2 + 1;

        $rows = array(
            1 => 20,
            2 => 19,
            3 => 19,
            4 => 17,
            5 => 19,
            6 => 15,
            7 => 17,
            8 => 12,
            9 => 11,
            10 => 19,
            11 => 10,
            12 => 9,
            13 => 8
        );

        $generateGrid = function($lose) use($rows, $game, $row_cell_id, &$grid, &$generateGrid) {
            $row = array();
            for($i = 0; $i < $rows[(int) $game->cell_2]; $i++)
                $row[$i] = 0;

            $row[$row_cell_id] = 1;

            $left = (int) $game->cell_1;
            if($lose) $left -= 1;

            while($left > 0) {
                $r = mt_rand(0, $rows[(int) $game->cell_2] - 1);
                if(($lose && $row[$r] == 0) || (!$lose && $row[$r] == 0 && $r != $row_cell_id)) {
                    $row[$r] = 1;
                    $left--;
                }
            }

            $grid[(int) $game->cell_2] = $row;
            return $row;
        };

        if($grid[(int) $game->cell_2 - 1][$row_cell_id] == 1 || self::isItWayTooMuch($id, $mul) || self::shouldFake(11, $id)) {
            $win = false;
            $row = $generateGrid(true);
        } else {
            $row = $generateGrid(false);
            if((int) $game->cell_2 >= 13) {
                Achievement::awardProgress(new Stairs50Achievement(), 1);
                Achievement::awardProgress(new Stairs200Achievement(), 1);
            }
        }

        if(!$win) $game->status = 0;

        $game->multiplier = $mul;
        $game->cell_4 = json_encode($grid);
        $game->save();

        return json_encode(array(
            'status' => $win ? 'continue' : 'lose',
            'games' => (int) $game->cell_2,
            'grid' => $win ? 'Game is still in progress' : $grid,
            'row' => $row,
            'mul' => $mul,
            'profit' => $game->bet * $mul
        ));
    }

    public function stairs_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();

        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $mul = $game->cell_2 > 0 ? $this->getStairsMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2] : 0;
        $profit = $mul == 0 ? 0 : ($game->bet * $mul);

        if(!$this->isDemo($id) && $profit > 0) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 11);
        }

        $game->status = $profit > 0 ? 1 : 0;
        $game->win = $profit;
        $game->multiplier = $mul;
        $game->save();

        return json_encode(array(
            'profit' => $profit,
            'grid' => json_decode($game->cell_4)
        ));
    }

    public function tower($wager, $mines) {
        if(Game::isDisabled('tower')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');
        if($mines < 1 || $mines > 4) return response('{"error":3}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();
            self::logTransaction(-$wager, 2, 9);
            Achievement::awardProgress(new Tower100Achievement(), 1);
        }

        $grid = array();
        // 0 - safe ; 1 - bomb
        $row = -1;
        for($i = 0; $i < 5 * 10; $i++) {
            if($i % 5 == 0) $row += 1;
            if(!isset($grid[$row])) array_push($grid, array());
            $grid[$row][$i % 5] = 0;
        }
        for($row = 0; $row < 10; $row++) {
            $left = $mines;
            do {
                $r = mt_rand(0, 4);
                if($grid[$row][$r] == 1) continue;
                $grid[$row][$r] = 1;
                $left -= 1;
            } while($left != 0);
        }

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => $mines,
            'cell_2' => 0,
            'cell_3' => -1,
            'cell_4' => json_encode($grid),
            'win' => -1,
            'status' => -1,
            'game_id' => 9,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id, 'bet' => $wager));
    }

    public static function bgFragment() {
        $latestGame = \App\Game::where('game_id', 6)->orderBy('id', 'desc')->first();
        foreach(json_decode($latestGame->cell_4, true) as $player_id) {
            $player = \App\User::where('id', $player_id)->first();
            if ($player == null) continue;
            echo <<< HTML
                <div class="bg_player" onclick="load('user?id={$player->id}')">
                    <img class="lazyload" data-src="{$player->avatar}">
                </div>
HTML;
        }
    }

    public function tower_open($id, $row_cell_id) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');
        if($game->status != -1) return response('{"error":0}');

        $win = true;
        $grid = json_decode($game->cell_4, true);

        $mul = $this->getTowerMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2 + 1];
        $fake = self::shouldFake(9, $id) || $this->isItWayTooMuch($game->id, $mul);
        if($grid[(int) $game->cell_2][(int) $row_cell_id] == 1 || $fake) {
            $win = false;

            if($fake) {
                for($i = 0; $i < 5; $i++)
                    $grid[(int) $game->cell_2][$i] = 0;

                $grid[(int) $game->cell_2][$row_cell_id] = 1;

                $left = (int) $game->cell_1 - 1;
                while($left > 0) {
                    $v = mt_rand(0, 4);
                    if($v == $row_cell_id || $grid[(int) $game->cell_2][$v] == 1) continue;
                    $grid[(int) $game->cell_2][$v] = 1;
                    $left -= 1;
                }
                $game->cell_4 = json_encode($grid);
            }
        }

        if(!$win) $game->status = 0;
        else {
            $game->cell_2 = $game->cell_2 + 1;
            if((int) $game->cell_2 >= 10 && !$this->isDemo($id)) {
                Achievement::awardProgress(new Tower50Achievement(), 1);
                Achievement::awardProgress(new Tower200Achievement(), 1);
            }
        }
        $game->multiplier = $mul;
        $game->save();

        return json_encode(array(
            'status' => $win ? 'continue' : 'lose',
            'games' => (int) $game->cell_2,
            'grid' => $win ? 'Game is still in progress' : $grid,
            'row' => $grid[$win ? (int) $game->cell_2 - 1 : (int) $game->cell_2],
            'mul' => $mul,
            'profit' => $game->bet * $mul
        ));
    }

    public function tower_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();

        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $mul = $game->cell_2 > 0 ? $this->getTowerMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2] : 0;
        $profit = $mul == 0 ? 0 : ($game->bet * $mul);

        if(!$this->isDemo($id) && $profit > 0) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 9);
        }

        $game->status = $profit > 0 ? 1 : 0;
        $game->win = $profit;
        $game->multiplier = $mul;
        $game->save();

        return json_encode(array(
            'profit' => $profit,
            'grid' => json_decode($game->cell_4)
        ));
    }

    public function mines($wager, $mines) {
        if(Game::isDisabled('mines')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');
        if($mines < 2 || $mines > 24) return response('{"error":3}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();
            self::logTransaction(-$wager, 2, 5);
        }

        $grid = array();
        // 0 - safe ; 1 - bomb
        for($i = 0; $i < 5 * 5; $i++) {
            array_push($grid, 0);
        }

        $bombs_left = $mines;
        do {
            $b = rand(0, (5 * 5) - 1);
            if($grid[$b] === 1) continue;
            $grid[$b] = 1;
            $bombs_left -= 1;
        } while($bombs_left != 0);

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => $mines,
            'cell_2' => 0,
            'cell_3' => -1,
            'cell_4' => json_encode($grid),
            'win' => -1,
            'status' => -1,
            'game_id' => 5,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id, 'bet' => $wager));
    }

    public function upload_avatar() {
        if(Auth::guest()) return json_encode(array('error' => -1));

        if($_FILES) {
            $image = new Image($_FILES);
            if($image['pictures']) {
                $image->setSize(1000, 300000);
                $image->setMime(array('jpeg', 'png'));
                $image->setLocation('upload');
                $upload = $image->upload();
                if($upload) {
                    try {
                        \Bulletproof\Utils\resize($image->getFullPath(), $image->getMime(), $image->getWidth(), $image->getHeight(), 150, 150);

                        $user = User::where('id', Auth::user()->id)->first();
                        $user->avatar = '/'.$upload->getFullPath();
                        $user->save();

                        return json_encode(array('success' => '/'.$upload->getFullPath()));
                    } catch(\Exception $e) {
                        return json_encode(array('error' => 2));
                    }
                } else return json_encode(array('error' => 2, 'response' => $image->getError()));
            } else json_encode(array('error' => -2));
        } else return json_encode(array('error' => 1));
    }

    public function mines_mine($id, $mine_id) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');
        if($game->status != -1 || $this->history($id, $mine_id)) return response('{"error":0}');

        $game->cell_2 = $game->cell_2 + 1;

        $win = true;
        $grid = json_decode($game->cell_4, true);

        $mul = $this->getMinesMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2];
        $fake = $this->shouldFake(5, $id) || $this->isItWayTooMuch($game->id, $mul);
        if($grid[$mine_id] == 1 || $fake) {
            $win = false;

            if($fake) {
                if($grid[$mine_id] == 0) {
                    for ($i = 0; $i < 5 * 5; $i++) {
                        if ($i != $mine_id && $grid[$i] == 1) {
                            $grid[$i] = 0;
                            break;
                        }
                    }
                }
                $grid[$mine_id] = 1;
                $game->cell_4 = json_encode($grid);
            }
        }

        if(!$win) {
            $game->status = 0;
        } else if(!$this->isDemo($game->id)) {
            Achievement::awardProgress(new Mines50Achievement(), 1);
            Achievement::awardProgress(new Mines500Achievement(), 1);
            Achievement::awardProgress(new Mines5000Achievement(), 1);
        }

        $game->multiplier = $mul;
        $game->save();

        return json_encode(array(
            'status' => $win ? 'continue' : 'lose',
            'games' => (int) $game->cell_2,
            'grid' => $win ? 'Game is still in progress' : $grid,
            'mul' => $mul,
            'profit' => $game->bet * $mul
        ));
    }

    public function mines_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();

        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $mul = $game->cell_2 > 0 ? $this->getMinesMultiplierTable()[(int) $game->cell_1][(int) $game->cell_2] : 0;
        $profit = $mul == 0 ? 0 : ($game->bet * $mul);

        if(!$this->isDemo($id) && $profit > 0) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 5);
        }

        $game->status = $profit > 0 ? 1 : 0;
        $game->win = $profit;
        $game->multiplier = $mul;
        $game->save();

        return json_encode(array(
            'profit' => $profit,
            'grid' => json_decode($game->cell_4)
        ));
    }

    public function chat_unban() {
        if(Auth::guest()) return json_encode(array('error' => -2));
        $user = User::where('id', Auth::user()->id)->first();
        $price = $user->chat_total_bans * 50;

        if($user->is_chat_banned == 0) return json_encode(array('error' => -1));
        if($user->money < $price) return json_encode(array('error' => 0));
        if($user->chat_total_bans > 3) return json_encode(array('error' => 1));

        $user->money = $user->money - $price;
        $user->is_chat_banned = 0;
        $user->save();
        self::logTransaction(-$price, 3, null);
        return json_encode(array('response' => 1));
    }

    public function battlegrounds_check($id, $min_sum) {
        $user = User::where('id', $id)->first();
        if($user == null) return response('-1');
        if($user->money < floatval($min_sum)) return response('0');
        return response('1');
    }

    public function battlegrounds($winner_id, $sum, $players, $salt) {
        // это ключ для общения между battlegrounds.js <-> GeneralController, меняйте на свой и замените так же в battlegrounds.js
        if($salt !== "wwuu881x") return response('0');
        $this->give_balance($winner_id, $sum, $salt, 0);

        Achievement::awardProgress(new Battlegrounds1Achievement(), 1, $winner_id);
        Achievement::awardProgress(new Battlegrounds15Achievement(), 1, $winner_id);
        Achievement::awardProgress(new Battlegrounds50Achievement(), 1, $winner_id);

        $id = DB::table('games')->insertGetId([
            'bet' => -1,
            'user_id' => -2,
            'type' => -1,
            'cell_1' => -1,
            'cell_2' => -1,
            'cell_3' => -1,
            'cell_4' => $players,
            'win' => $sum,
            'status' => 1,
            'game_id' => 6,
            'multiplier' => -1,
            'time' => time()
        ]);

        return response($id);
    }

    public function remove_balance($id, $sum, $salt) {
        // это ключ для общения между battlegrounds.js <-> GeneralController, меняйте на свой и замените так же в battlegrounds.js
        if($salt !== "wwuu881x") return response('0');
        $user = User::where('id', $id)->first();
        if($user == null) return response('0');
        $user->money = $user->money - $sum;
        $user->save();
        self::logTransaction(-$sum, 2, 6, $id);
        return response('1');
    }

    public function give_balance($id, $sum, $salt, $type) {
        // это ключ для общения между battlegrounds.js <-> GeneralController, меняйте на свой и замените так же в battlegrounds.js
        if($salt !== "wwuu881x") return response('0');
        $user = User::where('id', $id)->first();
        if($user == null) return response('0');
        $user->money = $user->money + $sum;
        $user->save();
        if($type == 0) self::logTransaction($sum, 2, 6, $id);
        else if($type == 1) self::logTransaction($sum, 14, null, $id);
        return response('1');
    }

    public function chat_ban($id, $from, $salt) {
        // это ключ для общения между battlegrounds.js <-> GeneralController, меняйте на свой и замените так же в battlegrounds.js
        if($salt != "wwuu881x") return response('0');
        $user = User::where('id', $id)->first();
        if($user == null) return response('0');

        AdminController::log(4, array('id' => $id), $from);

        if($user->chat_role >= 2) return response('0');
        $user->is_chat_banned = 1;
        $user->chat_total_bans = $user->chat_total_bans + 1;
        $user->save();

        return response('1');
    }

    public function chat_info($user_id, $message = '') {
        $mutedWord = false;
        foreach(FilteredWord::get() as $word) if(str_contains($message, $word->word)) $mutedWord = true;

        if($mutedWord) {
            AdminController::mute($user_id, null, 60);
            return json_encode(['error' => 1]);
        }

        $user = User::where('id', $user_id)->first();
        if($user == null) return json_encode(array('error' => 'unknown id'));

        return json_encode(array(
            'avatar' => $user->avatar,
            'name' => $user->username,
            'type' => ($user->chat_role == 2 ? "moderator" : ($user->chat_role == 3 ? "admin" : ($user->level == 10 ? 'vip' : "user"))),
            'ban' => $user->is_chat_banned == 1 ? true : false,
            'level' => $user->level
        ));
    }

    public function socket_token($user_id, $data) {
        $salt = "win_#*3*1*5_x$%1_/ggax";
        if(Auth::guest() || Auth::user()->id != $user_id || Auth::user()->is_chat_banned == 1 || Auth::user()->mute > time()) return hash('sha256', rand());
        if(strpos($data, "\"system\":\"true\"") !== false && strpos($data, "actionsendgamegameid") === false && Auth::user()->chat_role < 2) return hash('sha256', rand());

        $hash = hash('sha256', $salt . $user_id . $data . $salt, true);
        $hash = hash_hmac('sha256', $salt, $hash);
        return $hash;
    }

    public function wheel($wager, $color) {
        if(Game::isDisabled('wheel')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');
        if($color != 'green' && $color != 'red' && $color != 'black') return response('{"error":0}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();
        }

        $segments = array(0 => 'green',
            2 => 'black', 4 => 'black', 6 => 'black', 8 => 'black', 10 => 'black', 12 => 'black', 14 => 'black',
            1 => 'red', 3 => 'red', 5 => 'red', 7 => 'red', 9 => 'red', 11 => 'red', 13 => 'red');

        $red_segments = array(1, 3, 5, 7, 9, 11, 13);
        $black_segments = array(2, 4, 6, 8, 10, 12, 14);

        $hash = $this->generate_seed();
        if(!$this->isDemo()) {
            $fake = self::shouldFake(2) || $this->isItWayTooMuchW(2, $color == 'green' ? 14 : 2, $wager);
            if($fake) {
                if ($color == 'green')
                    $hash = $this->generate_seed_range(15, 1, sizeof($segments));
                else if ($color == 'red' || $color == 'black')
                    $hash = $this->generate_seed_specific(15, $color == 'red' ? $black_segments[mt_rand(0, sizeof($black_segments) - 1)] : $red_segments[mt_rand(0, sizeof($red_segments) - 1)]);
            }
        }

        $rng = (int) ($this->provably_fair($hash, $this->get_client_seed())['result']) % 15;
        $r_color = $segments[$rng];

        $win = $color == $r_color;
        $profit = $color == 'green' ? $wager * 14 : $wager * 2;

        $id = -1;

        if(!$this->isDemo()) {
            if ($win) {
                $user->money = $user->money + $profit;
                self::logTransaction($profit, 2, 2);

                Achievement::awardProgress(new Wheel50Achievement(), 1);
                Achievement::awardProgress(new Wheel300Achievement(), 1);
                Achievement::awardProgress(new Wheel1000Achievement(), 1);
            } else self::logTransaction(-$wager, 2, 2);
            $user->save();

            $id = DB::table('games')->insertGetId([
                'bet' => $wager,
                'user_id' => Auth::user()->id,
                'type' => -1,
                'cell_1' => $rng,
                'cell_2' => $color == 'green' ? 0 : ($color == 'red' ? 1 : 2),
                'cell_3' => -1,
                'win' => $profit,
                'status' => $win ? 1 : 0,
                'game_id' => 2,
                'multiplier' => $color == 'green' ? 14 : 2,
                'server_seed' => $hash,
                'time' => $this->game_time(),
                'demo' => $this->isDemo()
            ]);
        }

        return json_encode(array(
            'id' => $id,
            'user_color' => $color,
            'color' => $r_color,
            'result' => $win,
            'profit' => $win ? '+' . $profit : '-' . $wager,
            'segment' => $rng,
            'demo' => $this->isDemo() ? 'true' : 'false'
        ));
    }

    public function chat_drop() {
        $users = array();

        $all = \DB::table('payments')->where('amount', '>=', 30)->where('time', '>=', \Carbon\Carbon::today(new DateTimeZone("Etc/GMT-3"))->timestamp)->where('status', 1)->where('type', 0)->get();
        if(sizeof($all) < 5) {
            $length = 5 - sizeof($users);
            $a = \DB::table('payments')->where('amount', '>=', 30)->where('status', 1)->where('amount', '>=', 30)->where('type', 0)->get();
            shuffle($a);
            $all += $a;
        }

        shuffle($all);

        $dub = array();
        foreach($all as $payment) {
            $user = User::where('id', $payment->user)->first();
            if($user == null || $payment->amount < 30) continue;

            if(in_array($user->id, $dub)) continue;
            array_push($dub, $user->id);

            array_push($users, array(
                'id' => $user->id,
                'name' => $user->username
            ));
        }

        $users = array_slice($users, 0, 5);
        foreach($users as $u) {
            $du = User::where('id', $u['id'])->first();
            if($du == null) continue;

            $du->money = $du->money + 2;
            $du->save();
        }
        return json_encode($users);
    }

    public function getDeck() {
        return array(
            1 => array('type' => 'spades', 'value' => 'A', 'rank' => 0, 'slot' => 1, 'blackjackValue' => 11),
            2 => array('type' => 'spades', 'value' => '2', 'rank' => 1, 'slot' => 2, 'blackjackValue' => 2),
            3 => array('type' => 'spades', 'value' => '3', 'rank' => 2, 'slot' => 3, 'blackjackValue' => 3),
            4 => array('type' => 'spades', 'value' => '4', 'rank' => 3, 'slot' => 4, 'blackjackValue' => 4),
            5 => array('type' => 'spades', 'value' => '5', 'rank' => 4, 'slot' => 5, 'blackjackValue' => 5),
            6 => array('type' => 'spades', 'value' => '6', 'rank' => 5, 'slot' => 6, 'blackjackValue' => 6),
            7 => array('type' => 'spades', 'value' => '7', 'rank' => 6, 'slot' => 7, 'blackjackValue' => 7),
            8 => array('type' => 'spades', 'value' => '8', 'rank' => 7, 'slot' => 8, 'blackjackValue' => 8),
            9 => array('type' => 'spades', 'value' => '9', 'rank' => 8, 'slot' => 9, 'blackjackValue' => 9),
            10 => array('type' => 'spades', 'value' => '10', 'rank' => 9, 'slot' => 10, 'blackjackValue' => 10),
            11 => array('type' => 'spades', 'value' => 'J', 'rank' => 10, 'slot' => 11, 'blackjackValue' => 10),
            12 => array('type' => 'spades', 'value' => 'Q', 'rank' => 11, 'slot' => 12, 'blackjackValue' => 10),
            13 => array('type' => 'spades', 'value' => 'K', 'rank' => 12, 'slot' => 13, 'blackjackValue' => 10),
            14 => array('type' => 'hearts', 'value' => 'A', 'rank' => 0, 'slot' => 1, 'blackjackValue' => 11),
            15 => array('type' => 'hearts', 'value' => '2', 'rank' => 1, 'slot' => 2, 'blackjackValue' => 2),
            16 => array('type' => 'hearts', 'value' => '3', 'rank' => 2, 'slot' => 3, 'blackjackValue' => 3),
            17 => array('type' => 'hearts', 'value' => '4', 'rank' => 3, 'slot' => 4, 'blackjackValue' => 4),
            18 => array('type' => 'hearts', 'value' => '5', 'rank' => 4, 'slot' => 5, 'blackjackValue' => 5),
            19 => array('type' => 'hearts', 'value' => '6', 'rank' => 5, 'slot' => 6, 'blackjackValue' => 6),
            20 => array('type' => 'hearts', 'value' => '7', 'rank' => 6, 'slot' => 7, 'blackjackValue' => 7),
            21 => array('type' => 'hearts', 'value' => '8', 'rank' => 7, 'slot' => 8, 'blackjackValue' => 8),
            22 => array('type' => 'hearts', 'value' => '9', 'rank' => 8, 'slot' => 9, 'blackjackValue' => 9),
            23 => array('type' => 'hearts', 'value' => '10', 'rank' => 9, 'slot' => 10, 'blackjackValue' => 10),
            24 => array('type' => 'hearts', 'value' => 'J', 'rank' => 10, 'slot' => 11, 'blackjackValue' => 10),
            25 => array('type' => 'hearts', 'value' => 'Q', 'rank' => 11, 'slot' => 12, 'blackjackValue' => 10),
            26 => array('type' => 'hearts', 'value' => 'K', 'rank' => 12, 'slot' => 13, 'blackjackValue' => 10),
            27 => array('type' => 'clubs', 'value' => 'A', 'rank' => 0, 'slot' => 1, 'blackjackValue' => 11),
            28 => array('type' => 'clubs', 'value' => '2', 'rank' => 1, 'slot' => 2, 'blackjackValue' => 2),
            29 => array('type' => 'clubs', 'value' => '3', 'rank' => 2, 'slot' => 3, 'blackjackValue' => 3),
            30 => array('type' => 'clubs', 'value' => '4', 'rank' => 3, 'slot' => 4, 'blackjackValue' => 4),
            31 => array('type' => 'clubs', 'value' => '5', 'rank' => 4, 'slot' => 5, 'blackjackValue' => 5),
            32 => array('type' => 'clubs', 'value' => '6', 'rank' => 5, 'slot' => 6, 'blackjackValue' => 6),
            33 => array('type' => 'clubs', 'value' => '7', 'rank' => 6, 'slot' => 7, 'blackjackValue' => 7),
            34 => array('type' => 'clubs', 'value' => '8', 'rank' => 7, 'slot' => 8, 'blackjackValue' => 8),
            35 => array('type' => 'clubs', 'value' => '9', 'rank' => 8, 'slot' => 9, 'blackjackValue' => 9),
            36 => array('type' => 'clubs', 'value' => '10', 'rank' => 9, 'slot' => 10, 'blackjackValue' => 10),
            37 => array('type' => 'clubs', 'value' => 'J', 'rank' => 10, 'slot' => 11, 'blackjackValue' => 10),
            38 => array('type' => 'clubs', 'value' => 'Q', 'rank' => 11, 'slot' => 12, 'blackjackValue' => 10),
            39 => array('type' => 'clubs', 'value' => 'K', 'rank' => 12, 'slot' => 13, 'blackjackValue' => 10),
            40 => array('type' => 'diamonds', 'value' => 'A', 'rank' => 0, 'slot' => 1, 'blackjackValue' => 11),
            41 => array('type' => 'diamonds', 'value' => '2', 'rank' => 1, 'slot' => 2, 'blackjackValue' => 2),
            42 => array('type' => 'diamonds', 'value' => '3', 'rank' => 2, 'slot' => 3, 'blackjackValue' => 3),
            43 => array('type' => 'diamonds', 'value' => '4', 'rank' => 3, 'slot' => 4, 'blackjackValue' => 4),
            44 => array('type' => 'diamonds', 'value' => '5', 'rank' => 4, 'slot' => 5, 'blackjackValue' => 5),
            45 => array('type' => 'diamonds', 'value' => '6', 'rank' => 5, 'slot' => 6, 'blackjackValue' => 6),
            46 => array('type' => 'diamonds', 'value' => '7', 'rank' => 6, 'slot' => 7, 'blackjackValue' => 7),
            47 => array('type' => 'diamonds', 'value' => '8', 'rank' => 7, 'slot' => 8, 'blackjackValue' => 8),
            48 => array('type' => 'diamonds', 'value' => '9', 'rank' => 8, 'slot' => 9, 'blackjackValue' => 9),
            49 => array('type' => 'diamonds', 'value' => '10', 'rank' => 9, 'slot' => 10, 'blackjackValue' => 10),
            50 => array('type' => 'diamonds', 'value' => 'J', 'rank' => 10, 'slot' => 11, 'blackjackValue' => 10),
            51 => array('type' => 'diamonds', 'value' => 'Q', 'rank' => 11, 'slot' => 12, 'blackjackValue' => 10),
            52 => array('type' => 'diamonds', 'value' => 'K', 'rank' => 12, 'slot' => 13, 'blackjackValue' => 10),
        );
    }

    public function generateFakedCard($value, $hidden = false) {
        $find = function() use(&$find, $value) {
            $index = mt_rand(1, 52);
            $card = $this->getDeck()[$index];
            if($card['blackjackValue'] != $value) return $find();
            return array('index' => $index, 'card' => $card);
        };
        $card = $find();
        return array(
            'index' => $card['index'],
            'type' => $card['card']['type'],
            'value' => $card['card']['value'],
            'blackjack_value' => $card['card']['blackjackValue'],
            'hidden' => $hidden
        );
    }

    public function generateCard($hash, $index, $hidden = false) {
        $index = ((int) $this->provably_fair($hash, $this->get_client_seed().":".$index)['result'] % 52) + 1;

        $card = $this->getDeck()[$index];
        return array(
            'index' => $index,
            'type' => $card['type'],
            'value' => $card['value'],
            'blackjack_value' => $card['blackjackValue'],
            'hidden' => $hidden
        );
    }

    public function blackjack_insure($id) {
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo() ? null : User::where('id', Auth::user()->id)->first();
        if($game == null || ($user == null && !$this->isDemo())) return response('{"error":1}');
        if($game->status != -1 || $game->cell_2 != 0) return response('{"error":0}');

        if($user != null) {
            if($user->money < $game->bet / 2) return response('{"error":2}');
            $user->money = $user->money - ($game->bet / 2);
            $user->save();
        }
        $game->cell_2 = 1;
        $game->bet = $game->bet / 2;
        $game->save();

        return response('{"result":1}');
    }

    public function blackjack_double($id) {
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo() ? null : User::where('id', Auth::user()->id)->first();
        if($game == null || ($user == null && !$this->isDemo())) return response('{"error":1}');
        if($game->status != -1 || $game->cell_3 != 0) return response('{"error":0}');

        if($user != null) {
            if($user->money < $game->bet) return response('{"error":2}');
            $user->money = $user->money - $game->bet;
            $user->save();
        }
        $game->cell_3 = 1;
        $game->bet = $game->bet * 2;
        $game->save();

        return response('1');
    }

    public function blackjack($wager) {
        if(Game::isDisabled('blackjack')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();

            Achievement::awardProgress(new Blackjack50Achievement(), 1);
        }

        $hash = $this->generate_seed();

        $player = array(
            $this->generateCard($hash, 0),
            $this->generateCard($hash, 1)
        );

        if(self::shouldFake(8)) {
            $order = mt_rand(0, 1);
            $dealer = array(
                $this->generateFakedCard($order == 0 ? 10 : 11),
                $this->generateFakedCard($order == 0 ? 11 : 10, true)
            );
        } else {
            $dealer = array(
                $this->generateCard($hash, 2),
                $this->generateCard($hash, 3, true)
            );
        }

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => 4,
            'cell_2' => 0,
            'cell_3' => 0,
            'cell_4' => json_encode(array(
                'player' => $player,
                'dealer' => $dealer
            )),
            'win' => -1,
            'status' => -1,
            'game_id' => 8,
            'server_seed' => $hash,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array(
            'id' => $id,
            'player' => $player,
            'dealer' => $dealer[0]
        ));
    }

    public function blackjack_hit($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $card = $this->generateCard($game->server_seed, $game->cell_1);

        $table = json_decode($game->cell_4, true);
        array_push($table['player'], $card);
        $game->cell_4 = json_encode($table);

        $game->cell_1 = $game->cell_1 + 1;
        $game->cell_3 = 1;
        $game->save();

        return json_encode(array('player' => $card));
    }

    public function blackjack_get_score($player, $includeHidden = true) {
        $score = 0; $aces = 0;

        foreach($player as $key => $value) {
            if($value['hidden'] == true && !$includeHidden) continue;

            $score += $value['blackjack_value'];
            if($value['blackjack_value'] == 11) $aces += 1;
            if($score > 21 && $aces > 0) {
                $score -= 10;
                $aces--;
            }
        }

        return $score;
    }

    public function blackjack_score($type, $id) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":1}');
        if($type != 'player' && $type != 'dealer') return response('{"error":2}');
        $table = json_decode($game->cell_4, true);
        return $this->blackjack_get_score($table[$type], false);
    }

    public function blackjack_get_winner($id) {
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $table = json_decode($game->cell_4, true);

        $dealerScore = $this->blackjack_get_score($table['dealer']);
        $playerScore = $this->blackjack_get_score($table['player']);

        $responseType = 'error'; $responseHeader = ''; $responseMessage = '';
        $playerHandSize = sizeof($table['player']); $dealerHandSize = sizeof($table['dealer']);

        $win = 0;

        if($playerScore == $dealerScore) {
            if($playerScore <= 21) {
                $win = $game->bet;
                $responseType = 'success';
                $responseHeader = 'Ничья!';
            } else {
                $responseType = 'error';
                $responseHeader = 'Вы проиграли!';
            }
        } else if($playerScore > $dealerScore) {
            if($playerScore == 21 && $playerHandSize < 3) {
                $win = ($game->bet * 2) + ($game->bet / 2);
                $responseType = 'success';
                $responseHeader = 'Вы выиграли!';
                $responseMessage = 'У вас блэкджек!';
            } else if($playerScore <= 21) {
                $win = $game->bet * 2;
                $responseType = 'success';
                $responseHeader = 'Вы выиграли!';
            } else if($playerScore > 21) {
                $responseType = 'error';
                $responseHeader = 'Вы проиграли!';
                $responseMessage = 'Вы набрали более 21 очков.';
            }
        } else if($playerScore < $dealerScore) {
            if($playerScore <= 21 && $dealerScore > 21) {
                $win = $game->bet * 2;
                $responseType = 'success';
                $responseHeader = 'Вы выиграли!';
                $responseMessage = 'Дилер набрал более 21 очков.';
            } else if($dealerScore <= 21) {
                $responseType = 'error';
                $responseHeader = 'Вы проиграли!';
            }
        }

        $game->status = $win == 0 ? 0 : 1;
        $game->win = $win;
        $game->multiplier = $win == 0 ? 0 : 2.00;
        $game->save();

        if($user != null) {
            if($win > 0) {
                $user->money = $user->money + $win;
                $user->save();
                self::logTransaction($win, 2, 8);

                if($playerScore == 21) Achievement::awardProgress(new Blackjack21_50Achievement(), 1);
                Achievement::awardProgress(new Blackjack200Achievement(), 1);
            } else {
                self::logTransaction(-$game->bet, 2, 8);
            }
        }

        return json_encode(array(
            'type' => $responseType,
            'header' => $responseHeader,
            'message' => $responseMessage
        ));
    }

    public function blackjack_stand($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":1}');
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $table = json_decode($game->cell_4, true);

        $dealerScore = $this->blackjack_get_score($table['dealer']);
        $playerScore = $this->blackjack_get_score($table['player']);

        $dealerDraw = array();

        $checkDealerScore = function() use(&$dealerScore, &$playerScore, &$game, &$table, &$checkDealerScore, &$dealerDraw) {
            if($dealerScore < 17 && $playerScore <= 21) {
                $card = $this->generateCard($game->server_seed, $game->cell_1);
                $game->cell_1 = $game->cell_1 + 1;

                array_push($dealerDraw, $card);
                array_push($table['dealer'], $card);
                $dealerScore += $card['blackjack_value'];

                $checkDealerScore();
            }
        };

        $checkDealerScore();
        $table['dealer'][1]['hidden'] = false;
        $game->cell_4 = json_encode($table);
        $game->save();

        return json_encode(array(
            'dealerReveal' => $table['dealer'][1], // Reveal second dealer card (which is hidden at the start of the game)
            'dealerScore' => $dealerScore,
            'playerScore' => $playerScore,
            'dealerDraw' => $dealerDraw,
            'status' => $this->blackjack_get_winner($id)
        ));
    }
    
    public function hilo($wager, $starting) {
        if(Game::isDisabled('hilo')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');
        if($starting < 1 || $starting > 52) return response('{"error":3}');
        if($this->getDeck()[$starting]['rank'] == 0 || $this->getDeck()[$starting]['rank'] == 12) return response('{"error":3}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if($user->money < $wager) return response('{"error":2}', 200);
            $user->money = $user->money - $wager;
            $user->save();
            self::logTransaction(-$wager, 2, 7);
        }

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => 0,
            'cell_2' => 0,
            'cell_3' => $starting,
            'win' => -1,
            'status' => -1,
            'game_id' => 7,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id, 'bet' => $wager));
    }

    public function hilo_flip($id, $type) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');
        if($game->status != -1) return response('{"error":0}');
        if($type !== 'lower' && $type !== 'higher') return response('{"error":-2}');

        $currentCard = $this->getDeck()[(int) $game->cell_3];
        $random = null;

        $adjust = self::shouldFake(7, $id) || $this->isItWayTooMuch($id, $game->cell_1);
        while($random == null) {
            $n = mt_rand(1, 52);
            $c = $this->getDeck()[$n];

            if($adjust && $currentCard['rank'] != 0 && $currentCard['rank'] != 12) {
                if ($type == 'higher' && $c['rank'] >= $currentCard['rank']) continue;
                if ($type == 'lower' && $c['rank'] <= $currentCard['rank']) continue;
            }
            $random = $n;
        }

        $generated = $this->getDeck()[$random];
        $started = $this->getDeck()[(int) $game->cell_3];

        $win = true;
        if(($type == 'higher' && $started['rank'] > $generated['rank'])
            || ($type == 'lower' && $started['rank'] < $generated['rank'])) {
            $win = false;
        }

        if($win) {
            $game->cell_2 = $game->cell_2 + 1;
            $m = $type === 'higher' ? (12.350 / (13 - ($started['slot'] - 1))) : (12.350 / ($started['slot']));

            $game->cell_3 = $random;
            $game->cell_1 = $game->cell_1 + $m;
        } else $game->status = 0;

        $game->multiplier = $game->cell_1 <= 0 ? 0 : $game->cell_1;
        $game->save();

        return json_encode(array(
            'startedAt' => json_encode($started),
            'generated' => json_encode($generated),
            'deck_index' => $random,
            'win' => $win,
            'games' => (int) $game->cell_2,
            'multiplier' => $game->multiplier
        ));
    }

    public function hilo_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $profit = ($game->bet * ($game->cell_1));

        if(!$this->isDemo($id) && $profit > 0) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 7);

            Achievement::awardProgress(new HiLo25Achievement(), 1);
            Achievement::awardProgress(new HiLo150Achievement(), 1);
            Achievement::awardProgress(new HiLo500Achievement(), 1);
        }

        $game->status = $profit > 0 ? 1 : 0;
        $game->win = $profit;
        $game->multiplier = $game->cell_1;
        $game->save();

        return json_encode(array('profit' => $profit));
    }

    public function coinflip($wager) {
        if(Game::isDisabled('coinflip')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}');
            $user->money = $user->money - $wager;
            $user->save();
            self::logTransaction(-$wager, 2, 4);
        }

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => 0,
            'cell_2' => 0,
            'cell_3' => -1,
            'win' => -1,
            'status' => -1,
            'game_id' => 4,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id, 'bet' => $wager));
    }

    public function coinflip_flip($id, $side) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');
        if($game->status != -1) return response('{"error":0}');

        $win = mt_rand(0, 100) < 50;
        if($win) {
            $game->cell_2 = $game->cell_2 + 1;
            if($game->cell_1 <= 0) $game->cell_1 = 1.9;
            else $game->cell_1 = ((float) $game->cell_1) * 2;
        }

        if(!$win || (self::shouldFake(4, $id) || $this->isItWayTooMuch($game->id, $game->cell_1))) {
            $win = false;
            $game->status = 0;
            $game->save();
        }

        $game->multiplier = $game->cell_1;
        $game->save();

        return json_encode(array(
            'status' => $win ? 'continue' : 'lose',
            'multiplier' => $game->cell_1,
            'side' => $win ? $side : ($side == 'red' ? 'black' : 'red'),
            'games' => (int) $game->cell_2
        ));
    }

    public function coinflip_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('{"error":-1}');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":1}');
        if($game->status != -1) return response('{"error":0}');

        $profit = ($game->bet * ($game->cell_1));

        if(!$this->isDemo($id) && $profit > 0) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 4);

            Achievement::awardProgress(new Coinflip25Achievement(), 1);
            Achievement::awardProgress(new Coinflip150Achievement(), 1);
            Achievement::awardProgress(new Coinflip500Achievement(), 1);
        }

        $game->status = $profit > 0 ? 1 : 0;
        $game->win = $profit;
        $game->multiplier = $game->cell_1;
        $game->save();

        return json_encode(array('profit' => $profit));
    }

    public function crash($wager) {
        if(Game::isDisabled('crash')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}', 200);
        }

        $hash = $this->generate_seed();
        if(!$this->isDemo()) {
            $settings = Settings::where('id', 1)->first();
            if(mt_rand(0, 100) > $settings->crash_s) {
                if(mt_rand(0, 100) > $settings->crash_m) {
                    if(mt_rand(0, 100) > $settings->crash_b) {
                        if(mt_rand(0, 100) > $settings->crash_h) {
                            if(mt_rand(0, 100) > $settings->crash_u) $hash = $this->generate_seed_range(null, 1001, 2000);
                            else $hash = $this->generate_seed_range(null, 401, 1000);
                        } else $hash = $this->generate_seed_range(null, 401, 1000);
                    } else $hash = $this->generate_seed_range(null, 251, 400);
                } else $hash = $this->generate_seed_range(null, 151, 250);
            } else $hash = $this->generate_seed_range(null, 101, 150);
        }

        $at = ((float) $this->provably_fair($hash, $this->get_client_seed())['result']) / 100.0;
        if($at > 20) $at = 20.0;
        if($at < 1) $at = 1.0;

        $id = DB::table('games')->insertGetId([
            'bet' => $wager,
            'user_id' => $this->isDemo() || Auth::guest() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => $at,
            'cell_2' => 1,
            'cell_3' => -1,
            'win' => -1,
            'status' => -1,
            'game_id' => 3,
            'server_seed' => $hash,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        return json_encode(array('id' => $id));
    }

    public function crash_tick($id) {
        $game = Game::where('id', $id)->first();
        if($game == null) return response('{"error":-1}');

        if($game->cell_2 < 20) $game->cell_2 = $game->cell_2 += 0.033;
        $game->multiplier = $game->cell_2;
        $game->save();

        if($game->cell_2 >= $game->cell_1) {
            if($game->cell_3 == -1) {
                if(!$this->isDemo($id)) {
                    $user = User::where('id', $game->user_id)->first();
                    $user->money = $user->money - $game->bet;
                    $user->save();
                    self::logTransaction(-$game->bet, 2, 3, $game->user_id);
                }
                $game->cell_3 = 1;
                $game->status = 0;
                $game->save();
            }
            return response('{"error":'.$game->cell_1.', "bet":'.$game->bet.'}');
        }
        return json_encode(array('mul' => $game->cell_2, 'bet' => $game->bet));
    }

    public function crash_take($id) {
        if(Auth::guest() && !$this->isDemo($id)) return response('-1');
        $game = Game::where('id', $id)->first();
        $user = Auth::guest() || $this->isDemo($id) ? null : User::where('id', $game->user_id)->first();
        if($game == null || ($user == null && !$this->isDemo($id))) return response('{"error":-1}');
        if($game->status == 0 || $game->status == 1) return response('{"error":0}');

        $profit = ($game->bet * ($game->cell_2-1));
        $profit = $game->cell_2 < 1 ? 0 : $profit;

        if(!$this->isDemo($id)) {
            $user->money = $user->money + $profit;
            $user->save();
            self::logTransaction($profit, 2, 3, $game->user_id);

            Achievement::awardProgress(new Crash50Achievement(), 1);
            Achievement::awardProgress(new Crash150Achievement(), 1);
            Achievement::awardProgress(new Crash500Achievement(), 1);
        }

        $game->status = 1;
        $game->win = $profit;
        $game->multiplier = $game->cell_2;
        $game->save();

        return json_encode(array('profit' => $profit, 'mul' => $game->cell_2, 'crash' => $game->cell_1));
    }

    public function dice_get_profit($wager, $low, $high) {
        return number_format($this->getPayout($low, $high) * $wager, 4, '.', '');
    }

    public function dice_get_profit_type($type, $wager, $chance) {
        return $this->dice_get_profit($wager, $type == 'lower' ? 0 : $chance, $type === 'higher' ? 100 : $chance);
    }

    public function roulette($bets) {
        if(Game::isDisabled('roulette')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');

        $json = json_decode($bets, true);
        if($json == null) return response('{"error":1}');
        $totalBet = 0;
        foreach($json as $key => $value) {
            $totalBet += $value;
        }

        if($totalBet < 0.01) return response('{"error":1}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $totalBet) return response('{"error":2}');
            $user->money = $user->money - $totalBet;
            $user->save();
        }

        $hash = $this->generate_seed();

        $rows = array(
            "first" => array('3', '6', '9', '12', '15', '18', '21', '24', '27', '30', '33', '36'),
            "second" => array('2', '5', '8', '11', '14', '17', '20', '23', '26', '29', '32', '35'),
            "third" => array('1', '4', '7', '10', '13', '16', '19', '22', '25', '28', '31', '34'),

            "red" => array('3', '9', '12', '18', '21', '27', '30', '36', '5', '14', '23', '32', '1', '7', '16', '19', '25', '34'),
            "black" => array('6', '15', '24', '33', '2', '8', '11', '17', '20', '26', '29', '35', '4', '10', '13', '22', '28', '31'),

            "numeric" => array(
                "first" => array('3', '6', '9', '12', '2', '5', '8', '11', '1', '4', '7', '10'),
                "second" => array('15', '18', '21', '24', '14', '17', '20', '23', '13', '16', '19', '22'),
                "third" => array('27', '30', '33', '36', '26', '29', '32', '35', '25', '28', '31', '34')
            ),

            "half" => array(
                "first" => array('3', '6', '9', '12', '15', '18', '2', '5', '8', '11', '14', '17', '1', '4', '7', '10', '13', '16'),
                "second" => array('21', '24', '27', '30', '33', '36', '20', '23', '26', '29', '32', '35', '19', '22', '25', '28', '31', '34')
            ),

            "e/o" => array(
                "even" => array('6', '12', '18', '24', '30', '36', '2', '8', '14', '20', '26', '32', '4', '10', '16', '22', '28', '34'),
                "odd" => array('3', '9', '15', '21', '27', '33', '5', '11', '17', '23', '29', '35', '1', '7', '13', '19', '25', '31')
            )
        );

        $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;

        $profit = 0;
        $multiplier = 0;

        $settings = Settings::where('id', 1)->first();

        $not = function($n) use(&$not) {
            $g = mt_rand(0, 37);
            if($g == $n) return $not($n);
            return $g;
        };
        $notInArray = function($arr) use(&$notInArray) {
            $g = mt_rand(0, 37);
            if(in_array($g, $arr)) return $notInArray($arr);
            return $g;
        };

        foreach($json as $key => $value) {
            if($value == 0) continue;
            if(is_numeric($key) && intval($key) >= 0 && intval($key) <= 36 && $rng == intval($key)) {
                if(mt_rand(0, 100) < $settings->roulette) {
                    $hash = $this->generate_seed_specific(37, $not(intval($key)));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "row1" && in_array(strval($rng), $rows['first'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['first']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "row2" && in_array(strval($rng), $rows['second'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['second']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "row3" && in_array(strval($rng), $rows['third'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['third']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "1-12" && in_array(strval($rng), $rows['numeric']['first'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['numeric']['first']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "13-24" && in_array(strval($rng), $rows['numeric']['second'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['numeric']['second']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "25-36" && in_array(strval($rng), $rows['numeric']['third'])) {
                if(mt_rand(0, 100) < $settings->roulette_3) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['numeric']['third']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === 'red' && in_array(strval($rng), $rows['red'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['red']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === 'black' && in_array(strval($rng), $rows['black'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['black']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === 'even' && in_array(strval($rng), $rows['e/o']['even'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['e/o']['even']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === 'odd' && in_array(strval($rng), $rows['e/o']['odd'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['e/o']['odd']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === '1-18' && in_array(strval($rng), $rows['half']['first'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['half']['first']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
            else if($key === "19-36" && in_array(strval($rng), $rows['half']['second'])) {
                if(mt_rand(0, 100) < $settings->roulette_2) {
                    $hash = $this->generate_seed_specific(37, $notInArray($rows['half']['second']));
                    $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 37;
                    break;
                }
            }
        }

        $x36awarded = false; $x3awarded = false;
        $awardx36 = function() use(&$x36awarded) {
            if($x36awarded) return;
            $x36awarded = true;
            if(!$this->isDemo()) Achievement::awardProgress(new Roulette25Achievement(), 1);
        };
        $awardx3 = function() use(&$x3awarded) {
            if($x3awarded) return;
            $x3awarded = true;
            if(!$this->isDemo()) Achievement::awardProgress(new Roulette150Achievement(), 1);
        };

        foreach($json as $key => $value) {
            if($value == 0) continue;
            if(is_numeric($key) && intval($key) >= 0 && intval($key) <= 36 && $rng == intval($key)) {
                $multiplier += 36;
                $profit += $value * 36;
                $awardx36();
            }
            else if($key === "row1" && in_array(strval($rng), $rows['first'])) {
                $multiplier += 3;
                $profit += $value * 3;
                $awardx3();
            }
            else if($key === "row2" && in_array(strval($rng), $rows['second'])) {
                $multiplier += 3;
                $profit += $value * 3;
                $awardx3();
            }
            else if($key === "row3" && in_array(strval($rng), $rows['third'])) {
                $multiplier += 3;
                $profit += $value * 3;
            }
            else if($key === "1-12" && in_array(strval($rng), $rows['numeric']['first'])) {
                $multiplier += 3;
                $profit += $value * 3;
                $awardx3();
            }
            else if($key === "13-24" && in_array(strval($rng), $rows['numeric']['second'])) {
                $multiplier += 3;
                $profit += $value * 3;
                $awardx3();
            }
            else if($key === "25-36" && in_array(strval($rng), $rows['numeric']['third'])) {
                $multiplier += 3;
                $profit += $value * 3;
                $awardx3();
            }
            else if($key === 'red' && in_array(strval($rng), $rows['red'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
            else if($key === 'black' && in_array(strval($rng), $rows['black'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
            else if($key === 'even' && in_array(strval($rng), $rows['e/o']['even'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
            else if($key === 'odd' && in_array(strval($rng), $rows['e/o']['odd'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
            else if($key === '1-18' && in_array(strval($rng), $rows['half']['first'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
            else if($key === "19-36" && in_array(strval($rng), $rows['half']['second'])) {
                $multiplier += 2;
                $profit += $value * 2;
            }
        }

        if(!$this->isDemo()) Achievement::awardProgress(new Roulette50Achievement(), 1);

        $win = $profit > 0;

        if(!$this->isDemo()) {
            if($win) {
                $user->money = $user->money + $profit;
                self::logTransaction($profit, 2, 10);
            } else self::logTransaction(-$totalBet, 2, 10);
            $user->save();

            $id = DB::table('games')->insertGetId([
                'bet' => $totalBet,
                'user_id' => Auth::user()->id,
                'type' => -1,
                'cell_1' => $rng,
                'cell_2' => -1,
                'cell_3' => -1,
                'cell_4' => -1,
                'win' => $profit,
                'status' => $win ? 1 : 0,
                'game_id' => 10,
                'multiplier' => $multiplier,
                'server_seed' => $hash,
                'time' => $this->game_time(),
                'demo' => $this->isDemo()
            ]);
        } else $id = -1;

        return json_encode(array(
            'response' => array(
                'totalBet' => $totalBet,
                'win' => $win,
                'profit' => $profit,
                'id' => $id,
                'number' => $rng,
                'demo' => $this->isDemo() ? 'true' : 'false'
            )
        ));
    }

    public static function getPlinkoMultipliers() {
        return [
            'low' => [
                8 => [
                    5.3, 2.2, 1.1, 0.9, 0.5, 0.9, 1.1, 2.2, 5.3
                ],
                9 => [
                    5.0, 2.2, 1.4, 1.1, 0.6, 0.6, 1.1, 1.4, 2.2, 5.0
                ],
                10 => [
                    9.0, 3.1, 1.4, 1.1, 0.9, 0.5, 0.9, 1.1, 1.4, 3.1, 9.0
                ],
                11 => [
                    8.0, 2.9, 1.8, 1.3, 0.9, 0.7, 0.7, 0.9, 1.3, 1.8, 2.9, 8.0
                ],
                12 => [
                    10, 3.3, 1.9, 1.5, 1.1, 0.9, 0.4, 0.9, 1.1, 1.5, 1.9, 3.3, 10
                ],
                13 => [
                    8.0, 4.3, 3.1, 1.9, 1.2, 0.9, 0.6, 0.6, 0.9, 1.2, 1.9, 3.1, 4.3, 8.0
                ],
                14 => [
                    7.1, 5.0, 2.1, 1.8, 1.4, 1.1, 0.8, 0.5, 0.8, 1.1, 1.4, 1.8, 2.1, 5.0, 7.1
                ],
                15 => [
                    15, 7.0, 3.0, 2.0, 1.4, 1.2, 1.1, 0.5, 0.5, 1.1, 1.2, 1.4, 2.0, 3.0, 7.0, 15
                ],
                16 => [
                    16, 8.4, 4.0, 1.9, 1.4, 1.2, 1.1, 0.9, 0.4, 0.9, 1.1, 1.2, 1.4, 1.9, 4.0, 8.4, 16
                ]
            ],
            'medium' => [
                8 => [
                    13, 3.1, 1.3, 0.6, 0.4, 0.6, 1.3, 3.1, 13
                ],
                9 => [
                    16, 4.1, 1.8, 0.9, 0.4, 0.4, 0.9, 1.8, 4.1, 16
                ],
                10 => [
                    21, 5.2, 1.8, 1.3, 0.6, 0.4, 0.6, 1.3, 1.8, 5.2, 21
                ],
                11 => [
                    24, 6.0, 3.1, 1.8, 0.7, 0.4, 0.4, 0.7, 1.8, 3.1, 6.0, 24
                ],
                12 => [
                    32, 11, 4.0, 2.0, 1.1, 0.5, 0.3, 0.5, 1.1, 2.0, 4.0, 11, 32
                ],
                13 => [
                    40, 15, 5.3, 3.0, 1.3, 0.6, 0.4, 0.4, 0.6, 1.3, 3.0, 5.3, 15, 40
                ],
                14 => [
                    52, 15, 6.8, 4.0, 1.7, 1.1, 0.4, 0.2, 0.4, 1.1, 1.7, 4.0, 6.8, 15, 52
                ],
                15 => [
                    80, 18, 11, 5.0, 3.0, 1.3, 0.5, 0.2, 0.2, 0.5, 1.3, 3.0, 5.0, 11, 18, 80
                ],
                16 => [
                    110, 38, 10, 5.0, 3.1, 1.4, 1.1, 0.4, 0.2, 0.4, 1.1, 1.4, 3.1, 5.0, 10, 38, 110
                ]
            ],
            'high' => [
                8 => [
                    24, 4.0, 1.5, 0.3, 0.2, 0.3, 1.5, 4.0, 24
                ],
                9 => [
                    38, 6.4, 2.0, 0.6, 0.2, 0.2, 0.6, 2.0, 6.4, 38
                ],
                10 => [
                    70, 10, 3.2, 0.7, 0.3, 0.2, 0.3, 0.7, 3.2, 10, 70
                ],
                11 => [
                    120, 13, 5.2, 1.4, 0.3, 0.2, 0.2, 0.3, 1.4, 5.2, 13, 120
                ],
                12 => [
                    170, 21, 8.0, 2.0, 0.7, 0.2, 0.1, 0.2, 0.7, 2.0, 8.0, 21, 170
                ],
                13 => [
                    260, 32, 11, 4.0, 1.1, 0.2, 0.1, 0.1, 0.2, 1.1, 4.0, 11, 32, 260
                ],
                14 => [
                    420, 51, 16, 5.0, 2.0, 0.3, 0.2, 0.1, 0.2, 0.3, 2.0, 5.0, 16, 51, 420
                ],
                15 => [
                    620, 83, 27, 8.0, 3.0, 0.5, 0.2, 0.1, 0.1, 0.2, 0.5, 3.0, 8.0, 27, 63, 620
                ],
                16 => [
                    1000, 120, 22, 9.0, 4.0, 2.0, 0.2, 0.2, 0.1, 0.2, 0.2, 2.0, 4.0, 9.0, 22, 120, 1000
                ]
            ]
        ];
    }

    public function plinko($risk, $pins, $wager) {
        if(Game::isDisabled('plinko')) return json_encode(['error' => '$']);
        if(Auth::guest() && !$this->isDemo()) return json_encode(['error' => -1]);
        if($wager < 0.01) return json_encode(['error' => 1]);
        if($pins < 8 || $pins > 16) return json_encode(['error' => 0]);
        if($risk !== 'low' && $risk !== 'medium' && $risk !== 'high') return json_encode(['error' => 3]);

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return json_encode(['error' => 2]);
            $user->money = $user->money - $wager;
            $user->save();
        }

        $profit = 0;

        $multipliers = self::getPlinkoMultipliers()[$risk][$pins];
        $multiplierId = mt_rand(0, sizeof($multipliers) - 1);
        $multiplier = $multipliers[$multiplierId];

        if($this->shouldFake(13) || $this->isItWayTooMuchW(13, $multiplier, $wager)) {
            $findLose = function() use($multipliers, &$findLose) {
                $id = mt_rand(0, sizeof($multipliers) - 1);
                if($multipliers[$id] >= 1) return $findLose();
                return $id;
            };
            $multiplierId = $findLose();
            $multiplier = $multipliers[$multiplierId];
        }

        $profit = $wager * $multiplier;
        $win = $profit > 0;

        $id = Game::insertGetId([
            'bet' => $wager,
            'user_id' => self::isDemo() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => -1,
            'cell_2' => -1,
            'cell_3' => -1,
            'cell_4' => -1,
            'win' => $profit,
            'status' => $win ? 1 : 0,
            'game_id' => 13,
            'multiplier' => $multiplier,
            'server_seed' => null,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        if($win && !$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->money = $user->money + $profit;
            $user->save();

            self::logTransaction($profit, 2, 12);

            Achievement::awardProgress(new Plinko25Achievement(), 1);
            Achievement::awardProgress(new Plinko250Achievement(), 1);
            Achievement::awardProgress(new Plinko1000Achievement(), 1);
        } else if(!$this->isDemo())
            self::logTransaction(-$wager, 2, 12);

        return json_encode([
            'id' => $id,
            'multiplier' => $multiplier,
            'profit' => $profit,
            'bucket' => $multiplierId + 1
        ]);
    }

    public function keno($pickedArray, $wager) {
        if(Game::isDisabled('keno')) return json_encode(['error' => '$']);
        if(Auth::guest() && !$this->isDemo()) return json_encode(['error' => -1]);
        if($wager < 0.01) return response(json_encode(['error' => 1]));

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return json_encode(['error' => 2]);
            $user->money = $user->money - $wager;
            $user->save();
        }

        $json = json_decode($pickedArray);
        if(sizeof($json) > 10) return json_encode(['error' => 0]);

        $picked = [];

        $win = false;

        $multipliers = [
            [0, 3.8],
            [0, 1.7, 5.2],
            [0, 0, 2.7, 48],
            [0, 0, 1.7, 10, 84],
            [0, 0, 1.4, 4, 14, 290],
            [0, 0, 0, 3, 9, 160, 720],
            [0, 0, 0, 2, 7, 30, 280, 800],
            [0, 0, 0, 2, 4, 10, 50, 300, 850],
            [0, 0, 0, 2, 2.5, 4.5, 12, 60, 320, 900],
            [0, 0, 0, 1.5, 2, 4, 6, 22, 80, 400, 1000]
        ];

        $findLose = function() use(&$picked, &$json, &$findLose) {
            $index = mt_rand(1, 40);
            if(in_array($index, $picked) || in_array($index, $json)) return $findLose();
            return $index;
        };

        $fake = $this->shouldFake(14);
        for($i = 0; $i < 10; $i++) {
            $semiFake = mt_rand(0, 100) > 70;
            array_push($picked, $fake && !$semiFake ? $findLose() : mt_rand(1, 40));
        }

        $hits = 0;
        $profit = 0;
        $multiplier = 0;

        $correct = [];

        for($i = 0; $i < 10; $i++) {
            if(in_array($json[$i] ?? [], $picked)) {
                $hits++;

                $multiplier = $multipliers[sizeof($json) - 1][$hits];
                $profit = $wager * $multiplier;
                array_push($correct, $json[$i]);
            }
        }

        $win = $profit > 0;

        $id = Game::insertGetId([
            'bet' => $wager,
            'user_id' => self::isDemo() ? -1 : Auth::user()->id,
            'type' => -1,
            'cell_1' => -1,
            'cell_2' => -1,
            'cell_3' => -1,
            'cell_4' => -1,
            'win' => $profit,
            'status' => $win ? 1 : 0,
            'game_id' => 14,
            'multiplier' => $multiplier,
            'server_seed' => null,
            'time' => $this->game_time(),
            'demo' => $this->isDemo()
        ]);

        if($win && !$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->money = $user->money + $profit;
            $user->save();

            self::logTransaction($profit, 2, 13);

            Achievement::awardProgress(new Keno25Achievement(), 1);
            Achievement::awardProgress(new Keno250Achievement(), 1);
            Achievement::awardProgress(new Keno1000Achievement(), 1);
        } else if(!$this->isDemo()) self::logTransaction(-$wager, 2, 13);

        return json_encode([
            'id' => $id,
            'grid' => $picked,
            'correct' => $correct,
            'hits' => $hits,
            'multiplier' => $multiplier,
            'profit' => $profit
        ]);
    }

    public function dice($wager, $type, $chance) {
        if(Game::isDisabled('dice')) return response('{"error":"$"}');
        if(Auth::guest() && !$this->isDemo()) return response('{"error":-1}');
        if($wager < 0.01) return response('{"error":1}');

        if($type != 'lower' && $type != 'higher') return response('{"error":0}');
        if($type == 'lower' && $chance > 94) return response('{"error":0}');
        if($type == 'higher' && $chance < 6) return response('{"error":0}');
        if($chance < 1 || $chance > 99) return response('{"error":0}');

        if(!$this->isDemo()) {
            $user = User::where('id', Auth::user()->id)->first();
            if ($user->money < $wager) return response('{"error":2}');
        }

        $profit = $this->dice_get_profit_type($type, $wager, $chance);
        $low = $type == 'lower' ? 0 : $chance;
        $high = $type === 'higher' ? 100 : $chance;

        $hash = $this->generate_seed();
        if(!$this->isDemo()) {
            if($this->shouldFake(1) || $this->isItWayTooMuchW(1, $this->getPayout($low, $high), $wager)) {
                if($type == "lower") $hash = $this->generate_seed_range(100, $chance + 1, 100);
                else $hash = $this->generate_seed_range(100, 0, $chance - 1);
            }
        }

        $rng = ((int) $this->provably_fair($hash, $this->get_client_seed())['result']) % 100;

        if(($type == "lower" && $rng <= $chance) || ($type == "higher" && $rng >= $chance)) $win = true;
        else $win = false;

        $id = -1;

        if(!$this->isDemo()) {
            if ($win) {
                $user->money = $user->money + $profit;
                self::logTransaction($profit, 2, 1);

                Achievement::awardProgress(new Dice50Achievement(), 1);
                Achievement::awardProgress(new Dice200Achievement(), 1);
                Achievement::awardProgress(new Dice1000Achievement(), 1);
            } else {
                $user->money = $user->money - $wager;
                self::logTransaction(-$wager, 2, 1);
            }
            $user->save();

            $id = DB::table('games')->insertGetId([
                'bet' => $wager,
                'user_id' => Auth::user()->id,
                'type' => -1,
                'cell_1' => $chance,
                'cell_2' => $rng,
                'cell_3' => -1,
                'cell_4' => $type,
                'win' => $profit,
                'status' => $win ? 1 : 0,
                'game_id' => 1,
                'multiplier' => $this->getPayout($low, $high),
                'server_seed' => $hash,
                'time' => $this->game_time(),
                'demo' => $this->isDemo()
            ]);
        }

        return json_encode(array(
            'response' => array(
                'id' => $id,
                'number' => $rng,
                'profit' => ($win ? '+' : '-') . $profit,
                'result' => $win,
                'demo' => $this->isDemo() ? 'true' : 'false'
            )
        ));
    }

    private function getPayout($min, $max) {
        if($min == $max) return 100.0;
        $range = $max - $min;
        return ((100.0 - $range) / $range);
    }

    public function api_money() {
        if(Auth::guest()) return 0;
        return Auth::user()->money;
    }

	public function get_drop() {
		$game = Game::where([
		    ['status', '<>', '-1'],
            ['user_id', '<>', '-1']
        ])->orderBy('id', 'desc')->limit(10)->get();

		$drops = array();
		foreach($game as $p) {
			$info = array(
			    "id" => $p->id,
			    'game_id' => $p->game_id,
			    "name" => $this->getGameName($p->game_id),
                "icon" => $this->getGameIcon($p->game_id),
                "bet" => $p->bet,
                "amount" => $p->win,
                "user_id" => $p->user_id,
                "time" => $p->time == null ? null : $this->get_time($p->time),
                "username" => $p->user_id == -2 ? -1 : User::where('id', $p->user_id)->first()->username,
                "mul" => $p->multiplier == null ? 'Unknown' : $p->multiplier,
                "status" => $p->status
            );
			array_push($drops, $info);
		}
		return json_encode(array_reverse($drops));
	}

	public function user_drops($id, $page) {
        $start = 10 * $page;

        $game = Game::where([
            ['status', '!=', '-1'],
            ['user_id', '=', $id],
            ['multiplier', '!=', null]
        ])->orderBy('id', 'desc')->skip($start)->limit(10)->get();

        $drops = array();
        foreach($game as $p) {
            $info = array(
                "id" => $p->id,
                "game_id" => $p->game_id,
                "name" => $this->getGameName($p->game_id),
                "icon" => $this->getGameIcon($p->game_id),
                "bet" => $p->bet,
                "amount" => $p->status == 0 ? '0.00' : $p->win,
                "user_id" => $p->user_id,
                "time" => $p->time == null ? null : $this->get_time($p->time),
                "username" => User::where('id', $p->user_id)->first()->username,
                "mul" => $p->multiplier == null ? 'Unknown' : $p->multiplier
            );
            array_push($drops, $info);
        }
        return json_encode(array_reverse($drops));
    }

    public function user($id) {
        $user = User::where('id', $id)->first();
        if($user == null) return json_encode(array('error' => -1));

        return json_encode(array(
            'id' => $user->id,
            'name' => $user->username,
            'avatar' => $user->avatar
        ));
    }

	public function drop(\Illuminate\Http\Request $r) {
		if(!isset($r->id)) return "Не переданы параметры";
		$g = Game::where('id', $r->id)->first();
		if($g == null || $g->status == -1) return '-1';

		$info = array(
		    "id" => $g->id,
		    "game_id" => $g->game_id,
		    "status" => $g->status,
		    "name" => $this->getGameName($g->game_id),
		    "icon" => $this->getGameIcon($g->game_id),
            "bet" => $g->bet,
            "amount" => $g->win,
            "user_id" => $g->user_id,
            "time" => $g->time == null ? null : $this->get_time($g->time),
            "username" => $g->user_id == -2 ? -1 : User::where('id', $g->user_id)->first()->username,
            "mul" => $g->multiplier == null ? 'Unknown' : $g->multiplier,
            "server_seed" => $g->server_seed == null ? null : $g->server_seed
        );
		return json_encode($info);
	}

	public function notifyBonus() {
        if(Auth::guest()) return json_encode(['error' => -1]);
        if(Auth::user()->notify_bonus == 1) return json_encode(['error' => 0]);
        $user = User::where('id', Auth::user()->id)->first();
        $user->money = $user->money + 1;
        $user->notify_bonus = 1;
        $user->save();
        return json_encode(['success' => 1]);
    }

    public function readNotifications() {
        if(Auth::guest()) return '-1';
        foreach(Notification::where('user_id', Auth::user()->id)->where('read_status', 0)->get() as $notification) {
            $notification->read_status = 1;
            $notification->save();
        }
        return '1';
    }

	public function asyncBonus() {
        if(!Auth::guest()) {
            $user = User::where('id', Auth::user()->id)->first();
            if($user->vk_bonus == 0 && Auth::user()->isSubscribed($user->login2)) {
                $user->money = $user->money + 0.45;
                $user->vk_bonus = 1;
                $user->save();
                self::logTransaction(0.45, 6, null);
            }

            if($user->welcome_notification == 0) {
                Notification::send($user->id, 'fad fa-galaxy', 'Win5X',
                    'Добро пожаловать на Win5X!'
                    . '<br>Посетите <a class="ll" href="javascript:void(0)" onclick="load(\'bonus\')">страницу с бонусами</a> для начала игры.'
                    . '<br>Есть вопросы? Задавайте их в чат или в <a class="ll" href="javascript:void(0)" onclick="window.open(\'https://vk.com/playintm\', \'_blank\')">службу поддержку Win5X</a>!');
                $user->welcome_notification = 1;
                $user->save();
            }

            if($user->level >= 3) Achievement::award(new Level3Achievement(), $user->id);
            if($user->level >= 5) Achievement::award(new Level5Achievement(), $user->id);
            if($user->level >= 8) Achievement::award(new Level8Achievement(), $user->id);
            if($user->level >= 10) Achievement::award(new Level10Achievement(), $user->id);

            $vvod = DB::table('promocodes')->where('code', Auth::user()->ref_code)->get();
            $settings = Settings::where('id', 1)->first();
            foreach($vvod as $v) {
                if(count(\App\User::whereRaw('JSON_CONTAINS(`ref_in_used`, \''.$v->user.'\', \'$\')')->get()) > 0) continue;
                if(\App\Game::where('user_id', $v->user)->where('status', 1)->sum('win') < 5) continue;

                $arr = json_decode($user->ref_in_used);
                array_push($arr, $v->user);
                $user->ref_in_used = json_encode($arr);

                $user->money = $user->money + $settings->promo_sum;
                $user->save();

                self::logTransaction($settings->promo_sum, 7, null);
                Achievement::awardProgress(new Referral10Achievement(), 1, $user->id);
                Achievement::awardProgress(new Referral100Achievement(), 1, $user->id);
                Achievement::awardProgress(new Referral500Achievement(), 1, $user->id);
            }
        }
    }

    public function sport_fragment($page, $id = -1) {
        if(!view()->exists('pages.sport.fragment.'.$page)) return response()->view('errors.404', [], 404);
        if((!Auth::guest() && !Auth::user()->isActivated()) || Game::isDisabled($page)) return response()->view('errors.403', [], 403);
        $page = str_replace('/', '.', $page);
        return view('pages.sport.fragment.'.$page, compact('id'));
    }

    public function sport_line($page, $id) {
        if(!view()->exists('pages.sport.line.'.$page)) return response()->view('errors.404', [], 404);
        if((!Auth::guest() && !Auth::user()->isActivated()) || Game::isDisabled($page)) return response()->view('errors.403', [], 403);
        $page = str_replace('/', '.', $page);
        if(Request::ajax()) return view('pages.sport.line.'.$page, compact('id'));
        return view('layout')->with('page', view('pages.sport.line.'.$page, compact('id')));
    }

    public function sport_page($page = 'main') {
        if(!view()->exists('pages.sport.'.$page)) return response()->view('errors.404', [], 404);
        if((!Auth::guest() && !Auth::user()->isActivated()) || Game::isDisabled($page)) $page = 'main';
        $page = str_replace('/', '.', $page);
        $settings = Settings::where('id', 1)->first();
        if(Request::ajax()) return view('pages.sport.'.$page, compact('settings'));
        return view('layout')->with('page', view('pages.sport.'.$page, compact('settings')));
    }

    public function page($page = 'games') {
        if(!view()->exists('pages.'.$page)) return response()->view('errors.404', [], 404);
        if((!Auth::guest() && !Auth::user()->isActivated()) || Game::isDisabled($page)) $page = 'games';
        $page = str_replace('/', '.', $page);
        $settings = Settings::where('id', 1)->first();
        if(Request::ajax()) return view('pages.'.$page, compact('settings'));
        return view('layout')->with('page', view('pages.'.$page, compact('settings')));
    }

    public function payout(\Illuminate\Http\Request $r){
        if(!isset($r->amount) || !isset($r->currency) || !isset($r->provider) || !isset($r->purse)) return json_encode(array('error' => -2));
        else {
            if(Auth::guest()) return json_encode(array('error' => 1));
            $settings = Settings::where('id', 1)->first();
            if($r->amount < $settings->min_with) return json_encode(array('error' => 0, 'value' => $settings->min_with));
            if(Auth::user()->money < $r->amount) return json_encode(array('error' => 1));
            if($r->purse == '') return json_encode(array('error' => 2));
            $count = DB::table('withdraw')->where('user_id', Auth::user()->id)->where('status', 0)->count();
            if($count > 0) return json_encode(array('error' => 3));
            $user = User::where('id', Auth::user()->id)->first();
            $user->money = $user->money - $r->amount;
            $user->save();
            self::logTransaction(-$r->amount, 8, $r->purse);
            DB::table('withdraw')->insertGetId(['user_id' => Auth::user()->id, 'system' => $r->currency, 'wallet' => $r->purse ,'amount' => $r->amount]);
            return json_encode($r->amount);
        }
    }

    public function get_refs() {
        if(Auth::guest()) return json_encode([]);
        $vvod = DB::table('promocodes')->where('code', Auth::user()->ref_code)->orderBy('id', 'desc')->get();
        if(count($vvod) > 0) {
            foreach($vvod as $v) {
                $v->username = User::where('id', $v->user)->first()->username;
                $v->active = \App\Game::where('user_id', $v->user)->where('status', 1)->sum('win') > 5;
            }
        }
        return json_encode($vvod);
    }

    public function achievements($id, $category) {
        $result = [];
        $array = $category === 'all' ? Achievements::instances() : Achievements::get($category);

        foreach ($array as $achievement) {
            $unlocked = Achievement::unlockStatus($achievement, $id);
            array_push($result, [
                'name' => $achievement->name(),
                'description' => $achievement->description(),
                'badge' => $achievement->badge(),
                'reward' => $achievement->reward(),
                'unlock' => $unlocked,
                'progress' => [
                    'current' => $unlocked ? $achievement->progress() : Achievement::getProgress($achievement, $id),
                    'max' => $achievement->progress(),
                    'percent' => $unlocked ? 100 : (Achievement::getProgress($achievement, $id) / $achievement->progress()) * 100
                ]
            ]);
        }

        return json_encode($result);
    }

    public function invoice($amount, $type) {
        if(Auth::guest()) return '-1';
        $settings = Settings::where('id', 1)->first();
        if($amount < $settings->min_in) $amount = $settings->min_in;

        $id = DB::table('payments')->insertGetId([
            'amount' => $amount,
            'user' => Auth::user()->id,
            'time' => time(),
            'status' => 0
        ]);

        $data = [
            'm' => $settings->ap_id,
            'oa' => $amount,
            'o' => $id,
            's' => md5($settings->ap_id.':'.$amount.':'.$settings->ap_secret.':'.$id),
            'lang' => 'ru',
            'i' => $type
        ];
        return redirect('http://www.free-kassa.ru/merchant/cash.php?'.http_build_query($data));
    }

    public function paymentStatus(\Illuminate\Http\Request $r) {
        $settings = Settings::where('id', 1)->first();

        $sign = md5($settings->ap_id.':'.$r->AMOUNT.':'.$settings->ap_api_key.':'.$r->MERCHANT_ORDER_ID);
        if($sign !== $r->SIGN) {
            if(!Auth::guest()) self::logTransaction(0, 16, 'Ошибка подписи', Auth::user()->id);
            return 'Ошибка подписи';
        }

        $payment = DB::table('payments')->where('id', $r->MERCHANT_ORDER_ID)->first();
        if(count($payment) == 0) {
            if(!Auth::guest()) self::logTransaction(0, 16, 'Платеж не найден', Auth::user()->id);
            return 'Платеж не найден';
        }

        if($payment->status != 0) {
            self::logTransaction(0, 16, 'Уже оплачен', $payment->user);
            return "Уже оплачен";
        }

        $user = User::where('id', $payment->user)->first();
        $user->money = $user->money + $payment->amount;
        $user->deposit = $user->deposit + $payment->amount;
        $user->save();

        \DB::table('payments')->where('id', $payment->id)->update(['status' => 1]);

        for($i = 0; $i < (int) ((int) $payment->amount / 10); $i++) {
            if($user->level < 3) {
                $user->level = 3;
                $user->exp = 0;
            }

            if($user->level < 10) {
                $amount = (int) ((10 / 100) * User::getRequiredExperience($user->level + 1));
                if($user->exp + $amount >= User::getRequiredExperience($user->level + 1)) {
                    $user->exp = 0;
                    $user->level = $user->level + 1;
                } else $user->exp = $user->exp + $amount;
            }

            $user->save();
        }
        self::logTransaction($payment->amount, 9, null, $user->id);
        return 'YES';
    }

    public function paymentSuccess() {
        return redirect('/');
    }

    public function paymentFail() {
        return redirect('/');
    }

    public function chat_limit_info($user_id) {
        $user = User::where('id', $user_id)->first();
        if($user->latest_event_time + 3600 > time()) return json_encode(['error' => 0]);
        $user->latest_event_time = time();
        $user->save();
        return json_encode(['success' => 0]);
    }

	public function activate($code){
		if(Auth::guest()) return json_encode(array('error' => -1));

		$promo = Promocode::where('code', $code)->first();
        if($promo != false) {
            if($promo->usages <= 0 && $promo->type == 0) return json_encode(array('error' => 0));
            if($promo->type == 1 && $promo->time + 86400 < time()) return json_encode(array('error' => 0));
            if(Auth::user()->money > 15) return json_encode(['error' => 6]);

            $user = User::where('id', Auth::user()->id)->first();
            if($promo->type == 1) {
                if($user->tp_reset < time()) {
                    $user->tp_reset = time() + 43200;
                    $user->tp_used = 0;
                    $user->save();
                }

                if($user->tp_used >= 2) return json_encode(array('error' => 5));
                $user->tp_used = $user->tp_used + 1;
                $user->save();
            }

            $arr = json_decode($user->gp_used, false);
            if(in_array($promo->id, $arr)) return json_encode(array('error' => 1));

            array_push($arr, $promo->id);
            $user->gp_used = json_encode($arr);
            $user->money = $user->money + $promo->sum;
            $user->save();

            Achievement::awardProgress(new Freebie50Achievement(), 1);
            Achievement::awardProgress(new Freebie100Achievement(), 1);
            self::logTransaction($promo->sum, 0, $promo->code);

            $promo->usages = $promo->usages - 1;
            $promo->save();

            return json_encode(array('result' => $promo->sum));
        } else {
            if (Auth::user()->ref_use != null) return json_encode(array('error' => 2));
            if (Auth::user()->ref_code === $code) return json_encode(array('error' => 3));

            $referer = User::where('ref_code', $code)->first();
            if ($referer == false || strlen($code) < 1) return json_encode(array('error' => 4));
            else {
                $summ = Settings::where('id', 1)->first();
                $user = User::where('id', Auth::user()->id)->first();

                $user->ref_use = $code;
                $user->money = $user->money + $summ->promo_sum;
                $user->save();
                self::logTransaction($summ->promo_sum, 1, $code);

                DB::table('promocodes')->insertGetId(["code" => $code, "user" => Auth::user()->id]);
                return json_encode(array('result' => $summ->promo_sum));
            }
        }
	}

    public static function getip() {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }

    public function isDomainAvailable($domain) {
        if(!filter_var($domain, FILTER_VALIDATE_URL)) return false;

        $curlInit = curl_init($domain);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);
        if ($response) return true;
        return false;
    }

    public function percentageIncrease($old, $new) {
        if($new != 0) {
            if($old != 0) $percent = ($new - $old) / $old * 100;
            else $percent = $new * 100;
        } else $percent = - $old * 100;
        return floor($percent) / 100 + 1;
    }

    public function open_case($id) {
        if(Auth::guest()) return json_encode(['error' => -1]);

        $case = Box::where('id', $id)->first();
        $user = User::where('id', Auth::user()->id)->first();
        if($case == null || $user == null) return json_encode(['error' => 0]);

        if($case->is_free == 1) {
            if(!Box::isFreeAvailable()) return json_encode(['error' => 2]);
            $user->free_case_time = time();
        } else {
            if($user->money < $case->price) return json_encode(['error' => 1]);
            $user->money = $user->money - $case->price;
            self::logTransaction(-$case->price, 15, null);
        }
        $user->save();

        $items = json_decode($case->contains, true);

        $item = null;

        foreach($items as $it) {
            if(mt_rand(0, 100) <= intval($it['chance'])) {
                $item = $it;
                $rand = array_search($it, $items);
                break;
            }
        }

        if($item == null) {
            $rand = mt_rand(0, sizeof($items) - 1);
            $item = $items[$rand];
        }

        if($item['type'] == 1) {
            $user->money = $user->money + floatval($item['value']);
            $user->save();
            self::logTransaction(floatval($item['value']), 15, null);
        }
        if($item['type'] == 2) {
            if(Auth::user()->level == 10) {
                $user->money = $user->money + (floatval($item['value']) / 20);
                $user->save();
                self::logTransaction(floatval($item['value']) / 20, 15, null);
            } else User::expPercent(intval($item['value']));
        }

        $id = Game::insertGetId([
            'bet' => $case->price,
            'user_id' => Auth::user()->id,
            'type' => -1,
            'cell_1' => -1,
            'cell_2' => -1,
            'cell_3' => -1,
            'cell_4' => -1,
            'win' => $item['type'] == 1 ? floatval($item['value']) : -1,
            'status' => 1,
            'game_id' => 12,
            'multiplier' => -1,
            'server_seed' => null,
            'time' => $this->game_time(),
            'demo' => false
        ]);

        return json_encode([
            'id' => $id,
            'send' => $item['type'] == 1,
            'item' => $rand,
            'free' => $case->is_free == 1
        ]);
    }

    public function isItWayTooMuchW($game_id, $multiplier, $wager) {
        if((!Auth::guest() && Auth::user()->chat_role == 1) || $this->isDemo()) return false;
        $maxMul = AdminController::getAdjustmentValues($game_id)['mm'];
        if($maxMul != -1 && $multiplier >= $maxMul) return true;
        return $this->percentageIncrease($wager, $wager * $multiplier) >= Settings::where('id', 1)->first()->max_bet_increase;
    }

    public function isItWayTooMuch($gameId, $multiplier) {
        if((!Auth::guest() && Auth::user()->chat_role == 1) || $this->isDemo($gameId)) return false;
        $game = Game::where('id', $gameId)->first();

        $maxMul = AdminController::getAdjustmentValues($game->game_id)['mm'];
        if($maxMul != -1 && $multiplier >= $maxMul) return true;

        return $this->percentageIncrease($game->bet, $game->bet * $multiplier) >= Settings::where('id', 1)->first()->max_bet_increase;
    }

    public static function history($game_id, $e) {
        $game = Game::where('id', $game_id)->first();
        $arr = json_decode($game->history);
        if(in_array($e, $arr)) return true;
        array_push($arr, $e);
        $game->history = json_encode($arr);
        $game->save();
        return false;
    }

}
