<?php

namespace App\Http\Controllers;

use App\Build;
use App\Settings;
use Auth;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller {

    public function login(Request $r) {
        if($r->type === 'vk') return $this->vk($r);
        else if($r->type === 'google') return $this->google($r);
        else if($r->type === 'facebook') return $this->facebook($r);
        else return json_encode(['error' => 'Invalid auth type']);
    }

    public function google(Request $r) {
        $client_id = '';
        $client_secret = '';
        $redirect_uri = 'https://'.$_SERVER['SERVER_NAME'];

        if (!is_null($r->code)) {
            $url = 'https://accounts.google.com/o/oauth2/token';
            $params = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri . '/login/google',
                'grant_type' => 'authorization_code',
                'code' => $r->code
            );

            $obj = json_decode($this->curl($url, $params));

            if (isset($obj->access_token)) {
                $params['access_token'] = $obj->access_token;
                $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);

                if(isset($userInfo['id'])) {
                    $user_id = $userInfo['id'];

                    $user = User::where('login2', $user_id)->first();
                    if($user == NULL) {
                        $user = User::create([
                            'username' => $userInfo['name'],
                            'avatar' => $userInfo['picture'] ?? 'http://vk.com/images/camera_200.png',
                            'login' => $user_id,
                            'login2' => $user_id,
                            'ref_code' => substr(str_shuffle(MD5(microtime())), 0, 8),
                            'nick' => substr(str_shuffle(MD5(microtime())), 0, 8),
                            'time' => time(),
                            'email_confirmed' => 1
                        ]);

                        if(Cookie::get('ref') !== null) {
                            $referer = User::where('ref_code', Cookie::get('ref'))->first();
                            if ($referer != false) {
                                $summ = Settings::where('id', 1)->first();

                                $user->ref_use = Cookie::get('ref');
                                $user->money = $user->money + $summ->promo_sum;
                                $user->save();
                                GeneralController::logTransaction($summ->promo_sum, 12, null);

                                DB::table('promocodes')->insertGetId(["code" => Cookie::get('ref'), "user" => $user->id]);
                            }
                        }
                    } else {
                        $photo = $userInfo['picture'] ?? 'http://vk.com/images/camera_200.png';

                        if($user->is_admin == 0) {
                            $user->username = $userInfo['name'];
                            $user->avatar = $photo;
                        }
                        $user->login = $user_id;
                        $user->login2 = $user_id;
                        $user->save();
                    }

                    Auth::login($user, true);
                    return redirect('/');
                } else return json_encode(['error' => 'user id is not granted']);
            } else return json_encode(['error' => 'access_token is not granted']);
        } else return redirect('https://accounts.google.com/o/oauth2/auth?'.urldecode(http_build_query([
            'redirect_uri' => $redirect_uri . '/login/google',
            'response_type' => 'code',
            'client_id' => $client_id,
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
        ])));
    }

    public function facebook(Request $r) {
        $client_id = '';
        $client_secret = '';
        $redirect_uri = $_SERVER['SERVER_NAME'];

        if(!is_null($r->code)) {
            $url = 'https://graph.facebook.com/v3.2/oauth/access_token';
            $params = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => 'https://'.$redirect_uri.'/login/facebook',
                'code' => $r->code,
                'scope' => 'email'
            );

            $obj = json_decode(file_get_contents($url.'?'.urldecode(http_build_query($params))));

            if (isset($obj->access_token)) {
                $userInfo = json_decode(file_get_contents('https://graph.facebook.com/v3.2/me?fields=id,name,email&access_token='.$obj->access_token), true);

                if(isset($userInfo['id'])) {
                    $user_id = $userInfo['id'];

                    $user = User::where('login2', $user_id)->first();
                    if($user == NULL) {
                        $user = User::create([
                            'username' => $userInfo['name'],
                            'avatar' => $userInfo['picture'] ?? 'http://vk.com/images/camera_200.png',
                            'login' => $user_id,
                            'login2' => $user_id,
                            'ref_code' => substr(str_shuffle(MD5(microtime())), 0, 8),
                            'nick' => substr(str_shuffle(MD5(microtime())), 0, 8),
                            'time' => time(),
                            'email_confirmed' => 1
                        ]);

                        if(Cookie::get('ref') !== null) {
                            $referer = User::where('ref_code', Cookie::get('ref'))->first();
                            if ($referer != false) {
                                $summ = Settings::where('id', 1)->first();

                                $user->ref_use = Cookie::get('ref');
                                $user->money = $user->money + $summ->promo_sum;
                                $user->save();
                                GeneralController::logTransaction($summ->promo_sum, 12, null);

                                DB::table('promocodes')->insertGetId(["code" => Cookie::get('ref'), "user" => $user->id]);
                            }
                        }
                    } else {
                        $photo = $userInfo['picture'] ?? 'http://vk.com/images/camera_200.png';

                        if($user->is_admin == 0) {
                            $user->username = $userInfo['name'];
                            $user->avatar = $photo;
                        }
                        $user->login = $user_id;
                        $user->login2 = $user_id;
                        $user->save();
                    }

                    Auth::login($user, true);
                    return redirect('/');
                } else return json_encode(['error' => 'user id is not granted']);
            } else return json_encode(['error' => 'access_token is not granted']);
        } else return redirect('https://www.facebook.com/v3.2/dialog/oauth?'.urldecode(http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => 'https://' . $redirect_uri . '/login/facebook',
            'response_type' => 'code',
            'state' => '{st=xbnf52l,ds=731562}',
            'scope' => 'email'
        ])));
    }

    public function vk(Request $r) {
        $client_id = '';
        $client_secret = '';
        $redirect_uri = $_SERVER['SERVER_NAME'];

        if (!is_null($r->code)) {
            $obj = json_decode($this->curl('https://oauth.vk.com/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=http://' . $redirect_uri . '/login/vk&code=' . $r->code));

            if (isset($obj->access_token)) {
                $info = json_decode($this->curl('https://api.vk.com/method/users.get?fields=photo_200&access_token=' . $obj->access_token . '&v=5.103'), true);

                $user = User::where('login2', $info['response'][0]['id'])->first();
                if($user == NULL) {
                    if(array_key_exists('photo_200', $info['response'][0])) $photo = $info['response'][0]['photo_200'];
                    else $photo = 'http://vk.com/images/camera_200.png';
                    $user = User::create([
                        'username' => $info['response'][0]['first_name'] . ' ' . $info['response'][0]['last_name'],
                        'avatar' => $photo,
                        'login' => 'id'.$info['response'][0]['id'],
                        'login2' => $info['response'][0]['id'],
                        'ref_code' => substr(str_shuffle(MD5(microtime())), 0, 8),
                        'nick' => substr(str_shuffle(MD5(microtime())), 0, 8),
                        'time' => time()
                    ]);

                    if(Cookie::get('ref') !== null) {
                        $referer = User::where('ref_code', Cookie::get('ref'))->first();
                        if ($referer != false) {
                            $summ = Settings::where('id', 1)->first();

                            $user->ref_use = Cookie::get('ref');
                            $user->money = $user->money + $summ->promo_sum;
                            $user->save();
                            GeneralController::logTransaction($summ->promo_sum, 12, null);

                            DB::table('promocodes')->insertGetId(["code" => Cookie::get('ref'), "user" => $user->id]);
                        }
                    }
                } else {
                    if(array_key_exists('photo_200', $info['response'][0]))$photo = $info['response'][0]['photo_200'];
                    else $photo = 'http://vk.com/images/camera_200.png';

                    if($user->is_admin == 0) {
                        $user->username = $info['response'][0]['first_name'] . ' ' . $info['response'][0]['last_name'];
                        $user->avatar = $photo;
                    }
                    $user->login = 'id'.$info['response'][0]['id'];
                    $user->login2 = $info['response'][0]['id'];
                    $user->save();
                }

                Auth::login($user, true);
                return redirect('/');
            }
        } else return redirect('https://oauth.vk.com/authorize?client_id=' . $client_id . '&display=page&redirect_uri=http://' . $redirect_uri . '/login/vk&scope=friends,photos,&response_type=code&v=5.53');
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }

    public function curl($url, $params = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if($params != null) curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}
