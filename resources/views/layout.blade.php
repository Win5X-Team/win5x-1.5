@php($settings = \App\Settings::where('id', 1)->first())
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Win5X - игры с выводом денег</title>
    <meta name="description" content="Win5x — онлайн игры на реальные деньги. Начни зарабатывать с Win5x прямо сейчас!" />
    <meta name="keywords" content="win5x, заработок без вложений, инвестиция, игры на деньги, онлайн игры, win, казино, dice, рулетка, blackjack, tower, wheel, coinflip, hilo, мины, stairs">

    <link rel="stylesheet" href="{{ $css('/css/loader.css') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/storage/img/logo.jpg?v='.$version) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/storage/img/logo.jpg?v='.$version) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/storage/img/logo.jpg?v='.$version) }}">

    <meta name="theme-color" content="#000000">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Win5x — игры с выводом денег">
    <meta property="og:description" content="Win5x — онлайн игры на реальные деньги. Начни зарабатывать с Win5x прямо сейчас!">
    <meta property="og:image" content="{{ asset('/storage/img/logo.jpg?v='.$version) }}">
    <meta property="og:url" content="https://win5x.com">
    <meta property="business:contact_data:country_name" content="Russia">
    <base href="/">
</head>
<body>
    <script type="text/javascript" src="{{ asset('/js/vendor/jquery-1.11.1.min.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/jquery.nanoscroller.min.js?v='.$version) }}"></script>
    <script type="text/javascript">
        @php
            echo "var _css = ".json_encode([
                $css('/css/app.css'),
                asset('/css/fa-all.min.css?v='.$version),
                asset('/css/line-awesome.min.css?v='.$version),
                $css('/css/jquery-ui.css'),
                $css('/css/emojionearea.css'),
                asset('/css/iziToast.min.css?v='.$version),
                asset('/css/chart.min.css?v='.$version),
                asset('/css/nanoscroller.css?v='.$version),
                asset('/css/tooltipster.bundle.min.css?v='.$version),
                asset('/css/tooltipster-sideTip-punk.min.css?v='.$version),
                asset('/css/slick.css?v='.$version),
                asset('/css/slick-theme.css?v='.$version)
            ]).';';
        @endphp
    </script>

    <div class="profile-loader loader" style="display: flex">
        <div></div>
    </div>
    <div class="sport_sidebar">
        <div class="sport_sidebar_header">
            <i class="fad sport_unminimize" onclick="swapSportSidebar()"></i>
            <i class="fas fa-play" onclick="load('sport')"></i>
            <span onclick="load('sport')">Прямой эфир</span>
            <span class="sport_live_number" onclick="load('sport')" data-watch-id="-1" data-watch-game="total" style="display: none"><span data-watch="total">0</span></span>
        </div>
        <div class="sport_sidebar_footer">
            <div class="sport_sidebar_footer_purchase_info couponPurchaseOnly dn">
                <div>
                    Сумма
                    <span id="betTotal">0.00 руб.</span>
                </div>
            </div>
            <div class="sport_sidebar_footer_purchase_button couponPurchaseOnly dn">
                Приобрести
            </div>
            <div class="sport_sidebar_category" onclick="swapTicketSidebar()">
                <i class="fas fa-ticket-alt"></i>
                <span>Ставка</span>
            </div>
        </div>
        <div class="sport_sidebar_tickets dn">
            <div class="ticket_tabs">
                <div class="sport_sidebar_category ticket-tab-active" data-ticket-tab="#coupons">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Купоны</span>
                </div>
                <div class="sport_sidebar_category" data-ticket-tab="#bets">
                    <i class="fad fa-scroll"></i>
                    <span>Мои ставки</span>
                </div>
            </div>
            <div class="ticket-cbx">
                <input class="inp-cbx" id="autoMConfirm" type="checkbox" style="display: none;">
                <label class="cbx" for="autoMConfirm">
                    <span><svg width="12px" height="10px" viewbox="0 0 12 10"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
                    <span>Автопринятие изменения коэфф.</span>
                </label>
            </div>
            <div class="ticket-tab ticket-tab-active ticket-purchase-nano" id="coupons">
                <div class="nano">
                    <div class="nano-content" id="pending_bets">
                        <div class="sport-empty-bets">Вы еще не делали ставок.</div>
                    </div>
                </div>
            </div>
            <div class="ticket-tab" @if(Auth::guest())) onclick="$('#b_si').click();" @else id="bets" @endif>
                <div class="nano">
                    <div class="nano-content" @if(!Auth::guest()) data-watch-fragment="/sport/fragment/sidebar_bets" @endif></div>
                </div>
            </div>
        </div>
        <div class="nano sport_sidebar_games">
            <div class="nano-content">
                <div class="sport_sidebar_category" data-game="sport/soccer" onclick="load('sport/soccer')">
                    <i class="fal fa-futbol"></i>
                    <span>Футбол</span>
                </div>
                <!-- TODO Below -->
                <div class="sport_sidebar_category" data-game="sport/tennis" onclick="load('sport/tennis')">
                    <i class="fas fa-tennis-ball"></i>
                    <span>Теннис</span>
                </div>
                <div class="sport_sidebar_category" data-game="sport/basketball" onclick="load('sport/basketball')">
                    <i class="fas fa-basketball-ball"></i>
                    <span>Баскетбол</span>
                </div>
                <div class="sport_sidebar_category" data-game="sport/hockey" onclick="load('sport/hocket')">
                    <i class="fas fa-hockey-puck"></i>
                    <span>Хоккей</span>
                </div>
                <div class="sport_sidebar_category" data-game="sport/volleyball" onclick="load('sport/volleyball')">
                    <i class="fas fa-volleyball-ball"></i>
                    <span>Воллейбол</span>
                </div>
                <div class="sport_sidebar_category" data-game="sport/table_tennis" onclick="load('sport/table_tennis')">
                    <i class="fas fa-table-tennis"></i>
                    <span>Настольный теннис</span>
                </div>
                <div class="sport_sidebar_category" data-game="sport/baseball" onclick="load('sport/baseball')">
                    <i class="fas fa-baseball-ball"></i>
                    <span>Бейсбол</span>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden-xs gg_sidebar">
        <div class="gg_sidebar_main">
            <div class="nano gg_sidebar_container">
                <div class="nano-content gg_s_content">
                    <div class="gg_sidebar-item i_y_i"
                         @if(Auth::guest())
                             onclick="$('#b_si').click()"
                         @else
                            onclick="load('bonus')"
                        @endif>
                        <i class="fad fa-coins"></i>
                        <p>Бонус</p>
                    </div>
                    @if(!\App\Game::isDisabled('cases'))
                    <div class="gg_sidebar-item" data-game="cases" onclick="load('cases')">
                        @if(\App\Box::isFreeAvailable())
                            <div class="gg_sidebar-notification">1</div>
                        @endif
                        <i class="fad fa-box-open"></i>
                        <p>Кейсы</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('mines'))
                    <div class="gg_sidebar-item" data-game="mines" onclick="load('mines')">
                        <i class="fad fa-bomb"></i>
                        <p>Mines</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('plinko'))
                    <div class="gg_sidebar-item" data-game="plinko" onclick="load('plinko')">
                        <i class="fas fa-ball-pile"></i>
                        <p>Plinko</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('keno'))
                    <div class="gg_sidebar-item" data-game="keno" onclick="load('keno')">
                        <i class="fad fa-octagon"></i>
                        <p>Keno</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('stairs'))
                    <div class="gg_sidebar-item" data-game="stairs" onclick="load('stairs')">
                        <i class="la la-stream"></i>
                        <p>Stairs</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('tower'))
                    <div class="gg_sidebar-item" data-game="tower" onclick="load('tower')">
                        <i class="fad fa-chess-rook"></i>
                        <p>Tower</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('wheel'))
                    <div class="gg_sidebar-item" data-game="wheel" onclick="load('wheel')">
                        <i class="fad fa-circle"></i>
                        <p>Wheel</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('roulette'))
                    <div class="gg_sidebar-item" data-game="roulette" onclick="load('roulette')">
                        <i class="fad fa-badge"></i>
                        <p>Roulette</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('hilo'))
                    <div class="gg_sidebar-item" data-game="hilo" onclick="load('hilo')">
                        <i class="fad fa-clone"></i>
                        <p>HiLo</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('blackjack'))
                    <div class="gg_sidebar-item" data-game="blackjack" onclick="load('blackjack')">
                        <i class="fas fa-spade"></i>
                        <p>Blackjack</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('dice'))
                    <div class="gg_sidebar-item" data-game="dice" onclick="load('dice')">
                        <i class="fad fa-dice"></i>
                        <p>Dice</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('crash'))
                    <div class="gg_sidebar-item" data-game="crash" onclick="load('crash')">
                        <i class="la la-line-chart"></i>
                        <p>Crash</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('coinflip'))
                    <div class="gg_sidebar-item" data-game="coinflip" onclick="load('coinflip')">
                        <i class="fad fa-coin"></i>
                        <p>Coinflip</p>
                    </div>
                    @endif
                    @if(!\App\Game::isDisabled('battlegrounds'))
                        <div class="gg_sidebar-item" data-game="battlegrounds" onclick="battlegrounds_connect()">
                            <i class="fad fa-swords"></i>
                            <p>Battle</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="chat" data-role="<?= Auth::guest() ? '-1' : Auth::user()->chat_role ?>" style="min-width: 0; width: 0;">
        <div class="chat_button hidden-xs" onclick="swapChat()">
            <i class="fad fa-comments fa-rotate-90"></i>Чат
        </div>
        <div class="chat_header">
            <span>Чат</span>
            @if(!Auth::guest() && Auth::user()->chat_role >= 2)
                <div class="chat_event_timer tooltip tooltipstered" title="Online" style="display: none; right: unset;"><span id="online">Offline</span></div>
            @endif
        </div>
        <div class="chat_messages">
            @if(Auth::guest() || Auth::user()->is_chat_banned == 0)
            <div class="chat_status" style="opacity: 0">
                <div class="chat_loader">
                    <div class="loader-15"></div>
                </div>
                <span>Подключение...</span>
            </div>
            @else
                <div class="chat_banned">
                    Вы были заблокированы модератором
                    <br>
                    за нарушение правил чата.
                    @if(Auth::user()->chat_total_bans <= 3)
                        <br><br>
                        <div class="unban_btn" onclick="unban_chat()">Разблокировать за <?= Auth::user()->chat_total_bans * 50 ?> руб.</div>
                    @endif
                </div>
            @endif
            <div class="nano" id="chat_nano">
                <div id="chat" class="nano-content"></div>
            </div>
        </div>
        <div class="chat_input" style="opacity: 0">
            @if(!Auth::guest())
                @if(Auth::user()->mute > time())
                    <div class="chat_banned">
                        Чат заблокирован до
                        @php
                            $date = new \DateTime('now', new \DateTimeZone('Etc/GMT-3'));
                            $date->setTimestamp(Auth::user()->mute);
                            echo $date->format('d.m.Y H:i');
                        @endphp
                    </div>
                @else
                    <div class="textarea_sidebar_i">
                        <textarea class="b_input_s b_textarea" rows="2" id="chat_message" placeholder="Введите сообщение..."></textarea>
                    </div>
                    <div class="textarea_sidebar">
                        <div onclick="$('.emojionearea-button').click()"><i class="far fa-smile"></i></div>
                        <div id="chat_send" data-user-level="{{ Auth::guest() ? 1 : Auth::user()->level }}" data-user-id="{{ Auth::user()->id }}"><i class="fad fa-share-all"></i></div>
                        @if(!Auth::guest() && Auth::user()->chat_role >= 2)
                            <div class="chat_mod_special_send tooltip" title="Создать викторину" onclick="newSpecial()"><i class="fad fa-microphone-stand"></i></div>
                        @endif
                        @if(!Auth::guest() && Auth::user()->chat_role >= 3)
                            <div class="chat_mod_drop_send tooltip" title="Снег/Дождь" onclick="newDrop()"><i class="fad fa-snowflake"></i></div>
                        @endif
                    </div>
                @endif
            @else
                <div class="chat_banned">
                    Для общения в чате<br>требуется авторизация.<br>
                    <div class="unban_btn" onclick="$('#b_si').click();">Вход</div><br><br>
                </div>
            @endif
        </div>
    </div>
    <header class="header">
        <div class="container">
            <div class="header__wrapper">
                <div class="header__element header__element_static">
                    <a href="javascript:void(0)" onclick="load('games')" class="header__logo-link">
                        <img data-src="/storage/img/logo_transparent.png?v={{$version}}" alt="" class="header__logo header__logo_big lazyload">
                        <img data-src="/storage/img/logo_transparent.png?v={{$version}}" alt="" class="header__logo header__logo_small lazyload">
                    </a>
                </div>

                <div class="header__element">
                    <div class="header_nav">
                        <div onclick="load('games')" data-game="games">
                            <i class="fas fa-club"></i>
                            Игры
                        </div>
                        <div onclick="load('tasks')" data-game="games">
                            <i class="fas fa-tasks"></i>
                            Задания
                        </div>
                        <!--
                        <div onclick="load('sport')" data-game="sport">
                            <i class="fad fa-futbol"></i>
                            Спорт
                        </div>
                        -->
                    </div>
                </div>
                @if(Auth::guest())
                    <div class="header__element header__element_static">
                        <div class="header_nav auth_nav">
                            <div id="b_si" onclick="$('[data-auth-action=\'auth\']').click(); $('.md-auth').toggleClass('md-show', true)">
                                Войти
                            </div>
                            <div onclick="$('[data-auth-action=\'register\']').click(); $('.md-auth').toggleClass('md-show', true)">
                                Регистрация
                            </div>
                        </div>
                    </div>
                @else
                    <div class="header__element header__element_static">
                        <div class="wallet">
                            <div class="wallet-pay wallet-payin-icon" id="_payin" onclick="$('.md-wallet').toggleClass('md-show', true)">
                                Пополнить
                            </div>
                            <div class="wallet-icon tooltip" title="Включить/отключить демо-режим">
                                <i class="fad fa-coins"></i>
                                <i class="fal fa-angle-down"></i>
                            </div>
                            <div class="wallet-balance" id="g_balance" onclick="$('.md-wallet').toggleClass('md-show', true)">
                                {{Auth::user()->money}}
                            </div>
                        </div>
                    </div>
                    <div class="header__element header__element_static">
                        <div class="header_notifications">
                            <i id="notifications" class="fas fa-bell"></i>
                            <div data-watch-disable-loader="true" data-watch-fragment="/fragment.notifications_counter"></div>
                            <div class="header_notifications_window" data-visible="false" style="display: none">
                                <div class="header_notification_header">
                                    Уведомления
                                    <i class="header_notifications_close fal fa-times"></i>
                                </div>
                                <div class="nano">
                                    <div class="nano-content" data-watch-disable-loader="true" data-watch-fragment="/fragment.notifications"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="load('user?id={{Auth::user()->id}}')" class="header__user-ava">
                        <img data-src="{{Auth::user()->avatar}}" alt="" class="lazyload header__user-ava-img">
                    </a>
                @endif
            </div>
        </div>
    </header>
    <main>
        <div class="game">
            <div class="container container_full-width">
                <div id="_ajax_content_">
                    {!! html_entity_decode($page) !!}
                </div>
            </div>
        </div>
    </main>
    <noindex>
        <div class="ll_container">
            <div class="container">
                <div class="ll_header">
                    <div class="pulsating-circle"></div>
                    <span>Live</span>
                </div>
                <div>
                    <table class="live_table" id="ll">
                        <thead>
                            <tr class="live_table-header">
                                <th>Игра</th>
                                <th>Игрок</th>
                                <th class="hidden-xs">Время</th>
                                <th class="hidden-xs">Ставка</th>
                                <th class="hidden-xs">Коэфф.</th>
                                <th>Выигрыш</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $drops = file_exists(storage_path().'/meta/server.down') ? array() : array_reverse(json_decode(file_get_contents(url('/').'/api/get_drop'), true));
                        @endphp
                        @foreach($drops as $d)
                            @if($d['game_id'] == 12 && floatval($d['amount']) <= 0) @continue @endif

                            <tr class="live_table-game">
                                <th>
                                    <div class="live_table-animated">
                                        <div class="ll_icon hidden-xs"
                                            @if($d['user_id'] != -2)
                                             onclick="load('{{strtolower($d['name'])}}')"
                                            @else
                                             onclick="battlegrounds_connect()"
                                            @endif>
                                            <i class="{{$d['icon']}}"></i>
                                        </div>
                                        <div class="ll_game">
                                            <span
                                             @if($d['user_id'] != -2)
                                              onclick="@if($d['game_id'] != 12) load('{{strtolower($d['name'])}}') @else load('cases') @endif"
                                             @else
                                              onclick="battlegrounds_connect()"
                                             @endif>{{$d['name']}}</span>
                                            @if($d['game_id'] != 12) <p onclick="user_game_info({{$d['id']}})">Просмотр</p>
                                            @else <p onclick="load('cases')">Перейти</p> @endif
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class="live_table-animated">
                                        <a class="ll_user" href="javascript:void(0)"
                                           @if($d['user_id'] != -2)
                                            onclick="load('user?id={{$d['user_id']}}')"
                                           @else
                                            onclick="user_game_info({{$d['id']}})"
                                           @endif>
                                            @if($d['user_id'] != -2) {{$d['username']}}
                                            @else Несколько
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th class="hidden-xs">
                                    <div class="live_table-animated">
                                        {{!isset($d['time']) || $d['time'] == null ? '' : $d['time']}}
                                    </div>
                                </th>
                                <th class="hidden-xs">
                                    <div class="live_table-animated">
                                        @if($d['user_id'] != -2) {{$d['bet']}} руб. @endif
                                    </div>
                                </th>
                                <th class="hidden-xs">
                                    <div class="live_table-animated">
                                        @if($d['user_id'] != -2 && $d['game_id'] != 12) x{{$d['mul']}} @endif
                                    </div>
                                </th>
                                <th>
                                    <div class="live_table-animated">
                                        @if($d['status'] == 1) +{{$d['amount']}} руб. @else 0.00 руб. @endif
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </noindex>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-9">
                    <div class="footer__block">
                        <div class="footer__block-header"><span style="color: white;">Win</span><span style="color: #4c4c4c">5x</span></div>
                        <hr class="footer__block-hr">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="footer_category">Информация</div>
                                <div class="footer__block-text footer__block-text_link">
                                    <a href="javascript:void(0)" onclick="load('terms')">Пользовательское соглашение</a>
                                </div>
                                <div class="footer__block-text footer__block-text_link">
                                    <a href="javascript:void(0)" onclick="load('policy')">Политика конфиденциальности</a>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="footer_category">Поддержка</div>
                                <div class="footer__block-text footer__block-text_link">
                                    <a href="javascript:void(0)" onclick="provablyfair()">Доказуемая честность</a>
                                </div>
                            </div>
                        </div>
                        <div class="footer__block-text footer__block-text_copyright">
                            Copyright © 2019-2020. Все права защищены
                        </div>
                        <a class="dn" href="https://www.free-kassa.ru/"><img alt="" class="mt20 lazyload" data-src="https://www.free-kassa.ru/img/fk_btn/14.png"></a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <div class="footer__block">
                    <div class="footer__block-header"><span style="color: #4c4c4c">Контакты</span></div>
                        <hr class="footer__block-hr">
                        <div class="footer__block-text footer__block-text_link">
                            <a href="https://vk.com/playintm" target="_blank"><span style="color: white"><i class="fab fa-vk"></i> ВКонтакте</span> Служба поддержки</a>
                        </div>
                        <div class="footer__block-text footer__block-text_link">
                            <a href="mailto:klaus.win5x@gmail.com" style="color: white !important"><i class="fal fa-at"></i> klaus.win5x@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="m-game-selection hidden-sm hidden-md hidden-lg">
        <div class="m-game-selection-item col-xs-3" data-game="games" onclick="load('games')">
            <i class="fas fa-club"></i>
            <div>Игры</div>
        </div>
        <!--
        <div class="m-game-selection-item col-xs-3" data-game="sport" onclick="load('sport')">
            <i class="fad fa-futbol"></i>
            <div>Спорт</div>
        </div>
        -->
        <div class="m-game-selection-item col-xs-3 i_b_i" onclick="swapChat()">
            <i class="fad fa-comments"></i>
            <div>Чат</div>
        </div>
        <div class="m-game-selection-item col-xs-3 i_y_i"
             onclick="@if(Auth::guest()) $('#b_si').click(); @else if(chat) swapChat(); load('bonus'); @endif">
            <i class="fad fa-coins"></i>
            <div>Бонус</div>
        </div>
    </div>

    @include('pages.layout_modals')

    <script type="text/javascript" src="{{ asset('/js/vendor/lazysizes.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/socket.io-1.4.5.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/slick.min.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/iziToast.min.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/tooltipster.bundle.min.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/emojione.min.js?v='.$version) }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vendor/emojionearea.min.js?v='.$version) }}"></script>

    <script type="text/javascript" src="{{ $asset('/ajax.js', 'js') }}"></script>
    <script type="text/javascript" src="{{ $asset('/general.js', 'js') }}"></script>
    <script type="text/javascript" src="{{ $asset('/battlegrounds_general.js', 'js') }}"></script>

    <script type="text/javascript">
        setDemo({{Auth::guest() ? 'true' : 'false'}});
    </script>

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context" : "http://schema.org",
        "@type" : "AdultEntertainment",
        "name":"Win5x",
        "url":"/",
        "aggregateRating":{
            "@type":"AggregateRating",
            "ratingValue":"5",
            "reviewCount":"5"
        },
        "priceRange":"5"
    }
    </script>

</body>
</html>
